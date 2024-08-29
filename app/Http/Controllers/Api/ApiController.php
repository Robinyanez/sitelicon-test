<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Interfaces\HttpCodeInterface;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends BaseController implements HttpCodeInterface
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use ApiResponse;
}
