<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Repository\Event as EventRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view_events', ['only' => ['index', 'show', 'export']]);
        // $this->middleware('permission:add_events',  ['only' => ['store']]);
        // $this->middleware('permission:edit_events', 
        //                         ['only' => ['update', 'active', 'inactive', 'trash', 'restore']]);
        // $this->middleware('permission:delete_events', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = EventResource::collection(Event::fetchData(request()->all()));
        return response()->json([
            'rows'        => $rows,
            'paginate'    => $this->paginate($rows)
        ], 200);
    }

    public function me()
    {
        $rows = EventResource::collection(Event::fetchData(request()->all(), true));
        return response()->json([
            'rows'        => $rows,
            'paginate'    => $this->paginate($rows)
        ], 200);
    }

    public function store(EventStoreRequest $request)
    {
        $row = Event::createOrUpdate(NULL, $request->all());
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry, ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new EventResource(Event::findOrFail(decrypt($id)));
        return response()->json(['row' => $row], 200);
    }

    public function update(EventUpdateRequest $request, $id)
    {
        $row = Event::createOrUpdate(decrypt($id), $request->all());
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry, ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $row = Event::query();

            if(strpos($id, ',') !== false) {
                foreach(explode(',',$id) as $sid) {
                    $ids[] = $sid;
                }
                $row->whereIN('id', $ids);
            } else {
                $row->where('id', $id);
            }   
            $row->delete();

            return response()->json(['message' => ''], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to delete entry, '. $e->getMessage()], 500);
        }
    }

    public function active($id)
    {
        try {
            $row = Event::query();

            if(strpos($id, ',') !== false) {
                foreach(explode(',',$id) as $sid) {
                    $ids[] = $sid;
                }
                $row->whereIN('id', $ids);
            } else {
                $row->where('id', $id);
            }   
            $row->update(['status' => true]);

            return response()->json(['message' => ''], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function inactive($id)
    {
        try {
            $row = Event::query();

            if(strpos($id, ',') !== false) {
                foreach(explode(',',$id) as $sid) {
                    $ids[] = $sid;
                }
                $row->whereIN('id', $ids);
            } else {
                $row->where('id', $id);
            }   
            $row->update(['status' => false]);

            return response()->json(['message' => ''], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
