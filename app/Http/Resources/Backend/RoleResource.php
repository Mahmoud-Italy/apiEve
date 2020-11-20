<?php

namespace App\Http\Resources\Backend;

use App\Models\User;
use App\Models\Permission;
use App\Http\Resources\Backend\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'id'              => $this->id,
            'encrypt_id'      => encrypt($this->id),
            'user'            => ($this->user) ? new UserResource($this->user) : NULL,

            'name'            => $this->name,
            'authority'       => $this->authority,
            'permissions_ids' => Permission::getPermissionsIds($this->id),

            'users_no'        => User::role($this->name)->count(),

            // Dates
            'dateForHumans'   => $this->created_at->diffForHumans(),
            'created_at'      => ($this->created_at == $this->updated_at) 
                                    ? 'Created <br/>'. $this->created_at->diffForHumans()
                                    : NULL,
            'updated_at'      => ($this->created_at != $this->updated_at) 
                                    ? 'Updated <br/>'. $this->updated_at->diffForHumans()
                                    : NULL,
            'deleted_at'      => ($this->updated_at && $this->trash) 
                                    ? 'Deleted <br/>'. $this->updated_at->diffForHumans()
                                    : NULL,
            'timestamp'       => $this->created_at,

            // Status & Visibility
            'status'          => (boolean)$this->status,
            'trash'           => (boolean)$this->trash,
            'loading'         => false
        ];
    }
}
