<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PfizerController extends Controller
{
    public function token(Request $request): JsonResponse
    {
        try {
            $request->user()->update(
                $request->validate([
                    'pfizer_token' => ['required', 'string']
                ])
            );

            return response()->json([
                'success' => true,
                'alert'   => [
                    'type'  => 'success',
                    'title' => 'Token saved!'
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error: $e"
            ]);
        }
    }
}
