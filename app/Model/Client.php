<?php

namespace App\Model;

use App\Services\ClientService;
use Illuminate\Database\Eloquent\Model;
use App\Model\Transaction;

class Client extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Relation for Client transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Another way to access ClientService
     * @return ClientService
     */
    public function service()
    {
        return new ClientService($this);
    }

}
