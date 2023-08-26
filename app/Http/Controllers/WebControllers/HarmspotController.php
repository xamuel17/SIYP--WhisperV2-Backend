<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HarmSpot;
use Illuminate\Support\Facades\Auth;
use App\Models\SpotTrue;
use App\Models\SpotFalse;
use App\Models\Countries;

class HarmspotController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $harmspots = HarmSpot::all();
        return view('harmspots.index', compact('harmspots'));
    }

    public function newHarmpot()
    {
        $baseUrl = config('app.url');
        $countries = Countries::all();
        return view('harmspots.new', compact('countries', 'baseUrl'));
    }

    public function createHarmspot(Request $request)
    {
        $harmspot = HarmSpot::create([
            'admin_id' => auth()->user()->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location' => $request->location,
            'title' => $request->title,
            'content' => $request->content,
            'risk_level' => $request->risk_level,
            'status' => $request->status,
            'country' => $request->country
        ]);
        if($harmspot) {
            return redirect()->route('harmspot.index')->with('message', 'Harmspot Created Successfully');
        } else {
            return redirect()->back()->with('error', 'An error occurred. Please try again');
        }
    }

    public function unpublishHarmspot($id)
    {
        $harmspot = HarmSpot::where('id',$id)->first();
        if(!$harmspot) {
            return redirect()->back()->with('error', 'Harmspot Not Found');
        }
        $harmspot->update([
            'status' => HarmSpot::UNPUBLISHED
        ]);
        return redirect()->back()->with('message', 'Harmspot Unpublished Successfully');
    }

    public function publishHarmspot($id)
    {
        $harmspot = HarmSpot::where('id',$id)->first();
        if(!$harmspot) {
            return redirect()->back()->with('error', 'Harmspot Not Found');
        }
        $harmspot->update([
            'status' => HarmSpot::PUBLISHED
        ]);
        return redirect()->back()->with('message', 'Harmspot Published Successfully');
    }

    public function showHarmspot($id)
    {
        $harmspot = HarmSpot::where('id',$id)->first();
        if(!$harmspot) {
            return redirect()->back()->with('error', 'Harmspot Not Found');
        }
        return view('harmspots.edit', compact('harmspot'));
    }

    public function updateHarmspot(Request $request, $id)
    {
        $harmspot = HarmSpot::where('id',$id)->first();
        if(!$harmspot) {
            return redirect()->back()->with('error', 'Harmspot Not Found');
        }

        $harmspot->update([
            'title' => $request->title,
            'content' => $request->content,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'risk_level' => $request->risk_level,
            'status' => $request->status
        ]);
        return redirect()->route('harmspot.index')->with('message', 'Harmspot Edited Successfully');
    }

    public function deleteHarmspot($id)
    {
        $harmspot = HarmSpot::where('id',$id)->first();
        if(!$harmspot) {
            return redirect()->back()->with('error', 'Harmspot Not Found');
        }
        $harmspot->delete();
        return redirect()->back()->with('message', 'Harmspot Delete Successfully');
    }

    public function viewHarmspot($id)
    {
        $harmspot = HarmSpot::where('id',$id)->first();
        if(!$harmspot) {
            return redirect()->back()->with('error', 'Harmspot Not Found');
        }

        // get true Votes
        $trueVotes = SpotTrue::where('spot_id',$harmspot->id)->get();

        // get false Votes
        $falseVotes = SpotFalse::where('spot_id',$harmspot->id)->get();

        return view('harmspots.view', compact('harmspot', 'trueVotes', 'falseVotes'));
    }

    public function getCountryName($countryId){
        $country = Countries::where('id',$countryId)->first();
        return $country->country_name;;
    }
}
