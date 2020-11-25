<?php
namespace App\Repository;

use Helper;
use Carbon\Carbon;
use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Contracts\Cache\Repository;

class Events {

  CONST CACHE_KEY = 'Events';

  public static function getCacheKey($key)
  {
    $key = strtoupper($key);
    return self::CACHE_KEY .".$key";
  }

  // fetch Events
  public static function fetchData($value='events', $skip=0)
  {
    $key      = "getEvents.{$value}";
    $cacheKey = self::getCacheKey($key);
    
    return Cache::remember($cacheKey, Carbon::now()->addHours(6), function() use($skip) {
      return self::toCollection(EventResource::collection(
                    Event::skip($skip)
                         ->take(50)
                         ->get()));
    });
  }

  // find Event
  public static function findRow($id='')
  {
    $key      = "findEvent.{$id}";
    $cacheKey = self::getCacheKey($key);
    
    return Cache::remember($cacheKey, Carbon::now()->addHours(6), function() use($id) {
      return self::toCollection(new EventResource(Event::findOrFail($id)));
    });
  }

  // Convert json to Collection Laravel
  public static function toCollection($rows='')
  {
    $json   = response()->json(['rows'=>$rows]);
    $decode = json_decode(json_encode($json), true);
    return json_decode(json_encode($decode['original']['rows']));
  }

}