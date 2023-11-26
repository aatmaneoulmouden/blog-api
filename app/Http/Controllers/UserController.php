<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Register User
     *
     * @param RegisterUserRequest $request
     * @return void
     */
    public function register(RegisterUserRequest $request)
    {
        // Create new user
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Store token and user data in $userData
        $userData = [
            'token' => $user->createToken('user-' . $user->id)->plainTextToken,
            'data' => new UserResource($user),
        ];

        // Return http response
        return $this->success('user', $userData, 201);
    }

    /**
     * Login user
     *
     * @param LoginUserRequest $request
     * @return void
     */
    public function login(LoginUserRequest $request)
    {
        // Get user creds and get userData according to email
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        // Validate credentials and return http response
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->error('Invalid credentials', 403);
        }

        // Store token and user data in $userData
        $userData = [
            'token' => $user->createToken('user-' . $user->id)->plainTextToken,
            'data' => new UserResource($user),
        ];

        // Return http response
        return $this->success('user', $userData);
    }

    /**
     * Update user profile
     *
     * @return void
     */
    public function updateProfile()
    {
        // 
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout()
    {
        // Revoke the token for the authenticated user and return http response
        if (Auth::check()) {
            Auth::user()->currentAccessToken()->delete();

            return $this->success('message', 'You have been successfully logged out');
        }
    }
}
