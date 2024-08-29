<?php

use Carbon\Carbon;
use App\Models\ApiLog;

function saveAndSendError($content_error)
{
    $error = new ApiLog;
    $error->input_date = Carbon::now()->format('Y-m-d H:i:s');
    $error->input_json = json_encode($content_error['request']);
    $error->response_date = Carbon::now()->format('Y-m-d H:i:s');
    $error->response_json = is_null($content_error['response']) ? $content_error['error']['message'] : json_encode($content_error['response']);
    $error->response_code = is_null($content_error['code']) ? $content_error['error']['code'] : $content_error['code'];
    $error->url = $content_error['url'];
    $error->save();
}
