<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CheckCodeRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Получить детальные данные для страницы essence",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          in="query",
     *          required=true,
     *          example="test@mail.ru",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="name",
     *          description="Имя",
     *          in="query",
     *          required=true,
     *          example="Александр Александров",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Пароль",
     *          in="query",
     *          required=true,
     *          example="123qweasd",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Пользователь зарегистрирован, а код отправлен ему на почту",
     *          @OA\JsonContent(
     *          ),
     *          @OA\Header(
     *              header="X-Token",
     *              @OA\Schema(
     *                  type="string",
     *                  example="token value"
     *              ),
     *              description="Токен авторизации"
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Ошибка регистрации",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Произошла ошибка"
     *              ),
     *          )
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            $user = AuthService::register($data);
        } catch (Exception $exception) {
            Log::error('Ошибка при регистрации пользователя: ' . $exception->getMessage());
            return $this->errorResponse('Произошла ошибка при регистрации пользователя');
        }
        return $this->responseJson([], 201)->withHeaders(['X-Token' => $user->createToken('AUTO_TOKEN')->accessToken]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/check-code",
     *     summary="Проверить код регистрации",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *          name="code",
     *          description="Код",
     *          in="query",
     *          required=true,
     *          example="55555",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          in="query",
     *          required=true,
     *          example="test@example.ru",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Response(
     *          response=204,
     *          description="Код верный",
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Неверный код",
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @param CheckCodeRequest $request
     * @return JsonResponse
     */
    public function checkCode(CheckCodeRequest $request)
    {
        $data = $request->validated();
        $check = AuthService::checkCode($data);
        return $check
            ? $this->responseJson([], 204)
            : $this->responseValidationError('Неверный код');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Авторизация",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          in="query",
     *          required=true,
     *          example="test@mail.ru",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Пароль",
     *          in="query",
     *          required=true,
     *          example="123qweasd",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *          ),
     *          @OA\Header(
     *              header="X-Token",
     *              @OA\Schema(
     *                  type="string",
     *                  example="token value"
     *              ),
     *              description="Токен авторизации"
     *          )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Неверный логин или пароль",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Неверный логин или пароль"
     *              ),
     *          ),
     *     ),
     *     security={
     *       {"auth_api": {}}
     *     }
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::query()->where('email', $data['email'])->first();
        if ($user && Hash::check($data['password'], $user->password)) {
            return $this->responseJson([])->withHeaders(['X-Token' => $user->createToken('AUTO_TOKEN')->accessToken]);
        }
        return $this->responseValidationError('Неверный логин или пароль');
    }
}
