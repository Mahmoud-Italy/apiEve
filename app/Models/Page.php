<?php

namespace App\Models;

use DB;
use App\Models\Imageable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    public function image() {
        return $this->morphOne(Imageable::class, 'imageable')->select('url');
    }

    // fetchData
    public static function fetchData($value='')
    {
        // this way will fire up speed of the query
        $obj = self::query();

          
          // search for multiple columns..
          if(isset($value['search']) && $value['search']) {
            $obj->where(function($q) use ($value) {
                $q->where('title', 'like', '%'.$value['search'].'%');
                $q->orWhere('body', 'like', '%'.$value['search'].'%');
                $q->orWhere('id', $value['search']);
              });
          }

          // status
          if(isset($value['status']) && $value['status']) {
              if($value['status'] == 'active')
                  $obj->where(['status' => true, 'trash' => false]);
              else if ($value['status'] == 'inactive')
                  $obj->where(['status' => false, 'trash' => false]);
              else if ($value['status'] == 'trash')
                  $obj->where('trash', true);
          }

          // order By..
          if(isset($value['order']) && $value['order']) {
            if($value['order_by'] == 'title')
              $obj->orderBy('title', $value['order']);
            else if ($value['order_by'] == 'created_at')
              $obj->orderBy('created_at', $value['order']);
            else
              $obj->orderBy('id', $value['order']);
          } else {
            $obj->orderBy('sort', 'DESC');
          }

          // feel free to add any query filter as much as you want...

        $obj = $obj->paginate($value['paginate'] ?? 10);
        return $obj;
    }



    // createOrUpdate
    public static function createOrUpdate($id, $value)
    {
        try {
            
            DB::beginTransaction();

              // Row
              $row                = (isset($id)) ? self::findOrFail($id) : new self;
              $row->slug          = strtolower($value['slug']) ?? NULL;
              $row->title         = $value['title'] ?? NULL;
              $row->body          = $value['body'] ?? NULL;
              $row->sort          = (int)$value['sort'] ?? 0;
              $row->status        = (boolean)$value['status'] ?? false;
              $row->save();

              // Image
              if(isset($value['base64Image'])) {
                if($value['base64Image']) {
                  $image = Imageable::uploadImage($value['base64Image']);
                  $row->image()->delete();
                  $row->image()->create(['url' => $image]);
                }
              }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }
}
