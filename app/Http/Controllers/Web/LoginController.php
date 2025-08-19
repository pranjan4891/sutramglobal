<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Mail\sendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login()
    {
        $data['title'] = 'Login';
        return view('web.login',$data);
    }

    public function register()
    {
        $data['title'] = 'Register';
        return view('web.register',$data);
    }
    public function otp_verification(Request $request)
    {
        $data['title'] = 'Otp Verification';
        return view('web.otp_verification',$data);
    }

    public function user_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
        ], [
            'user_name.required' => 'The user name field is required.',
            'user_name.unique' => 'This user name is already in use.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }
        $d = explode('@', $request->email);
        $userType = $d[1];
        $matchingCompanies = Company::where('status', 1)->where('typo', $userType)->get();
        if ($matchingCompanies->isEmpty()) {
            return redirect()->route('corporate_buyer.register')->with('warning', 'This email address is not authorized.');
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return redirect()->route('corporate_buyer.login')->with('warning', 'This email has already been registered.');
        }
        $otp = $this->generateOtp($request->email);
        $user = new User;
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->user_type = $userType;
        $user->otp = $otp;
        $user->expire_at = Carbon::now('Asia/Kolkata')->addMinutes(3);
        $user->save();
        Session::put('sessionExpireAt', $user->expire_at);
        $mail_details = [
            'subject' => 'Corporate Buyer Account Registration OTP',
            'body' => 'Your OTP To Registration is: ' . $otp,
        ];
        Mail::to($request->email)->send(new sendEmail($mail_details));
        return redirect()->route('corporate_buyer.otp_verification')->with('success', 'OTP sent successfully!');
    }

    public function resend_otp(Request $request)
    {
        try {
            $otp = $this->generateOtp($request->email);
            $user = User::where('email', $request->email)->first();
            $user->otp = $otp;
            $user->expire_at = Carbon::now('Asia/Kolkata')->addMinutes(3);
            $user->save();
            Session::put('sessionExpireAt', $user->expire_at);

            $mail_details = [
                'subject' => 'Corporate Buyer Resend OTP',
                'body' => 'Your OTP To Registration is: ' . $otp,
            ];
            Mail::to($request->email)->send(new sendEmail($mail_details));

            return response()->json(['status' => true, 'message' => 'OTP resent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to resend OTP. Please try again.']);
        }
    }


    public function generateOtp($email)
    {
        $otp = rand(100000, 999999);
        Session::put('sessionOtp', $otp);
        Session::put('sessionEmail', $email);
        return $otp;
    }

    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'digit1' => 'required|numeric',
            'digit2' => 'required|numeric',
            'digit3' => 'required|numeric',
            'digit4' => 'required|numeric',
            'digit5' => 'required|numeric',
            'digit6' => 'required|numeric',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->with('warning', 'Please enter OTP.');
        }

        $enteredOtp = $request->digit1 . $request->digit2 . $request->digit3 . $request->digit4 . $request->digit5 . $request->digit6;
        $user = User::where(['email' => $request->email])->first();

        if (!$user) {
            return redirect()->route('corporate_buyer.register')->with('warning', 'Please register first.');
        }
        if ($user->otp != $enteredOtp) {
            return back()->with('warning', 'The entered OTP is incorrect.');
        }
        $now = Carbon::now('Asia/Kolkata');
        $expire_at_otp = Session::get('sessionExpireAt');
        $expireAt = Carbon::createFromFormat('Y-m-d H:i:s', $expire_at_otp, 'UTC')->setTimezone('Asia/Kolkata');

        // Compare the current time with the expiration time
        if ($now->isAfter($expireAt)) {
            return back()->with('warning', 'The entered OTP is expired.');
        }
        Auth::login($user);
        session(['userType' => $user->user_type]);
        $user->update(['expire_at' => $now]);
        Session::forget('sessionOtp');
        Session::forget('sessionEmail');
        Session::forget('sessionExpireAt');
        return redirect()->route('home')->with('success', 'Welcome! Login Successfully');
    }

    public function loginCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'The email address is not registered.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('corporate_buyer.login')->with('warning', 'Please register first.');
        }

        $matchingCompanies = Company::where('status', 1)
            ->where('typo', $user->user_type)
            ->get();

        if ($matchingCompanies->isEmpty()) {
            return redirect()->route('corporate_buyer.login')->with('warning', 'This email address is not authorized.');
        }

        $otp = $this->generateOtp($request->email);
        $user->otp = $otp;
        $user->expire_at = Carbon::now('Asia/Kolkata')->addMinutes(3);
        $user->save();
        Session::put('sessionExpireAt', Carbon::now('Asia/Kolkata')->addMinutes(3));
        $mail_details = [
            'subject' => 'Corporate Buyer Account Login OTP',
            'body' => 'Your OTP To Login is: ' . $otp,
        ];
        Mail::to($request->email)->send(new sendEmail($mail_details));
        return redirect()->route('corporate_buyer.otp_verification')->with('success', 'OTP sent successfully!');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('corporate_buyer.login')->with('success', 'Logout successfully!');
    }

}
