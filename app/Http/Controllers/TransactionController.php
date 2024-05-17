<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month') ? date('m', strtotime($request->input('month'))) : date('m');
        $transactions = Transaction::orderBy('id', 'desc')->whereMonth('created_at', '=', $selectedMonth)->paginate(5)->withQueryString();
        return Inertia::render('Transactions/index', [
            'transactions' => $transactions,
        ]);
    }

    public function store(Request $request)
    {
        Transaction::create(
            $request->validate([
                'description' => 'string|required',
                'user_id' => 'required',
                'amount' => 'integer|required',
                'type' => 'required|in:income,expence,transfer',
                'category' => 'nullable|string',
            ]),
        );
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction, 200);
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(
            $request->validate([
                'description' => 'string|required',
                'user_id' => 'required',
                'amount' => 'integer|required',
                'type' => 'required|in:income,expence,transfer',
                'category' => 'nullable|string',
            ]),
        );
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id)->delete();
    }
}
