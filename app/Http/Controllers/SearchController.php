<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request, string $search = null)
    {
        if (!$search)
            return redirect(route('dashboard'));

        $users = User::query()
            ->where('name', 'like', "%$search%")
            ->orWhere('username', 'like', "%$search%")
            ->latest()
            ->get();

        $groups = Group::query()
            ->where('name', 'like', "%$search%")
            ->orWhere('about', 'like', "%$search%")
            ->latest()
            ->get();

        $posts = Post::query()
            ->where('body', 'like', "%$search%")
            ->latest()
            ->paginate(20);

        $posts = PostResource::collection($posts);
        if ($request->wantsJson()) {
            return $posts;
        }


        return inertia('Search', [
            'posts' => $posts,
            'search' => $search,
            'users' => UserResource::collection($users),
            'groups' => GroupResource::collection($groups)
        ]);
    }
}
