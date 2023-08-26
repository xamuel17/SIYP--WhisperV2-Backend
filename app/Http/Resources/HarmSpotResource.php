<?php

namespace App\Http\Resources;

use App\Models\Countries;
use App\Models\SpotTrue;
use App\Models\SpotFalse;
use Illuminate\Http\Resources\Json\JsonResource;

class HarmSpotResource extends JsonResource
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
        'id' => $this->id,
        'admin_id' => $this->admin_id,
        'country'=>Countries::where('id',$this->country)->value('country_name'),
        'content'=>$this->content,
        'title'=>$this->title,
        'latitude' => $this->latitude,
        'longitude'=>$this->longitude,
        'true_count'=>SpotTrue::where('spot_id',$this->id )->get()->count(),
        'false_count'=>SpotFalse::where('spot_id',$this->id )->get()->count(),
         'location'=>$this->location,
        'status'=>$this->status,
       'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];


    }
}
