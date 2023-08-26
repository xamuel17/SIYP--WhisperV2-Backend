<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OffenderNotsureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
     

        return [
            'id' => $this->id,
           'offence_id' => $this->offence_id,
             'username'=>User::where('id',$this->user_id )->value('username'),
             'username_photo'=>User::where('id',$this->user_id )->value('profile_pic'),
             'firstname'=> User::where('id',$this->user_id )->value('firstname'),
             'lastname'=>User::where('id',$this->user_id )->value('lastname'),
           'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
