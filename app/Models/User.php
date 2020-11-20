<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Imageable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function image() {
        return $this->morphOne(Imageable::class, 'imageable')->select('image_url');
    }

    public static function fetchData($value='')
    {
        // this way will fire up speed of the query
        $obj = self::query();

            if(isset($value['search']) && $value['search']) {
                $obj->where(function($q){
                    $q->where('name', 'like','%'.$value['search'].'%');
                    $q->orWhere('email', 'like', '%'.$value['search'].'%');
                    $q->orWhere('id', $value['search']);
                });
            }

            // status
          if(isset($value['status']) && $value['status']) {
              if($value['status'] == 'seen')
                  $obj->where(['status' => true, 'trash' => false]);
              else if ($value['status'] == 'unseen')
                  $obj->where(['status' => false, 'trash' => false]);
              else if ($value['status'] == 'trash')
                  $obj->where('trash', true);
          }
            
            if(isset($value['order']) && $value['order']) {
                $obj->orderBy('id', $value['order']);
            } else {
                $obj->orderBy('id', 'DESC');
            }

        $obj = $obj->paginate($value['paginate'] ?? 10);
        return $obj;
    }


    public static function createOrUpdate($id, $value)
    {
        try {
            
            DB::beginTransaction();

              // Row
              $row                 = (isset($id)) ? self::findOrFail($id) : new self;
              $row->name           = $value['name'] ?? NULL;
              $row->email          = $value['email'] ?? NULL;

              if(isset($value['password']) && $value['password']) {
                  $plainPassword   = $value['password'];
                  $row->password   = app('hash')->make($plainPassword);
              }

              $row->save();

              $row->assignRole($value['role'] ?? 'root'); // assign admin role

              // role
              if(isset($value['base64Image'])) {
                $row->image()->delete();
                if($value['base64Image']) {
                  if(!Str::contains($value['base64Image'], ['uploads','false'])) {
                    $image = Imageable::uploadImage($value['base64Image']);
                  } else {
                    $image = explode('/', $value['base64Image']);
                    $image = end($image);
                  }
                  $row->image()->create(['url' => $image]);
                }
              }


            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }


    // fetch Period
    public static function fetchPeriod($header, $days)
    {
        $operator = '-';
        $percentage = '0%';
        $arrow = 'ti-arrow-down';

        // get Period Day
        $obj = self::fetchPeriodDay($header, $days);

        // find percentage & arrow
        if($days != 'infinity') {
            $obj2 = self::fetchPeriodDay($header, $days);
            if($obj >= $obj2) { $operator = '+'; $arrow = 'ti-arrow-up'; } 
            else { $operator = '-'; $arrow = 'ti-arrow-down'; }

            $diff = 0;
            if($obj > 0 && $obj2) { $diff = $obj / $obj2 * 100; }
            $percentage = $operator.''.$diff.'%';
        }

        $data = ['total'=>$obj, 'percentage'=>$percentage, 'arrow'=>$arrow];
        return $data;
        
    }

    public static function fetchPeriodDay($header, $days)
    {
        $obj = self::where('role_id', false);

            // Today & else = Yesterday, 28 Days, 90 Days , 180 Days
            if($days == 0) {
                $obj = $obj->whereDate('created_at', Carbon::now());
            } else if ($days != 0 && $days != 'infinity') {
                $obj = $obj->whereDate('created_at', '>=', Carbon::now()->subDay($days));
            } 

        $obj = $obj->count();
        return $obj;
    }
}
