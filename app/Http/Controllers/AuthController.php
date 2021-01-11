<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;


class AuthController extends Controller
{
    /**
     * Checking for server side OAuth authentication
     * Check for authorization
     *
     * To avoid storing passport client secret in vuejs we call passport to generate token within server
     * At first I used Guzzle HTTP client to make request but due to the constraint of Laravel server as single thread
     * this was not working, I used Route dispatch as work around just to make it easy for tester to run app in Laravel server
     * rather then create a Virtual Host(multi thread)
     * @param LoginRequest $request
     * @return Object
     */
    public function login(LoginRequest $request, UserService $userService)
    {
        $post = [
            "grant_type" => "password",
            'client_id' => config('continuum.passport_client'),
            'client_secret' => config('continuum.passport_secret'),
            "username" => $request->email,
            "password" => $request->password,
        ];

        try {

            request()->merge($post);

            // Make a request to a passport route token to issue toke
            // If there is an error it is set in data.error
            $response = Route::dispatch(request()->create(
                route('passport.token'), 'POST')
            );

            $result = json_decode((string)$response->getContent(), true);

            $token = [];

            if (isset($result['error'])) {
                return response()->json(['message'=>'Wrong credentials'],401);
            }

            $user = $userService->findByEmail($post['username']);
            $token['token'] = $result;
            $token['user'] = new UserResource($user);

            return response()->json($token,200);


        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return UserResource
     */
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Logs out an authenticated user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
        } catch (Exception $exception) {
            return response()->json("Unable to do logout" . $exception->getMessage(), 422);
        }
        return response()->json('Logged out success', 200);
    }

}
