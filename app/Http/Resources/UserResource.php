<?php

namespace App\Http\Resources;

use App\Http\Resources\ImageableResource;
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
        return [
            'id'            => $this->id,
            'encrypt_id'    => encrypt($this->id),
            
            'image'         => ($this->image) ? (new ImageableResource($this->image))->foo('users') : NULL,
            
            'name'          => $this->name,
            'email'         => $this->email,

            'dateForHumans' => $this->created_at->diffForHumans(),
            'timestamp'     => $this->created_at,
            'status'        => (boolean)$this->status,
        ];
    }
}
