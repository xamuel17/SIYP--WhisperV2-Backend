<?php

namespace App\Http\Resources;

use App\Models\Countries;
use  App\Models\Guardians;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

      //  return parent::toArray($request);

        $photo = $this->profile_pic ? env("APP_URL")."/users-images/" . $this->profile_pic : env("APP_URL")."/users-images/" . "avatar.jpg";

      return [
        'id' => $this->id,
        'firstname' => $this->firstname,
        'lastname' => $this->lastname,
        'username' => $this->username,
        'sex' => $this->sex,
        'dob' => $this->dob,
        'phone' => $this->phone,
        'country' => $this->country,
        'country_id'=>Countries::where('country_name',$this->country)->value('id'),
        'reg_location' => $this->reg_location,
        'email' => $this->email,
        'status' => $this->status,
        'language' => $this->language,
        'imei' => $this->imei,
        'activation_code' => $this->activation_code,
        'profile_pic'=> $photo,
        'followers_count'=>Guardians::where('guardian_id',$this->id )->get()->count(),
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];

    }
}



