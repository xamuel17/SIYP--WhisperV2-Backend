<?php

namespace App\Http\Resources;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianDistressMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // return parent::toArray($request);

        return [
           'id'=> $this->id,
          'guardian_id' => $this->guardian_id,
            'guardian_user'=>User::where('id',$this->guardian_id )->value('username'),
            'ward_id' => $this->ward_id,
            'ward_user'=> User::where('id',$this->ward_id )->value('username'),
            'title' => $this->title,
            'content'=>$this->content,
            'photo'=>$this->photo,
            'video'=>$this->video,
            'audio'=>$this->audio,
            'longitude'=>$this->longitude,
            'latitude'=>$this->latitude,
            'time_of_message'=>$this->time_of_message,
            'phone_number'=>$this->phone_number,
            'priority'=>$this->priority,
            'status'=>$this->status,
           'created_at' => $this->created_at,
             'updated_at' => $this->updated_at,
        ];


    }
}
