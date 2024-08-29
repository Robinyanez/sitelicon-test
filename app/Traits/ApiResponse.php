<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data=null, $status=200, $message='success')
    {
        return $this->successResponseJson([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], $status);
    }

    public function errorResponse($data=null, $status=500, $message='error')
    {
        $status = $this->validHttpstatus($status);
        $message = $this->validHttpMessage($message);

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], $status);
    }

    private function validHttpstatus($status)
    {
        if (empty($status)) {

            $status = 500;

        } else if ($status < 100 ||  $status > 599) {

            $status = 500;
        }

        return $status;
    }

    private function validHttpMessage($message)
    {
        return (empty($message)) ? 'Internal Server Error' : $message;
    }

    private function successResponseJson($data, $status)
    {
        return response()->json($data, $status);
    }
}
