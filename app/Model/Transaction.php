<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Client;

class Transaction extends Model
{
    /**
     * Relation Transaction belongs to a Client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
