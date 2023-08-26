<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebModels\Offender;
use App\Models\OffenderTrue;
use App\Models\OffenderFalse;
use App\Models\OffenderNotsure;

class OffendersController extends Controller
{
    public function browse()
    {
        $offenders = Offender::all();
        return view('offenders.index', compact('offenders'));
    }

    public function newOffender()
    {
        return view('offenders.new');
    }

    public function create(Request $request)
    {
        $data = request()->validate([
           'offender_name' => 'required',
           'title' => 'required',
           'offence' => 'required',
           'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           'content' => 'required',
       ]);

       $imageName = time().'.'.$request->photo->extension();
       $request->photo->move(public_path('offenders-photo'), $imageName);

       $offender = Offender::create([
           'admin_id' => auth()->user()->id,
           'offender_name' => $request->offender_name,
           'title' => $request->title,
           'photo' => $imageName,
           'offence' => $request->offence,
           'content' => $request->content,
           'source_url' => $request->source_url,
       ]);

       if($offender) {
           return redirect()->route('offenders.browse')->with('message', 'Offender Added Successfully');
       }
    }

    public function edit(Offender $offender) {
        return view('offenders.edit', compact('offender'));
    }

    public function editAction(Request $request, Offender $offender)
    {
        if($request->photo) {
            // update photo
            // delete current image from directory
            $imagePath = "offenders-photo/".$offender->photo;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('offenders-photo'), $imageName);
            $offender->update([
                'photo' => $imageName,
            ]);
        }

        $offender->update([
            'offender_name' => $request->offender_name,
            'title' => $request->offender->title,
            'offence' => $request->offence,
            'content' => $request->content,
            'source_url' => $request->source_url,
        ]);

        return redirect()->back()->with('message', 'Offender Edited Successfully');
    }

    public function delete(Offender $offender)
    {
        $offender->delete($offender);
        return redirect()->back()->with('message', 'Offender Deleted Successfully');
    }

    public function viewOffender(Offender $offender)
    {
        $trueVotes = OffenderTrue::where('offence_id', $offender->id)->get();
        $falseVotes = OffenderFalse::where('offence_id', $offender->id)->get();
        $notSureVotes = OffenderNotsure::where('offence_id', $offender->id)->get();
        return view('offenders.view', compact('offender', 'trueVotes', 'falseVotes', 'notSureVotes'));
    }
}