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
        //        $code = self::codeGenerate();
//        RegisterCode::query()->create([
//            'code' => $code,
//            'user_id' => $user->id
//        ]);
//        Mail::to($user->email)->send(new SendCode($code));
        return User::query()->create($data);
    }

    public function checkCode(array $data): bool
    {
<<<<<<< HEAD
        $userCode = User::query()->where('email', $data['email'])->first()
            ?->codes()->latest()->first()
            ?->code;
        return $data['code'] === $userCode;
=======
        return true;
>>>>>>> dceaf3ff56958bf50fa8d393aaba48e59073db5a
    }

    private static function codeGenerate(): int
    {
        return rand(10000, 99999);
    }
}
