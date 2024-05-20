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
     * Display a listing of the users
     * @method GET
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /users
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return Inertia::render('Users/index', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created user in storage.
     * @method POST
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /users/store
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'password' => 'string|required|min:8',
            'email' => 'email|required',
            'is_admin' => 'required',
        ]);
        // add user type
        $isAdmin = $request->input('is_admin') === 'admin' ? 1 : 0;
        User::create($request->only('name', 'email', 'password') + ['is_admin' => $isAdmin]);
    }

    /**
     * Display the specified user data for editing.
     * @method GET
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /users/{user}/edit
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request,userId $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    /**
     * Update the selected user
     * @method PUT
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /users/{user}/update
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request,userId $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required',
        ]);
        // update user type
        $isAdmin = $request->input('is_admin') === 'admin' ? 1 : 0;
        $user->update($request->only('name', 'email') + ['is_admin' => $isAdmin]);
    }

    /**
     * Remove the specified user from storage.
     * @method DELETE
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /users/{user}
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @param \Illuminate\Http\Request $request,userId $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id)->delete();
    }

    /**
     * Get all the users from the database ecxept admins
     * @method GET
     * @author Rohit Vispute (Zignuts Technolab)
     * @route /users/all
     * @authentication : Requires user authentication
     * @middleware auth:sanctum
     * @return \Illuminate\Http\Response
     */
    public function getUsernames()
    {
        $user = User::where('is_admin', '!=', '1')->get();
        return $user;
    }
}
