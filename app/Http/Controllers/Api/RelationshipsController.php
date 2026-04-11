<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyRelationshipRequest;
use App\Http\Requests\StoreRelationshipRequest;
use App\Services\RelationshipService;

class RelationshipsController extends Controller
{
    public function __construct(
        private readonly RelationshipService $relationshipService,
    ) {}

    public function store(StoreRelationshipRequest $request)
    {
        $this->relationshipService->follow($request->user(), (int) $request->validated('followed_id'));

        return response()->json(['follow' => true]);
    }

    public function destroy(DestroyRelationshipRequest $request)
    {
        $this->relationshipService->unfollow($request->user(), (int) $request->validated('followed_id'));

        return response()->json(['unfollow' => true]);
    }
}
