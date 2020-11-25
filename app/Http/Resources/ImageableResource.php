<?php

namespace App\Http\Resources;

use App\Models\Imageable;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageableResource extends JsonResource
{

    protected $foo;
    public function foo($value){
        $this->foo = $value;
        return $this;
    }

    public function toArray($request)
    {
        return [
            'image_url'     => Imageable::getImagePath($this->foo, $this->image_url),
            'image_alt'     => $this->image_alt ?? NULL,
            'image_title'   => $this->image_title ?? NULL,
        ];
    }
}
