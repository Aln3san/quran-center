<?php

namespace App\Traits;

trait ResponseTrait
{
    public function apiResponse($data = null, $message = null, $status = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $status);
    }

    public function successResponse($data = null, $message = "Succes Response", $status = 200)
    {
        return $this->apiResponse($data, $message, $status);
    }

    public function errorResponse($data = null, $message = "Error Response", $status = 400)
    {
        return $this->apiResponse($data, $message, $status);
    }
}
