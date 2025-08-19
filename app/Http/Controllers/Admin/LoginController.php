<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function loginCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required', 'email',
                Rule::exists('admins', 'email'),
            ],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            session()->flash('error', 'Admin not found.');
            return back();
        }

        if (!Hash::check($request->password, $admin->password)) {
            session()->flash('error', 'Invalid password.');
            return back();
        }

        Auth::guard('admin')->login($admin);
        return redirect()->route('admin.dashboard')->with('success', 'Login successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        session()->flash('success', 'Logout successfully.');
        return redirect()->route('admin.login');
    }
}
