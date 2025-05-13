<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Micropost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MicropostsController extends Controller
{
    /**
     * Display a listing of the microposts.
     */
    public function index()
    {
        $microposts = Micropost::with('user')->latest()->paginate(10);
        return response()->json($microposts);
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

        $micropost = new Micropost([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('microposts', 'public');
            $micropost->image = $path;
        }

        $micropost->save();

        return response()->json($micropost, 201);
    }

    /**
     * Display the specified micropost.
     */
    public function show(Micropost $micropost)
    {
        return response()->json($micropost->load('user'));
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

        $micropost->update([
            'content' => $request->content,
        ]);

        return response()->json($micropost);
    }

    /**
     * Remove the specified micropost from storage.
     */
    public function destroy(Micropost $micropost)
    {
        $this->authorize('delete', $micropost);

        if ($micropost->image) {
            Storage::disk('public')->delete($micropost->image);
        }

        $micropost->delete();

        return response()->json(null, 204);
    }
}
