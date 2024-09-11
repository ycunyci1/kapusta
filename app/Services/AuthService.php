<?php

namespace App\Services;

use App\Mail\SendCode;
use App\Models\RegisterCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public static function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::query()->create($data);
        $code = self::codeGenerate();
        RegisterCode::query()->create([
            'code' => $code,
            'user_id' => $user->id
        ]);
        Mail::to($user->email)->send(new SendCode($code));
        return $user;
    }

    public static function checkCode(array $data): bool
    {
        $userCode = User::query()->where('email', $data['email'])->first()
            ?->codes()->latest()->first()
            ?->code;
        return $data['code'] === $userCode;
    }

    private static function codeGenerate(): int
    {
        return rand(10000, 99999);
    }
}
