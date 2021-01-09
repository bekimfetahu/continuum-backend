<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
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
            'id'=>$this->id,
            'client'=>$this->client->first_name,
            'amount'=>$this->amount,
            'time'=>$this->created_at->format('d/m/Y H:i:s')
        ];
    }
}
