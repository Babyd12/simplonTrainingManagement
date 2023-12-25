<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Traits\ReturnJsonResponseTrait;

class AuthController extends Controller
{
    use ReturnJsonResponseTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Loged a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="password", type="string", description="User's password"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email (unique)"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Validation failed")
     * )
     */
    public function login(LoginRequest $request)
    {
        return $this->returLoginJsonResponse($request->validated(), 'guard:api');
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="User's name"),
     *             @OA\Property(property="firstName", type="string", description="User's first name"),
     *             @OA\Property(property="residence", type="string", description="User's residence"),
     *             @OA\Property(property="password", type="string", description="User's password"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email (unique)"),
     *             @OA\Property(property="profilePicture", type="string", description="URL or base64-encoded image of the user's profile picture"),
     *             @OA\Property(property="levelOfStudy", type="string", description="User's level of study"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Validation failed")
     * )
     */

    public function register(RegisterUserRequest $request)
    {
        return $this->returnJsonResponse('action:store', $request->validated(), 'model:User');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     @OA\Response(response="200", description="Successfully logged out"),
     *     security={{ "apiToken":{} }}
     * )
     */
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="refresh the user's token",
     *     @OA\Response(response="200", description="Successfully logged out"),
     *     security={{ "apiToken":{} }}
     * )
     */
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
