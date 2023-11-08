<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::latest()->get(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect(route('users.index'));
    }

    public function updateSuspend(User $user): RedirectResponse
    {
        $is_suspended = $user->suspend_flag == 1;
        $user->suspend_flag = $is_suspended ? 0 : 1;

        // Delete Existed Token
        if (!$is_suspended && $user->tokens->count() >= 1) {
            $user->tokens()->delete();
        }
        $user->save();

        return redirect(route('users.index'));
    }
}
