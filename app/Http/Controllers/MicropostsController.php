<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Micropost;
use App\Services\MicropostService;
use Illuminate\Http\Request;

class MicropostsController extends Controller
{
    public function __construct(
        private readonly MicropostService $micropostService,
    ) {
    /**
     * Create a new controller instance.
     */
        $this->middleware('auth');
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

        $this->micropostService->createForUser(
            $request->user()->id,
            $request->string('content')->toString(),
            $request->file('image'),
        );

        return redirect()->route('root')
            ->with('success', 'Micropost created!');
    }

    /**
     * Remove the specified micropost from storage.
     */
    public function destroy(Micropost $micropost)
    {
        $this->authorize('delete', $micropost);
        $this->micropostService->delete($micropost);

        return back()->with('success', 'Micropost deleted!');
    }
}
