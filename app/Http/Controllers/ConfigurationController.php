<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
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

            if ($response['success']) {
                return response()->json(['data' => $response['data']['new']]);
            } else {
                return response()->json(['data' => $response]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }


    private function configurationUrl(string $environment, string $configuration): string
    {
        return "https://ms-config-manager-$environment.digitalpfizer.com/api/v1/configs/$configuration";
    }
}
