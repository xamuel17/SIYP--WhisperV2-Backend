<?php

namespace App\Http\Controllers;

use App\Http\Resources\VolunteerResource;
use App\Models\Volunteer;
use App\Models\VoluteerAppointment;
use App\Models\VoluteerAvailableDays;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createVolunteer(Request $request)
    {
        $rules = array(
            'email' => 'required|email|unique:volunteers',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'Email address for a volunteer has already been registered.';
            $response['responseCode'] = -1001;
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }else{
           $volunteer= Volunteer::create([
                'user_id' => auth()->user()->id,
                'role' => $request->role,
                'description'=> $request->description,
                'email' => $request->email,
                'phone' =>$request->phone
            ]);
            if(isset($volunteer)){
                $response['responseMessage'] = 'Your application to volunteer as a '.$request->role.' for the SYIP app has been accepted';
                $response['responseCode'] = 200;
                return response()->json($response, 200);
            }else{
                $response['responseMessage'] = 'volunteer application failed';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }


        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewVolunteers()
    {
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 200;
        $response['data'] = VolunteerResource::collection(Volunteer::where('status', 'active')->get());
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadPhoto(Request $request)
    {
        $input = $request->all();
        $extension = $request->file('photo')->extension();

        $fileName = time() . "." . $extension;
        $fileName = "volunteer(" . auth()->user()->id . ")" . $fileName;
        $path = $request->file('photo')->move(public_path("/users-images"), $fileName);

        $photoURL = url('/' . $fileName);

        $data = [
            'photo' => $fileName,
        ];

       if(Volunteer::where('user_id', auth()->user()->id)->update($data)){
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 200;
        $response['data'] = $photoURL;
        return response()->json($response, 200);
       }else{
        $response['responseMessage'] = 'failed';
        $response['responseCode'] = -1001;
        return response()->json($response, 200);
       }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function activateDeactivateVolunteer(Request $request)
    {
        if(Volunteer::where('user_id', auth()->user()->id)->update(['status' => $request->status])){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
           }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
           }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function volunteerSetAppointmentDates(Request $request)
    {

        $volunteerId = Volunteer::where('user_id', auth()->user()->id)->first()->id;
        $volunteer = VoluteerAvailableDays::create([
            'volunteer_id' => $volunteerId,
            'user_id'=>auth()->user()->id,
            'day_of_week' => $request->day_of_week,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ]);
        $date = Carbon::parse($request->date);
        $formattedDate = $date->format('F j, Y');
        if(isset( $volunteer )){
            $response['responseMessage'] = 'Appointment  time for ' .$formattedDate. ' has been set.';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
           }else{
            $response['responseMessage'] = 'Ohh Snap! Something went wrong';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
           }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function retrieveVolunteerAvailableTime(Request $request)
    {

        $availableTimeSlots = VoluteerAvailableDays::where('volunteer_id', $request->volunteer_id)
    ->where('day_of_week', $request->day_of_week)
    ->whereTime('start_time', '>=', $request->current_time) // Filter out past time slots
    ->get();

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 200;
        $response['data'] =  $availableTimeSlots;
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function deleteAvailableTime($id)
    {
        $volunteer = VoluteerAvailableDays::where([
            'volunteer_id' => auth()->user()->id,
            'id' => $id
        ])->delete();
        if(isset( $volunteer )){
            $response['responseMessage'] = 'Available time has been removed';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
           }else{
            $response['responseMessage'] = 'Ohh Snap! Something went wrong';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
           }
    }


    public function userMakeBooking(Request $request){
        $volunteer= VoluteerAppointment::create([
            'user_id' => auth()->user()->id,
            'volunteer_id' => $request->volunteer_id,
            'appointment_date'=> $request->appointment_date,
            'appointment_time'=> $request->appointment_time,
            'notes' => $request->notes,
        ]);
        if(isset($volunteer)){
            $response['responseMessage'] = 'Your request for booking is currently pending approval.';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'Appointment booking failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    public function volunteerViewBooking($status){
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 200;
        $response['data'] = VoluteerAppointment::where(['volunteer_id' => auth()->user()->id, 'status' =>$status ])->get();
        return response()->json($response, 200);
    }


    public function updateBookingStatus($status, $id){
        $volunteer = VoluteerAppointment::where(['volunteer_id' => auth()->user()->id, 'id' =>$id ])->update(['status' => $status]);
        if(isset($volunteer)){
            $response['responseMessage'] = 'Appointment booking status has been updated';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'Appointment booking status failed to update';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }



}
