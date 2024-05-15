<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;


class TransactionController extends Controller
{
    public function index()  {
        return Inertia::render('Transactions');
    }
}
