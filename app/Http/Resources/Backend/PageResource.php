<?php

namespace App\Http\Resources\Backend;

use App\Http\Resources\Backend\UserResource;
use App\Http\Resources\Backend\ImageableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'image'         => ($this->image) ? (new ImageableResource($this->image))->foo('pages') : NULL,
            'user'          => ($this->user) ? new UserResource($this->user) : NULL,
            'meta'          => ($this->meta) ?? NULL,
            
            'title'         => $this->title,
            'slug'          => $this->slug,
            'body'          => $this->body,

            // Dates
            'dateForHumans' => $this->created_at->diffForHumans(),
            'created_at'    => ($this->created_at == $this->updated_at) 
                                ? 'Created <br/>'. $this->created_at->diffForHumans()
                                : NULL,
            'updated_at'    => ($this->created_at != $this->updated_at) 
                                ? 'Updated <br/>'. $this->updated_at->diffForHumans()
                                : NULL,
            'deleted_at'    => ($this->updated_at && $this->trash) 
                                ? 'Deleted <br/>'. $this->updated_at->diffForHumans()
                                : NULL,
            'timestamp'     => $this->created_at,


            // Status & Visibility
            'status'        => (boolean)$this->status,
            'index'         => (boolean)$this->index,
            'trash'         => (boolean)$this->trash,
            'loading'       => false
        ];
    }
}
