<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Category; 
use App\Models\SubCategory;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/home'; // Change to your desired redirect after password reset

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            // Pass categories for the login view if it uses them
            $categories = Category::with(['subcategories' => function ($query) {
                $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
    
            }])
            ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
            ->get();
    
            return redirect()->route('login')->with([
                'status' => trans($response),
                'categories' => $categories, // Pass categories
            ]);
        } else {
            // Reload the reset page with errors and categories
            $categories = Category::with(['subcategories' => function ($query) {
                $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
    
            }])
            ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
            ->get();
    
            return back()->withErrors(['email' => trans($response)])
                ->withInput($request->only('email'))
                ->with('categories', $categories); // Pass categories
        }
    }

}
