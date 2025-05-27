<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

use function Pest\Laravel\get;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        if ($request->get('for-my')) {
            $user = $request->user();

            $users_ids = $user->friends()->pluck('id')->push($user->id);

            $posts = Post::whereIn('user_id', $users_ids)->latest()->with('user')->get();
        } else {
            $posts = Post::latest()->with('user')->get(); // Trae todas las publicaciones en orden descendente
        }

        return view('dashboard', compact('posts'));
    }

    public function profile(User $user)
    {
        $posts = $user->posts()->latest()->get(); // Trae las publicaciones del usuario

        return view('profile', compact('user', 'posts'));
    }

    public function status(Request $request)
    {
        $requests = $request->user()->pendingTo;
        $sent = $request->user()->pendingFrom;
        $friends = $request->user()->friends();

        return view('status', compact('requests', 'sent', 'friends'));
    }
}
