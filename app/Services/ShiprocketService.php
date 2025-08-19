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
            $this->token = Cache::get('shiprocket_token');

            if (!$this->token) {
                Log::info('No cached token found. Attempting to authenticate with Shiprocket.');

                $response = Http::timeout(30)
                    ->retry(3, 2000)
                    ->post("{$this->baseUrl}/auth/login", [
                        'email' => env('SHIPROCKET_EMAIL'),
                        'password' => env('SHIPROCKET_PASSWORD'),
                    ]);

                if ($response->failed()) {
                    Log::error('Shiprocket authentication failed with status ' . $response->status() . ': ' . $response->body());
                    throw new Exception('Failed to authenticate with Shiprocket.');
                }

                $data = $response->json();
                $this->token = $data['token'] ?? null;

                if (!$this->token) {
                    Log::error('Shiprocket authentication response does not contain a token.');
                    throw new Exception('Failed to retrieve Shiprocket token.');
                }

                Cache::put('shiprocket_token', $this->token, now()->addHour());
                Log::info('Shiprocket authenticated successfully. Token cached.');
            } else {
                Log::info('Using cached Shiprocket token.');
            }
        } catch (Exception $e) {
            Log::error('Error during Shiprocket authentication: ' . $e->getMessage());
            throw new Exception('Shiprocket authentication failed.');
        }
    }

    /**
     * Create an order in Shiprocket.
     *
     * @param  array  $orderData
     * @return array
     */
    public function createOrder(array $orderData)
    {
        try {
            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/orders/create/adhoc", $orderData);

            $data = $response->json();
            Log::info('Shiprocket Create Order Response:', $data);

            if ($response->successful()) {
                if (isset($data['shipment_id'])) {
                    return [
                        'success' => true,
                        'shipment_id' => $data['shipment_id'],
                        'awb_code' => $data['awb_code'] ?? null,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Shipment ID not returned.',
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Unknown error',
                ];
            }
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
     *
     * @param  string  $shipmentId
     * @return array
     */
    public function fetchShipmentDetails($shipmentId)
    {
        try {
            $response = Http::withToken($this->token)
                ->get("{$this->baseUrl}/shipments/{$shipmentId}");

            $data = $response->json();
            Log::info('Fetch Shipment Details Response:', $data);

            if ($response->successful() && isset($data['awb_code']) && !empty($data['awb_code'])) {
                return $data;
            } else {
                Log::warning('AWB Code is still not generated for Shipment ID: ' . $shipmentId);
                return ['message' => 'AWB Code not yet available', 'awb_code' => null];
            }
        } catch (Exception $e) {
            Log::error('Error while fetching shipment details: ' . $e->getMessage());
            throw new Exception('Error while fetching shipment details.');
        }
    }
}
