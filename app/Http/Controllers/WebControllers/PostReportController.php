<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;

class PostReportController extends Controller
{
    public function __construct()
    {

    }

    public function browseReports()
    {
        $appUrl = config('app.url');
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('post-reports.browse', compact('reports', 'appUrl'));
    }

    public function deleteReport($id)
    {
        $report = Report::where('id',$id)->first();
        $res = $report->delete();
        if($res == 1) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public function viewReport($id, $userId=null)
    {
        $report = Report::where('id',$id)->first();
        return view('post-reports.view', compact('report', 'userId'));
    }

    public function userPostReport($userId)
    {
        $appUrl = config('app.url');
        $user = User::where('id', $userId)->first();
        $reports = Report::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return view('post-reports.user-reports', compact('reports', 'appUrl', 'user'));
    }
}
