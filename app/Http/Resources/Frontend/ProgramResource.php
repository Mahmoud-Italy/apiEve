<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Frontend\SectorResource;

class ProgramResource extends JsonResource
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
            'image'         => ($this->image) ? request()->root() . $this->image->url : NULL,
            'slug'          => $this->slug,
            'title'         => $this->title,
            'body'          => $this->body,
            'has_sectors'   => (boolean)$this->has_sectors,
            'sectors'       => SectorResource::collection($this->sectors),
        ];
    }
}