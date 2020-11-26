<?php

namespace App\Models;

use DB;
use App\Models\User;
use App\Models\Imageable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function image() {
        return $this->morphOne(Imageable::class, 'imageable');
    }


    // fetch Data
    public static function fetchData($value='', $me = false)
    {
        // this way will fire up speed of the query
        $obj = self::query();

          // grap only my data
          if($me) {
            $obj->where('user_id', auth()->guard('api')->user()->id);
          }

          // search for multiple columns..
          if(isset($value['search']) && $value['search']) {
            $obj->where(function($q) use ($value) {
                $q->where('name', 'like', '%'.$value['search'].'%');
                $q->orWhere('id', $value['search']);
              });
          }

          // feel free to add any query filter as much as you want...

        $obj = $obj->paginate($value['paginate'] ?? 10);
        return $obj;
    }


    // Create Or Update
    public static function createOrUpdate($id, $value='')
    {
        try {
            
            DB::beginTransaction();

              // row
              $row                  = (isset($id)) ? self::findOrFail($id) : new self;
              $row->user_id         = auth()->guard('api')->user()->id;
              $row->name            = $value['name'] ?? NULL;
              $row->venue           = $value['venue'] ?? NULL;
              $row->latitude        = $value['latitude'] ?? NULL;
              $row->longitude       = $value['longitude'] ?? NULL;
              $row->start_date      = $value['start_date'] ?? NULL;
              $row->end_date        = $value['end_date'] ?? NULL;
              $row->status          = (boolean)$value['status'] ?? false;
              $row->save();

                // image
                if(isset($value['image_base64'])) {
                    $row->image()->delete();
                    if($value['image_base64']
                      && !Str::contains($value['image_base64'], [ Imageable::contains() ])) {
                        $image = Imageable::uploadImage($value['image_base64']);
                    } else {
                        $image = explode('/', $value['image_base64']);
                        $image = end($image);
                    }
                    $row->image()->create([
                        'image_url'       => $image ?? NULL,
                        'image_alt'       => $value['image_alt'] ?? NULL,
                        'image_title'     => $value['image_title'] ?? NULL,
                    ]);
                }


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

}
