<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //

    public function showLoginForm(Request $request)
    {
        $data['title'] = 'Login';
        return view('web.login',$data);
    }
}
