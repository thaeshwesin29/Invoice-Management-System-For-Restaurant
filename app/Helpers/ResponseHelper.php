<?php

function success($data = [], $message = 'Request was successful', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

function error($data = [], $message = 'Something went wrong', $status = 400)
{
    return response()->json([
        'success' => false,
        'message' => $message,
        'data' => $data,
    ], $status);
}

function successMessage($message = 'Request was successful', $status = 200)
{
    return response()->json([
        'success' => true,
        'message' => $message,
    ], $status);
}

function errorMessage($message = 'Something went wrong', $status = 400)
{
    return response()->json([
        'success' => false,
        'message' => $message,
    ], $status);
}
