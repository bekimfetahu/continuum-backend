<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
{
    /**
     * Transform the resource into an jsonResource
     * In this case we need to return all fields but we might need to return only selective fields in some cases
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'avatar'=>$this->avatar?url('/storage/avatars').'/'.$this->avatar:url('/').'/default.jpg',
            'email'=>$this->email,
        ];
    }
}
