<?php

namespace App\Http\Controllers;

use App\DTO\Resources\AccountListDTO;
use App\Models\ExpenseAccount;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/accounts",
     *     summary="Получить список счетов",
     *     tags={"Accounts"},
     *     @OA\Response(
     *          response=200,
     *          description="Список счетов",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/AccountList")
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Неверный запрос",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Invalid request"
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
    public function index()
    {
        return $this->responseJson(AccountListDTO::collect(ExpenseAccount::all()));
    }
}
