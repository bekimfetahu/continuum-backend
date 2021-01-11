<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionCollection;
use App\Http\Requests\CreateTransactionRequest;
use App\Model\Transaction;
use App\Model\Client;
use App\Services\TransactionService;
use Illuminate\Http\Request;

/**
 * To manage Client transactions
 *
 * I have have left edit transaction
 * Anyway Transaction should never be deleted or modified in real scenarios but canceled
 * but this is for demo purpose
 *
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    protected $transactionService = null;

    /**
     * TransactionController constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Return a paginated list of transactions, json resource.
     *
     * @param Request $request
     * @return TransactionCollection
     */
    public function index(Request $request)
    {
        $transactions = Transaction::orderBy('id', 'desc')->paginate(10);

        return new TransactionCollection($transactions);
    }

    /**
     * Store a newly created transaction.
     *
     * @param CreateTransactionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTransactionRequest $request)
    {
        $client = Client::findOrFail($request->client_id);

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
     * @param Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        return response()->json($this->transactionService->delete($transaction));
    }
}
