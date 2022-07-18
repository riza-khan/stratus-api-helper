<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function defaultHttp(string $config_token = ''): PendingRequest
    {
        $user = Auth::user();

        $headers = [
            'Accept'         => 'application/json',
            'Authorization'  => "Bearer $user->pfizer_token",
            'x-config-token' => $config_token
        ];

        return Http::withHeaders($headers);
    }
}
