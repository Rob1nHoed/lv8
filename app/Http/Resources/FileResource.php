<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource(User::find($this->user_id)),
            'file_name' => $this->file_name,
            'file_key' => $this->file_key,
            'downloads' => $this->downloads,
            'max_downloads' => $this->max_downloads,
            'expires_at' => $this->expires_at,
        ];
    }
}
