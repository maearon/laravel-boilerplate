<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyRelationshipRequest;
use App\Http\Requests\StoreRelationshipRequest;
use App\Services\RelationshipService;
use Illuminate\Support\Facades\Auth;

class RelationshipsController extends Controller
{
    public function __construct(
        private readonly RelationshipService $relationshipService,
    ) {
        $this->middleware('auth');
    }

    /**
     * Follow a user.
     */
    public function store(StoreRelationshipRequest $request)
    {
        $this->relationshipService->follow(Auth::user(), (int) $request->validated('followed_id'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Unfollow a user.
     */
    public function destroy(DestroyRelationshipRequest $request)
    {
        $this->relationshipService->unfollow(Auth::user(), (int) $request->validated('followed_id'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
