<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionCollection;
use App\Model\Transaction;
use App\Model\Client;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return TransactionCollection
     */
    public function index(Request $request)
    {
//        $transactions = $client->transactions()->paginate(10);
        $transactions = Transaction::paginate(10);

        return new TransactionCollection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = Client::find($request->client_id);

        return response()->json($client->transactionService()->create($request->only(['amount'])));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
