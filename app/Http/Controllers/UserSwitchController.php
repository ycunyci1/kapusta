<?php

namespace App\Http\Controllers;

use App\Models\BaseUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSwitchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/switch-random",
     *     summary="Сменить пользователя на рандомного",
     *     tags={"Temp"},
     *     @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(type="string")
     *          )
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Server error"
     *              )
     *          )
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @return JsonResponse
     */
    public function switchToRandomUser()
    {
        $user = User::inRandomOrder()->first();
        BaseUser::first()->update(['user_id' => $user->id]);
        auth()->loginUsingId($user->id);

        return $this->responseJson("Switched to random user $user->name");
    }

    /**
     * @OA\Get(
     *     path="/api/v1/switch-new",
     *     summary="Сменить пользователя на нового(без проектов и других сущностей)",
     *     tags={"Temp"},
     *     @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(type="string")
     *          )
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Server error"
     *              )
     *          )
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @return JsonResponse
     */
    public function switchToNewUser()
    {
        $user = User::factory()->create();
        BaseUser::first()->update(['user_id' => $user->id]);
        auth()->loginUsingId($user->id);

        return $this->responseJson("Switched to new user $user->name");
    }
}
