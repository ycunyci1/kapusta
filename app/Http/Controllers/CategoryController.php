<?php

namespace App\Http\Controllers;

use App\DTO\Resources\CategoryListDTO;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return $this->responseJson(CategoryListDTO::collect(Category::all()));
    }
}
