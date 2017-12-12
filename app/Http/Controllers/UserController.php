<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;

class UserController extends Controller
{
    public function users(User $user)
    {
        $users = $user->all();

        return fractal()
            ->collection($users)
            ->transformWith(new UserTransformer)
            ->toArray();
    }

    public function profile(User $user)
    {
        $user = $user->find(auth()->user()->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->includePosts()
            ->toArray();
    }

    public function profileById(User $user, $id)
    {
        $user = $user->find($id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->includePosts()
            ->toArray();
    }
}
