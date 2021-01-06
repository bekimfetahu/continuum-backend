<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Transaction;

class Client extends Model
{
    public $timestamps = false;
    /**
     * Relation for Client transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
