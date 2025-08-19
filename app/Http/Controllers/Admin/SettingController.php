<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{

    public function index()
    {
        $data['title'] = 'Settings';
        $data['action'] = 'Manage';
        $data['setting'] = Setting::find(1);
        return view('admin.settings', $data);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_title' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|regex:/^\+?[0-9]{10,12}$/',
            'address' => 'required|string',
            'email2' => 'nullable|email',
            'phone2' => 'nullable|regex:/^\+?[0-9]{10,12}$/',
            'address2' => 'nullable|string',
            'email3' => 'nullable|email',
            'phone3' => 'nullable|regex:/^\+?[0-9]{10,12}$/',
            'address3' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'twitter' => 'nullable|url',
            'google_plus' => 'nullable|url',
            'pinterest' => 'nullable|url',
            'footer_note' => 'nullable|string|max:120',
        ], [
            'phone.regex' => 'The enter a valid phone number.',
            'phone2.regex' => 'The enter a valid phone number.',
            'phone3.regex' => 'The enter a valid phone number.',
            'phone4.regex' => 'The enter a valid phone number.',
            'email2.email' => 'The enter a valid email address.',
            'email3.email' => 'The enter a valid email address.',
            'email4.email' => 'The enter a valid email address.',
            'footer_note.max' => 'The footer note may not be greater than 120 characters.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $settings = Setting::find(1);
        $settings->site_title = $request->site_title;
        $settings->email = $request->email;
        $settings->phone = $request->phone;
        $settings->address = $request->address;
        $settings->email2 = $request->email2;
        $settings->phone2 = $request->phone2;
        $settings->address2 = $request->address2;
        $settings->email3 = $request->email3;
        $settings->phone3 = $request->phone3;
        $settings->address3 = $request->address3;
        $settings->phone4 = $request->phone4;
        $settings->email4 = $request->email4;
        $settings->address4 = $request->address4;
        $settings->facebook = $request->facebook;
        $settings->instagram = $request->instagram;
        $settings->youtube = $request->youtube;
        $settings->twitter = $request->twitter;
        $settings->google_plus = $request->google_plus;
        $settings->pinterest = $request->pinterest;
        $settings->footer_note = $request->footer_note;
        $settings->save();
        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
        ]);

    }
    public function updateLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header_logo' => 'nullable|mimes:jpeg,jpg,png,webp',
            'footer_logo' => 'nullable|mimes:jpeg,jpg,png,webp',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $settings = Setting::find(1);
        if ($request->hasFile('header_logo')) {
            deleteImageIfExists('settings', $settings->header_logo);
            $image = $request->file('header_logo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/settings'), $imageName);
            $settings->header_logo = $imageName;
        }
        if ($request->hasFile('footer_logo')) {
            deleteImageIfExists('settings', $settings->footer_logo);
            $image = $request->file('footer_logo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/settings'), $imageName);
            $settings->footer_logo = $imageName;
        }
        $settings->save();
        return response()->json([
            'success' => true,
            'message' => 'Logo updated successfully',
        ]);

    }
}
