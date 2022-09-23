<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return the user name, the name of the file from file_id

        return [
            'name' => $this->name,
            'files' => $this->files->pluck('file_name'),
            'downloaded' => $this->downloaded->pluck('file_name'),
            'received' => $this->received->pluck('file_name'),
        ];
    }
}
