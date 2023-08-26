<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\WebRequests\CreateAdminRequest;
use App\Http\Requests\WebRequests\SetupPasswordRequest;
use App\Http\Requests\WebRequests\ChangePasswordRequest;
use App\Models\WebModels\Admin;
use App\Models\WebModels\WebRole;
use App\WebClasses\Util;
use App\Jobs\WebJobs\SendActivationLinkJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('web.role.super_admin')->except('setupPassword', 'createPassword');
    }

    public function browseAdmin()
    {
        $admins = Admin::where('web_role_id', '<>', 1)->get();
        return view('admins.browse', compact('admins'));
    }

    public function newAdmin()
    {
        $webRoles = WebRole::where('name', '<>', 'APP_DEVELOPER')->get();
        return view('admins.new', compact('webRoles'));
    }

    public function createAdmin(CreateAdminRequest $request)
    {
        // generate activation code
        $activationCode = $this->generateUniqueActivationCode();

        $admin = Admin::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'web_role_id' => $request->web_role_id,
            'activation_code' => $activationCode,
            'activation_code_sent_at' => now(),
        ]);
        // send activation mail to admin
        $url = config('app.url').'/admin/setup-password/'.$admin->activation_code;
        SendActivationLinkJob::dispatch($admin,$url);

        return redirect(route('admin.browse'))->with('message', 'Admin Registered Successfully');
    }

    public function setupPassword($activationCode)
    {
        // get admin
        $admin = Admin::where('activation_code', $activationCode)->first();
        if(!$admin) {
            return 'Incorrect Activation Code. Please contact the Admin';
        }
        $email = $admin->email;
        $name = $admin->firstname. ' '. $admin->lastname;
        return view('admins.setup-password', compact('email', 'name'));
    }

    public function createPassword(SetupPasswordRequest $request)
    {
        $admin = Admin::where('email', $request->email)->first();
        if(!$admin) {
            return redirect()->back()->with('error', 'Something Went wrong, Please contact the Admin');
        }
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        // automatically login admin
        // $admin = new Admin();
        // $admin->email = $request->email;
        // $admin->password = $request->password;
        // // Auth::guard('web')->login($admin);
        // Auth::login($admin);

        return redirect()->route('login')->with('message', 'Password Setup Successfully. Login to your account');

    }

    private function generateUniqueActivationCode()
    {
        do {
            $code = Util::generateCode(50);
            $codeExists = Admin::where('activation_code',$code)->first();
        } while($codeExists);
        return $code;
    }

    public function changePassword()
    {
        return view('admins.change-password');
    }

    public function changePasswordPost(ChangePasswordRequest $request)
    {
        // check if current password is correct
        $adminUser = auth()->user();
        $admin = Admin::where('id',$adminUser->id)->first();
        $adminPassword = $admin->password;
        $newPassword = Hash::make($request->new_password);

        if (!Hash::check($request->current_password, $adminPassword)) {
            return redirect()->back()->with('error', 'Incorrect Password');
        }

        // update password
        $updateAdmin = $admin->update([
            'password' => $newPassword
        ]);

        if($updateAdmin){
            return redirect()->back()->with('message', 'Password updated successfully');
        }
    }

    public function profile()
    {
        $admin = auth()->user();
        return view('admins.profile', compact('admin'));
    }

    public function saveProfile(Request $request)
    {
        $admin = auth()->user();
        $res = $admin->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
        ]);
        if($res) {
            return redirect()->back()->with('message', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile');
        }
    }
}
