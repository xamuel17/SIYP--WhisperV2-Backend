<?php

namespace App\Http\Resources;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
      return [
        'id' => $this->id,
        'admin_id' => $this->admin_id,
        'user_id' => $this->user_id,
        'title'=>$this->title,
        'content'=>$this->content,
        'status'=>$this->status,
        'link'=>DB::table('mobile_pages')->where('id', $this->mobile_id)->value('router_link'),
       'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
    }
}
