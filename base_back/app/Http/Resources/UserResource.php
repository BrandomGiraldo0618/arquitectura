<?php

namespace App\Http\Resources;

use App\Library\General;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'image' => $this->getImage(),
            'active' => $this->active,
            'active_name' => General::active($this->active),
            'language' => $this->language,
            'last_date_connection' => $this->last_date_connection,
            'role' => $this->role,
            'address' => $this->address,
            'cellphone' => $this->cellphone
        ];
    }
}
