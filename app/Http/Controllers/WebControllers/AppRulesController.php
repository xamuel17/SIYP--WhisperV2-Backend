<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Rule;

class AppRulesController extends Controller
{
    public function __construct()
    {

    }

    public function browseRules()
    {
        $appUrl = config('app.url');
        $rules = DB::table('rules')->orderBy('created_at', 'desc')->get();
        return view('rules.browse', compact('rules', 'appUrl'));
    }

    public function newRule()
    {
        return view('rules.new');
    }

    public function createRule(Request $request)
    {
        $data = request()->validate([
           'content' => 'required',
       ]);

       $admin = auth()->user();

       $rule = DB::table('rules')->insert([
           'content' => $data["content"],
           'admin_id' => $admin->id,
           'created_at' => now()
       ]);

       return redirect()->route('rules.browse')->with('message', 'Rule Added Successfully');
    }

    public function viewRule($ruleId)
    {
        $rule = Rule::where('id', $ruleId)->first();
        return view('rules.view', compact('rule'));
    }

    public function saveRule(Request $request, $ruleId)
    {
        $rule = Rule::where('id', $ruleId)->first();
        $rule->update([
            'content' => $request->content,
            'updated_at' => now()
        ]);
        return redirect()->route('rules.browse')->with('message', 'Rule Updated Successfully');
    }

    public function deleteRule($id)
    {
        $rule = Rule::where('id',$id)->first();
        $res = $rule->delete();
        if($res == 1) {
            return 'yes';
        } else {
            return 'no';
        }
    }
}
