<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RelationshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelationshipsController extends Controller
{
    public function __construct(
        private readonly RelationshipService $relationshipService,
    ) {
    /**
     * Create a new controller instance.
     */
        $this->middleware('auth');
    }

    /**
     * Follow a user.
     */
    public function store(Request $request)
    {
        $this->relationshipService->follow(Auth::user(), (int) $request->input('followed_id'));

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
        $this->relationshipService->unfollow(Auth::user(), (int) $request->input('followed_id'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
