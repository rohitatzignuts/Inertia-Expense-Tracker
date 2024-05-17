<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\User;
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
        $transactions = Transaction::orderBy('id', 'desc')
            ->where('user_id', Auth::user()->id)
            ->whereMonth('created_at', '=', $selectedMonth)
            ->paginate(5)
            ->withQueryString();
        return Inertia::render('Transactions/index', [
            'transactions' => $transactions,
        ]);
    }

    public function userTransactions($id, Request $request)
    {

        // Check if a user ID is provided via the request and fetch the user or use the provided ID
        $selectedUser = $request->input('user') ? User::findOrFail($request->input('user')) : null;

        // Determine the user to use
        $user = $selectedUser ? $selectedUser : User::findOrFail($id);

        // Determine the selected month, defaulting to the current month if not provided
        $selectedMonth = $request->input('month') ? date('m', strtotime($request->input('month'))) : date('m');

        // Get the user's transactions for the selected month and paginate the results
        $userTransactions = $user->transactions()->whereMonth('created_at', '=', $selectedMonth)->paginate(5)->withQueryString();

        // Render the view with the transactions and user name
        return Inertia::render('Transactions/UserTransactions', [
            'userTransactions' => $userTransactions,
            'userName' => $user->name,
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
