<?php

namespace App\Http\Controllers;

use App\Http\Enums\GroupUserStatus;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $posts = Post::query() // SELECT * FROM posts
            ->withCount('reactions') // SELECT COUNT(*) from reactions
            ->with([
                'comments' => function ($query) use ($userId) {
                    $query->withCount('reactions'); // SELECT * FROM comments WHERE post_id IN (1, 2, 3...)
                    // SELECT COUNT(*) from reactions
                },
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId); // SELECT * from reactions WHERE user_id = ?
                }])
            ->latest()
            ->paginate(10);

        $posts = PostResource::collection($posts);
        if ($request->wantsJson()) {
            return $posts;
        }

        $groups = Group::query()
            ->select(['groups.*', 'gu.status', 'gu.role'])
            ->join('group_users AS gu', 'gu.group_id', 'groups.id')
            ->where('gu.user_id', Auth::id())
            ->orderBy('gu.role')
            ->orderBy('name', 'desc')
            ->get();

        return Inertia::render('Home', [
            'posts' => $posts,
            'groups'=> GroupResource::collection($groups)
        ]);
    }
}
