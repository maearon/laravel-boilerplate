<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResponseResource;
use Illuminate\Http\JsonResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->authenticateApi(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        return new LoginResponseResource($result);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return new JsonResponse(['message' => 'Logged out successfully']);
    }
}
