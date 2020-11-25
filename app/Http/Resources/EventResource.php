<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\ImageableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'id'            => $this->id,
            'encrypt_id'    => encrypt($this->id),

            'image'         => ($this->image) ? (new ImageableResource($this->image))->foo('events') : NULL,
            'user'          => ($this->user) ? new UserResource($this->user) : NULL,
            
            'name'          => $this->name,
            'venue'         => $this->venue,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,

            'dateForHumans' => $this->created_at->diffForHumans(),
            'timestamp'     => $this->created_at,
            'status'        => (boolean)$this->status,
        ];
    }
}
