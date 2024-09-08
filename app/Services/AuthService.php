<?php

namespace App\Services;

use App\Mail\SendCode;
use App\Models\RegisterCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public static function register(array $data): int
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::query()->create($data);
        $code = self::codeGenerate();
        RegisterCode::query()->create([
            'code' => $code,
            'user_id' => $user->id
        ]);
        Mail::to($user->email)->send(new SendCode($code));
        return $user->id;
    }

    public function checkCode(array $data): void
    {
        return $code ===
    }

    private static function codeGenerate(): int
    {
        return rand(10000, 99999);
    }
}
