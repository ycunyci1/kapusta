<?php

namespace App\Http\Controllers;

use App\Models\BaseUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserSwitchController extends Controller
{
    public function switchToRandomUser()
    {
        $user = User::inRandomOrder()->first();
        BaseUser::first()->update(['user_id' => $user->id]);
        auth()->loginUsingId($user->id);

        return $this->responseJson('Switched to random user');
    }

    public function switchToNewUser()
    {
        $user = User::factory()->create();
        BaseUser::first()->update(['user_id' => $user->id]);
        auth()->loginUsingId($user->id);

        return $this->responseJson('Switched to new user');
    }
}
