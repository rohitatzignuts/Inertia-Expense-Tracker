<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return Inertia::render('Users/index', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'password' => 'string|required|min:8',
            'email' => 'email|required',
            'is_admin' => 'required',
        ]);
        $isAdmin = $request->input('is_admin') === 'admin' ? 1 : 0;
        User::create($request->only('name', 'email', 'password') + ['is_admin' => $isAdmin]);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required',
        ]);
        $isAdmin = $request->input('is_admin') === 'admin' ? 1 : 0;
        $user->update($request->only('name', 'email') + ['is_admin' => $isAdmin]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id)->delete();
    }
}
