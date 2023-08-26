<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WebModels\Admin;
use App\Models\DistressMessage;
use Illuminate\Support\Facades\DB;
use App\Models\HarmSpot;
use App\Models\Post;
use App\Models\WebModels\WebRole;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalAdmins = Admin::where('web_role_id', '<>', 1)->count();
        $totalHarmspots = HarmSpot::count();
        $totalPosts = Post::count();
        $stats = [
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalHarmspots' => $totalHarmspots,
            'totalPosts' => $totalPosts,
        ];
        // $distressMessages = DistressMessage::orderBy('created_at','desc')->get();
        $distressMessages = DistressMessage::latest()->limit(3)->get();

         $distressMessages = DB::table('distress_messages as dm')->limit(3)->latest('dm.created_at')
            ->join('users as us', 'dm.user_id', '=', 'us.id')
            ->select('dm.id as distress_message_id', 'dm.time_of_message', 'dm.phone_number', 'dm.priority',
                    'us.firstname', 'us.lastname', 'us.username')->get();

        $distressMessagesCount = DistressMessage::count();

        if(auth()->user()->web_role->rank >= WebRole::SUPER_ADMIN_RANK)  {
            // show all post
            $posts = DB::table('posts as pst')->where('deleted_at','=',NULL)->latest('pst.created_at')
                ->join('admins as adm', 'pst.admin_id', '=', 'adm.id')
                ->select('pst.id as post_id', 'pst.title', 'pst.content',
                        'pst.status', 'adm.id as admin_id', 'adm.firstname', 'adm.lastname')->limit(3)->get();
        } else {
            // show only user posts
            $posts = DB::table('posts as pst')->where('deleted_at','=',NULL)->where('admin_id', auth()->user()->id)
                ->join('admins as adm', 'pst.admin_id', '=', 'adm.id')
                ->select('pst.id as post_id', 'pst.title', 'pst.content',
                        'pst.status', 'adm.id as admin_id', 'adm.firstname', 'adm.lastname')->limit(3)->get();
        }

        $postCount = Post::count();

        return view('dashboard', compact('stats', 'distressMessages', 'distressMessagesCount', 'posts', 'postCount'));
    }
}
