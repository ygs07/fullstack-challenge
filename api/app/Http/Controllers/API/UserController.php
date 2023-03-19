<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateWeatherData;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        dispatch(
            new UpdateWeatherData
        );


        $key = 'users.all';


        $users = Redis::get($key);

        if ($value = Redis::get($key)) {
            return response()->json(
                [
                    'users' => json_decode($value),
                    'isFirstVisit' => 0
                ]
            );
        }

        $users = User::all();
        Redis::set("users.all", $users);
        Redis::expire($key, 60 * 60); // Set cache expiration time to 1hr seconds



        return response()->json(
            [
                'users' => $users,
                'isFirstVisit' => 1

            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = json_decode(Redis::get('users.all'))[$user->id - 1];
        return response()->json(
            [
                'user' => $user
            ]
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateWeatherData(User $user)
    {
        $user->updateCurrentWeatherAttribute();
        return response()->json(
            [
                'user' => $user
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAllUserWeatherData()
    {
        $users = User::all();
        $key  = "users.all";
        Redis::set("users.all", $users);
        Redis::expire($key, 60 * 60);
        // $user->updateCurrentWeatherAttribute();
        return response()->json(
            [
                'users' => $users
            ]
        );
    }
}
