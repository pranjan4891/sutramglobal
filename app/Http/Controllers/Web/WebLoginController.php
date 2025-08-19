<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Mail\sendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;

class WebLoginController extends Controller
{
    public function login()
    {
        $data['title'] = 'Login';
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc');
        }])->where('status', 1)->orderBy('order_by', 'asc')->get();

        $previousUrl = url()->previous();
        if (!str_contains($previousUrl, '/checkout')) {
            session(['intended_url' => $previousUrl]);
        }

        return view('web.login', $data);
    }

    public function loginCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
        ], [
            'mobile.required' => 'Please provide a mobile number.',
            'mobile.digits' => 'The mobile number must be 10 digits.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $mobile = $request->mobile;
        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            // If user doesn't exist, insert
            $user = User::create([
                'uuid' => Str::uuid(),
                'mobile' => $mobile,
                'status' => 0,
            ]);
            Session::put('is_new_user', true);
        } else {
            Session::put('is_new_user', false);
        }

        $otp = rand(100000, 999999);
        $expire_at = Carbon::now('Asia/Kolkata')->addMinutes(3);

        $user->update([
            'otp' => $otp,
            'expire_at' => $expire_at,
        ]);

        Session::put('sessionMobile', $mobile);
        Session::put('sessionExpireAt', $expire_at);

        $this->sendOtpNimbus($mobile, $otp);

        return redirect()->route('otp_verification')->with('success', 'OTP sent successfully! Please check your mobile.');
    }

    public function otp_verification(Request $request)
    {
        $data['title'] = 'Otp Verification';
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc');
        }])->where('status', 1)->orderBy('order_by', 'asc')->get();

        return view('web.otp_verification', $data);
    }

    public function otpVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp'    => 'required|array|min:6|max:6',
            'mobile' => 'required|digits:10',
        ], [
            'otp.required'  => 'The OTP is required.',
            'otp.array'     => 'The OTP must be an array of digits.',
            'otp.min'       => 'The OTP must be 6 digits.',
            'otp.max'       => 'The OTP must be 6 digits.',
            'mobile.digits' => 'The mobile number must be 10 digits.',
        ]);

        foreach ($request->otp as $digit) {
            if (!is_numeric($digit)) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('otp', 'Each OTP digit must be numeric.');
                });
                break;
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $mobile = $request->mobile;
        $enteredOtp = implode('', $request->otp);
        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            return back()->with('warning', 'No user found with the provided mobile number.');
        }

        if ($user->otp != $enteredOtp) {
            // Delete new users if OTP fails
            if (Session::get('is_new_user')) {
                $user->delete();
                Session::forget(['is_new_user', 'sessionMobile', 'sessionExpireAt']);
            }
            return back()->with('warning', 'The entered OTP is incorrect.');
        }

        $now = Carbon::now('Asia/Kolkata');
        $expireAt = Carbon::parse($user->expire_at)->timezone('Asia/Kolkata');

        if ($now->gt($expireAt)) {
            if (Session::get('is_new_user')) {
                $user->delete();
                Session::forget(['is_new_user', 'sessionMobile', 'sessionExpireAt']);
            }
            return back()->with('warning', 'The entered OTP has expired.');
        }

        // ✅ Get guest session ID before login
        $guestSessionId = Session::getId();

        Auth::login($user);
        session(['userType' => $user->user_type]);

        $user->update([
            'expire_at' => $now,
            'status'    => 1,
            'otp'       => null,
        ]);

        Session::forget(['sessionOtp', 'sessionMobile', 'sessionExpireAt', 'is_new_user']);

        return $this->authenticated($request, $user, $guestSessionId);
    }

    protected function authenticated(Request $request, $user, $guestSessionId = null)
    {
        $guestSessionId = $guestSessionId ?? Session::getId();

        // Fetch guest cart items
        $guestCarts = Cart::where('session_id', $guestSessionId)
                        ->whereNull('user_id')
                        ->get();

        $hasGuestCart = false;

        foreach ($guestCarts as $guestCart) {
            $hasGuestCart = true;

            $existing = Cart::where('user_id', $user->id)
                ->where('product_id', $guestCart->product_id)
                ->where('size_id', $guestCart->size_id)
                ->where('color_id', $guestCart->color_id)
                ->first();

            if ($existing) {
                $existing->qty += $guestCart->qty;
                $existing->save();
                $guestCart->delete();
            } else {
                $guestCart->user_id = $user->id;
                $guestCart->save();
            }
        }

        // Check if the user already had cart items (not from guest session)
        $hasUserCart = Cart::where('user_id', $user->id)->exists();

        // ✅ Redirect Logic
        $intendedUrl = session('intended_url', '/');

        // If guest cart merged or existing user cart exists → redirect to checkout
        if ($hasGuestCart || $hasUserCart) {
            return redirect('cart/checkout');
        }

        // If intended URL is set and not /checkout → redirect there
        if ($intendedUrl && !str_contains($intendedUrl, '/checkout')) {
            session()->forget('intended_url');
            return redirect()->to($intendedUrl);
        }

        // Fallback: homepage
        return redirect('/');
    }

    public function login_with_password()
    {
        $data['title'] = 'Login with Password';
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc');
        }])->where('status', 1)->orderBy('order_by', 'asc')->get();

        // Get the previous URL
        $previousUrl = url()->previous();

        // If user came from '/user-login', get the URL before that using the referer chain
        if (str_ends_with($previousUrl, '/user-login')) {
            $previousUrl = url()->previous(1); // Laravel doesn't support multi-level previous() out-of-the-box
            // You may use the referer manually from headers
            $previousUrl = request()->headers->get('referer');
        }

        // Avoid saving login page itself
        if (!str_contains($previousUrl, '/user-login')) {
            session(['intended_url' => $previousUrl]);
        }

        return view('web.signin', $data);
    }

    public function loginpass(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

         // ✅ Get current guest session_id BEFORE login
        $guestSessionId = Session::getId();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->authenticated($request, Auth::user(), $guestSessionId);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }



    public function webregister()
    {
        $data['title'] = 'User-Register';
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories

        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();
        return view('web.register',$data);
    }

    public function user_store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|digits:10|unique:users,mobile',  // Phone validation
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'The first name field is required.',
            'lastname.required' => 'The last name field is required.',
            'phone.required' => 'The mobile number field is required.',
            'phone.digits' => 'The mobile number must be 10 digits.',
            'phone.unique' => 'The mobile is already in use.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'Password and confirmation do not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        // Check if the user is already registered
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return redirect()->route('weblogin')->with('warning', 'This email has already been registered.');
        }

        // Generate OTP
        $otp = $this->generateOtp($request->email);

        // Create new user with UUID and default status = 0
        $user = new User;
        $user->uuid = (string) Str::uuid();  // Generate and store UUID
        $user->first_name = $request->name;
        $user->last_name = $request->lastname;
        $user->mobile = $request->phone;  // Use 'phone' instead of 'mobile'
        $user->email = $request->email;
        $user->otp = $otp;
        $user->password = bcrypt($request->password);  // Hash the password
        $user->status = 0;  // Set default status to 0
        $user->expire_at = Carbon::now('Asia/Kolkata')->addMinutes(3);
        $user->save();

        // Store expiration time in session
        Session::put('sessionExpireAt', $user->expire_at);

        // Send OTP email
        $mail_details = [
            'subject' => 'User Email Verification OTP',
            'body' => 'Your OTP for registration is: ' . $otp,
        ];
        Mail::to($request->email)->send(new sendEmail($mail_details));

        return redirect()->route('otp_verification')->with('success', 'OTP sent successfully!');
    }


    // OTP generation method
    public function generateOtp($email)
    {
        $otp = rand(100000, 999999);
        Session::put('sessionOtp', $otp);
        Session::put('sessionEmail', $email);
        return $otp;
    }
    public function resendOtp(Request $request)
    {
        $recipient = $request->input('recipient');
        $type = $request->input('type'); // 'email' or 'mobile'

        // Validate recipient (email or mobile)
        if ($type == 'email') {
            $user = User::where('email', $recipient)->first();
        } elseif ($type == 'mobile') {
            $user = User::where('mobile', $recipient)->first();
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid recipient type']);
        }

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        // Generate new OTP and expiration time
        $otp = rand(100000, 999999); // Random 6-digit OTP
        $expire_at = Carbon::now('Asia/Kolkata')->addMinutes(3);

        // Save the OTP and its expiration time
        $user->otp = $otp;
        $user->expire_at = $expire_at;
        $user->save();

        // Send the OTP via email or mobile
        if ($type == 'email') {
            $mail_details = [
                'subject' => 'Your OTP for Verification',
                'body' => 'Your OTP for verification is: ' . $otp,
            ];
            Mail::to($recipient)->send(new SendEmail($mail_details));
        } else {
            $this->sendOtpNimbus($recipient, $otp);
        }

        // Update session with new expiration time
        Session::put('sessionExpireAt', $expire_at);

        return response()->json([
            'status' => true,
            'message' => 'OTP resent successfully',
            'new_expire_at' => $expire_at
        ]);
    }

    /**
     * Function to send OTP using Nimbus API
     */
    /**
     * Function to send OTP using Nimbus API
     */
    private function sendOtpNimbus($mobile, $otp)
    {
        $api_url = 'https://nimbusit.biz/Api/smsapi/JsonPost';

        // Prefix the mobile number with "+91" for Indian numbers
        $formatted_mobile =  $mobile;

        // Prepare the message using the approved template
        $message = "Dear Customer, Your OTP is {$otp} to login to sutramglobal.com. Do not share this OTP with anyone. Thank You!";

        // API payload
        $data = [
            "FORMAT" => "1",
            "USERNAME" => "devicediskapibiz", // Replace with your Nimbus username
            "PASSWORD" => "vwmc8457VW",       // Replace with your Nimbus password
            "SENDERID" => "SUTRAM",           // Replace with your sender ID
            "TEXTTYPE" => "TEXT",
            "SMSTEXT" => $message,
            "TemplateID" => "1707173331796914461", // Replace with your approved DLT template ID
            "EntityID" => "1701173276915726387",   // Replace with your Entity ID
            "MOBLIST" => [$formatted_mobile],      // Add the formatted mobile number
        ];

        // Convert payload to JSON
        $json_data = json_encode($data);

        // Send SMS using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . urlencode($json_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        // Log the response for debugging purposes
        Log::info('Nimbus SMS API Response: ' . $response);
    }


    public function showForgotPasswordForm()
    {
        $data['title'] = 'Forgot Password'; // Adjust the title as needed
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories

        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();
        return view('web.forgot-password', $data);
    }

    // Handle the forgot password request
    public function forgotPassword(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Send the password reset link
        $response = Password::sendResetLink($request->only('email'));

        return $response === Password::RESET_LINK_SENT
            ? back()->with('success', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('weblogin')->with('success', 'Logout successfully!');
    }

}
