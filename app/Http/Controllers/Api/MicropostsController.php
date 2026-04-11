<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MicropostResource;
use App\Models\Micropost;
use App\Services\CacheService;
use App\Services\MicropostService;
use Illuminate\Http\Request;

class MicropostsController extends Controller
{
    public function __construct(
        private readonly MicropostService $micropostService,
        private readonly CacheService $cacheService,
    ) {}

    /**
     * Display a listing of the microposts.
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 10), 50);
        $page = (int) $request->get('page', 1);
        $microposts = $this->cacheService->rememberMicropostsIndex($perPage, $page);

        return MicropostResource::collection($microposts);
    }

    /**
     * Store a newly created micropost in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140',
            'image' => 'nullable|image|max:5120', // 5MB max
        ]);

        $micropost = $this->micropostService->createForUser(
            $request->user()->id,
            $request->string('content')->toString(),
            $request->file('image'),
        );

        $micropost->load('user:id,name,email');

        return (new MicropostResource($micropost))->response()->setStatusCode(201);
    }

    /**
     * Display the specified micropost.
     */
    public function show(Micropost $micropost)
    {
        $model = $this->cacheService->rememberMicropostShow($micropost->id, function () use ($micropost) {
            $micropost->loadMissing('user:id,name,email');

            return $micropost;
        });

        return new MicropostResource($model);
    }

    /**
     * Update the specified micropost in storage.
     */
    public function update(Request $request, Micropost $micropost)
    {
        $this->authorize('update', $micropost);

        $this->validate($request, [
            'content' => 'required|max:140',
        ]);

        $this->micropostService->updateContent($micropost, $request->string('content')->toString());

        $micropost->load('user:id,name,email');

        return new MicropostResource($micropost);
    }

    /**
     * Remove the specified micropost from storage.
     */
    public function destroy(Micropost $micropost)
    {
        $this->authorize('delete', $micropost);
        $this->micropostService->delete($micropost);

        return response()->json(null, 204);
    }
}
