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
    /**
     * Display a listing of the transactions on month filter
     * @method GET
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /transactions
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get month value from the user if not given select the current month
        $selectedMonth = $request->input('month') ? date('m', strtotime($request->input('month'))) : date('m');
        // calculate the total income of a user
        $totalIncome = Transaction::where('type', 'income')
            ->where('user_id', Auth::user()->id)
            ->sum('amount');
        // calculate the total expense of a user
        $totalExpence = Transaction::where('type', 'expence')
            ->where('user_id', Auth::user()->id)
            ->sum('amount');
        // return latest transactions with pagination
        $transactions = Transaction::orderBy('id', 'desc')
            ->where('user_id', Auth::user()->id)
            ->whereMonth('created_at', '=', $selectedMonth)
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('Transactions/index', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpence' => $totalExpence,
        ]);
    }

    /**
     * Display a listing of the transactions by users on month filter
     * @method GET
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /{user}/transactions
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request, userID $id
     * @return \Illuminate\Http\Response
     */
    public function userTransactions($id, Request $request)
    {
        // get the current user by the user select filter or by the link clicked on
        $selectedUser = $request->input('user') ? User::findOrFail($request->input('user')) : null;
        // get the selected user's data
        $user = $selectedUser ? $selectedUser : User::findOrFail($id);
        // get month value from the user if not given select the current month
        $selectedMonth = $request->input('month') ? date('m', strtotime($request->input('month'))) : date('m');
        $userTransactions = $user->transactions()->whereMonth('created_at', '=', $selectedMonth)->paginate(5)->withQueryString();
        // calculate the total income of a user
        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');
        // calculate the total expense of a user
        $totalExpence = $user->transactions()->where('type', 'expence')->sum('amount');

        return Inertia::render('Transactions/UserTransactions', [
            'userTransactions' => $userTransactions,
            'userName' => $user->name,
            'totalIncome' => $totalIncome,
            'totalExpence' => $totalExpence,
        ]);
    }

    /**
     * store/create a new transaction
     * @method POST
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /transactions/store
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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
     * display existing transaction data for editing
     * @method GET
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /transactions/{transaction}/edit
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param transactionID $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction, 200);
    }

    /**
     * update transaction data
     * @method PUT
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /transactions/{transaction}/update
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request, transactionID $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * delete transation data
     * @method DESTROY
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /transactions/{transaction}
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param transactionID $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id)->delete();
    }
}
