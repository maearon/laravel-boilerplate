<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelationshipsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Follow a user.
     */
    public function store(Request $request)
    {
        $user = User::findOrFail($request->followed_id);
        Auth::user()->follow($user);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Unfollow a user.
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->followed_id);
        Auth::user()->unfollow($user);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
