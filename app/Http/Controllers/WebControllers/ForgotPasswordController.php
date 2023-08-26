<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebModels\Admin;
use App\Mail\WebMail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\WebRequests\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    public function sendPasswordResetLink(Request $request)
    {
        // check if email exists
        $admin = Admin::where('email',$request->email)->first();
        if(!$admin) {
            return redirect()->back()->with('error', "Whoops! Something went wrong.We can't find a user with that email address.");
        }

        // generate password reset code and update in database

        $passwordResetCode = $this->generateCode(32).time();
        $admin->update([
            'password_reset_code' => $passwordResetCode,
            'password_reset_code_sent_at' => now()
        ]);

        $url = config('app.url').'/admin/reset-password/'.$passwordResetCode;

        // send mail to admin
        Mail::to($admin->email)->send(new PasswordResetMail($admin,$url));
        return redirect()->back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function resetPassword($code)
    {
        // validate code
        $admin = Admin::where('password_reset_code',$code)->first();
        if(!$admin) {
            $error = 'Invalid Reset Code';
            return view('auth.reset-password', compact('admin', 'error'));
        }
        return view('auth.reset-password', compact('admin'));
    }

    public function resetPasswordAction(ResetPasswordRequest $request)
    {
        $admin = Admin::where('email',$request->email)->where('password_reset_code',$request->password_reset_code)->first();
        if(!$admin) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
        $admin->update([
            'password' => Hash::make($request->password),
            'password_reset_code' => NULL
        ]);
        Auth::login($admin);
        // return redirect()->back()->with('message', 'Password Reset was successful');
        return redirect()->route('dashboard');

    }

    private function generateCode($length = 6, $chars = null)
    {
        $characters = $chars ?? '123456789ABCDEFGHJKLMNPQRTUVWXYZadefghrt';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
