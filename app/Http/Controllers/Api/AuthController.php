<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Transformers\UserTransformer;

class AuthController extends Controller
{
    public Function register(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|max:32',
        ]);

        $user = $user->create([
            'name'  => $request->name,
            'email' => $request->email,
            'password'  => bcrypt($request->password),
            'api_token' => bcrypt($request->email),
        ]);

        $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'token' => $user->api_token,
            ])
            ->toArray();

        return response()->json($response, 201);
    }

    public function login(Request $request, User $user)
    {
        $credential = $request->validate([
            'email'     => 'required|string|email|max:255',
            'password'  => 'required|string|min:6|max:32',
        ]);

        if (!auth()->attempt($credential)) {
            return response()->json(['error' => 'Your credential is not match'], 401);
        }

        $user = $user->find(auth()->user()->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'token' => $user->api_token,
            ])
            ->toArray();
    }
}
