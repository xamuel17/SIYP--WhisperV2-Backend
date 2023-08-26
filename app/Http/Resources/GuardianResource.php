<?php

namespace App\Http\Resources;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

return[


        'id'=> $this->id,
        'ward_id'=> $this->ward_id,
        'ward_username'=> $this->ward_username,
        'ward_img'=>User::where('id',$this->ward_id )->value('profile_pic'),

        'guardian_id'=> $this->guardian_id,
        'guardian_username'=> $this->guardian_username,
		'guardian_img'=>User::where('id',$this->guardian_id )->value('profile_pic'),
        'status'=> $this->status,
        'created_at'=> $this->created_at,
        'updated_at'=> $this->updated_at,
];

    }
}
