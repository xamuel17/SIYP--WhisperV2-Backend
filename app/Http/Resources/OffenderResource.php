<?php

namespace App\Http\Resources;
use App\Models\OffenderTrue;
use App\Models\OffenderFalse;
use App\Models\OffenderNotsure;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OffenderResource extends JsonResource
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
           'admin_id' => $this->admin_id,
           'title'=>$this->title,
           'offender_name'=>$this->offender_name,
         'photo'=>'https://app.whispertohumanity.com/blog/public/offenders-photo/'.$this->photo,
           'content'=>$this->content,
            'source_url'=>$this->source_url,
             'true_count'=>OffenderTrue::where('offence_id',$this->id )->get()->count(),
             'false_count'=>OffenderFalse::where('offence_id',$this->id )->get()->count(),
             'notsure_count'=>OffenderNotsure::where('offence_id',$this->id )->get()->count(),

           'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
