<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class ProductController extends ApiController
{
    public function index()
    {
        $products = Product::select('id', 'code', 'name', 'price', 'stock')->get();

        return $this->successResponse($products, self::OK);
    }
}
