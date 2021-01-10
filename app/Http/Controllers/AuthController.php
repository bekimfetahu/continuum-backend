<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\User as UserResource;


class AuthController extends Controller
{
    /**
     * Checking for server side OAuth authentication
     * Check for authorization
     * @param LoginRequest $request
     * @return Object
     */
    public function login(LoginRequest $request){

        $post = [
            "grant_type"    => "password",
            'client_id' => config('continuum.passport_client'),
            'client_secret' => config('continuum.passport_secret'),
            "username"      => $request->email,
            "password"      => $request->password,
        ];

        try{
           $request->merge($post);

            $req = $request->create(route('passport.token'), 'POST');

            $response = Route::dispatch($req);

            return $response->getContent();

        }
        catch(Exception $e){
            if ($e->getCode() == 400) {
                return response()->json('Wrong email or password', $e->getCode());
            } elseif ($e->getCode() == 401) {
                return response()->json('Incorrect credentials', $e->getCode());
            } else if ($e->getCode() == 500) {
                return response()->json('Error', $e->getCode());
            } else {
                return response()->json('Error!', $e->getCode());
            }
        }
    }

    public function userData(Request $request)
    {
        return new UserResource($request->user());
    }

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
