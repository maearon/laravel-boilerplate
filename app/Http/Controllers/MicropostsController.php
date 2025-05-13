<?php

namespace App\Http\Controllers;

use App\Models\Micropost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MicropostsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
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

        $micropost = new Micropost([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('microposts', 'public');
            $micropost->image = $path;
        }

        $micropost->save();

        return redirect()->route('root')
            ->with('success', 'Micropost created!');
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

        return back()->with('success', 'Micropost deleted!');
    }
}
