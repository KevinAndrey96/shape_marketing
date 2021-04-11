<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    /*public function register(Request $request)
    {
        $attr = $request->validate([
                                       'name' => 'required|string|max:255',
                                       'email' => 'required|string|email|unique:users,email',
                                       'password' => 'required|string|min:6|confirmed'
                                   ]);

        $user = User::create([
                                 'name' => $attr['name'],
                                 'password' => bcrypt($attr['password']),
                                 'email' => $attr['email']
                             ]);

        return $this->success([
                                  'token' => $user->createToken('API Token')->plainTextToken
                              ]);
    }*/

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $attr = $request->validate([
                                       'email' => 'required|string|email|',
                                       'password' => 'required|string|min:6'
                                   ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        auth()->user()->tokens()->delete();

        return $this->success([
                                  'token' => auth()->user()->createToken('API Token')->plainTextToken
                              ]);
    }

    /**
     * @return string[]
     */
    public function logout(): array
    {
        auth()->user()->tokens()->delete();

        return [
            'code' => 200,
            'message' => 'Tokens Revoked'
        ];
    }
}