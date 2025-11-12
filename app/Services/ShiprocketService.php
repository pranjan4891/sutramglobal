<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class ShiprocketService
{
    protected $client;
    protected $baseUrl = 'https://apiv2.shiprocket.in/v1/external';
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->authenticate();
    }

    public function authenticate()
    {
        try {
            // 🚫 Prevent repeated attempts if we recently got blocked
            if (Cache::has('shiprocket_blocked')) {
                $ttl = Cache::ttl('shiprocket_blocked');
                Log::warning("Shiprocket authentication temporarily blocked. Will retry after {$ttl} seconds.");
                throw new Exception('Shiprocket authentication temporarily blocked. Please wait before retrying.');
            }

            // ✅ Reuse cached token if exists
            $this->token = Cache::get('shiprocket_token');
            if ($this->token) {
                Log::info('Using cached Shiprocket token.');
                return;
            }

            Log::info('No cached token found. Attempting to authenticate with Shiprocket.');

            // ⏳ Send login request
            $response = Http::timeout(30)
                ->retry(3, 2000)
                ->post("{$this->baseUrl}/auth/login", [
                    'email' => env('SHIPROCKET_EMAIL'),
                    'password' => env('SHIPROCKET_PASSWORD'),
                ]);

            // ❌ Handle failed responses
            if ($response->failed()) {
                $status = $response->status();
                $body = $response->body();

                Log::error("Shiprocket authentication failed [HTTP {$status}]: {$body}");

                // If Shiprocket blocked due to failed logins, back off for 15 mins
                if ($status === 403 && str_contains($body, 'User blocked')) {
                    Cache::put('shiprocket_blocked', true, now()->addMinutes(15));
                    throw new Exception('Shiprocket account temporarily blocked due to too many login attempts.');
                }

                throw new Exception("Failed to authenticate with Shiprocket. HTTP {$status}");
            }

            $data = $response->json();
            $this->token = $data['token'] ?? null;

            if (!$this->token) {
                Log::error('Shiprocket authentication response missing token. Response: ' . $response->body());
                throw new Exception('Failed to retrieve Shiprocket token.');
            }

            // ✅ Cache token for 1 hour
            Cache::put('shiprocket_token', $this->token, now()->addHour());
            Log::info('Shiprocket authenticated successfully. Token cached.');

        } catch (Exception $e) {
            Log::error('Error during Shiprocket authentication: ' . $e->getMessage());
            throw new Exception('Shiprocket authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Create an order in Shiprocket.
     */
    public function createOrder(array $orderData)
    {
        try {
            if (!$this->token) {
                $this->authenticate();
            }

            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/orders/create/adhoc", $orderData);

            $data = $response->json();
            Log::info('Shiprocket Create Order Response:', $data);

            if ($response->successful() && isset($data['shipment_id'])) {
                return [
                    'success' => true,
                    'shipment_id' => $data['shipment_id'],
                    'awb_code' => $data['awb_code'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Unknown error while creating order.',
            ];

        } catch (Exception $e) {
            Log::error('Error during Shiprocket order creation: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error creating Shiprocket order: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch shipment details from Shiprocket.
     */
    public function fetchShipmentDetails($shipmentId)
    {
        try {
            if (!$this->token) {
                $this->authenticate();
            }

            $response = Http::withToken($this->token)
                ->get("{$this->baseUrl}/shipments/{$shipmentId}");

            $data = $response->json();
            Log::info('Fetch Shipment Details Response:', $data);

            if ($response->successful() && isset($data['awb_code']) && !empty($data['awb_code'])) {
                return $data;
            }

            Log::warning("AWB Code not yet generated for Shipment ID: {$shipmentId}");
            return ['message' => 'AWB Code not yet available', 'awb_code' => null];

        } catch (Exception $e) {
            Log::error('Error fetching shipment details: ' . $e->getMessage());
            throw new Exception('Error while fetching shipment details.');
        }
    }
}
