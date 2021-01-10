<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\LoginRequest;


class AuthController extends Controller
{
    /**
     * Checking for server side OAuth authentication
     * Check for authorization
     * @param LoginRequest $request
     * @return Object
     */
    public function login(LoginRequest $request)
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

            // If there is an error it is set in data.error
            $response = Route::dispatch(request()->create(
                route('passport.token'), 'POST')
            );

            return json_decode((string) $response->getContent(), true);

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
