<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e]);
        }
    }

    public function getConfiguration(Request $request): JsonResponse
    {
        try {
            $response = $this->defaultHttp()->get(
                $this->configurationUrl($request->environment, $request->configuration)
            )->json();

            return response()->json(['data' => $response['data']['config']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function updateConfiguration(Request $request): JsonResponse
    {
        try {
            $response = $this->defaultHttp($request['params']['configuration'])->put(
                $this->configurationUrl($request['params']['environment'], $request['params']['configuration']),
                $request['body']
            )->json();

            return response()->json(['data' => $response['data']['new']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function getEmailTemplate(Request $request): JsonResponse
    {
        try {
            $response = $this->defaultHttp()->get(
                $this->emailUrl($request->environment, $request->name)
            )->json();

            return response()->json(['data' => $response['data']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function updateEmailTemplate(Request $request): JsonResponse
    {
        try {
            $response = $this->defaultHttp()->put(
                $this->emailUrl($request['params']['environment']),
                $request['body']
            )->json();

            return response()->json(['data' => $response['data']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    private function defaultHttp(string $config_token = 'foobar'): PendingRequest
    {
        $user = Auth::user();

        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => "Bearer $user->pfizer_token",
            'x-config-token' => $config_token
        ];

        return Http::withHeaders($headers);
    }

    private function configurationUrl(string $environment, string $configuration): string
    {
        return "https://ms-config-manager-$environment.digitalpfizer.com/api/v1/configs/$configuration";
    }

    private function emailUrl(string $environment, string $template = ''): string
    {
        return "https://ms-config-manager-$environment.digitalpfizer.com/api/v1/emails/templates/$template";
    }
}
