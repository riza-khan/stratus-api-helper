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

            return response()->json([
                'data'    => $response['data']['config'],
                'success' => true,
                'alert' => [
                    'type'  => 'success',
                    'title' => 'Configuration retrieved!'
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'alert' => [
                    'type'  => 'error',
                    'title' => 'Error while retrieving configuration'
                ],
                'error'   => "$e"
            ]);
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
                return response()->json([
                    'success'   => true,
                    'alert'     => [
                        'type'  => 'success',
                        'title' => 'Configuration updated!'
                    ],
                    'data'      => $response['data']['new']
                ]);
            } else {
                return response()->json([
                    'success'   => false,
                    'alert'     => [
                        'type'  => 'error',
                        'title' => 'Unable to configuration updated!'
                    ],
                    'error'     => $response
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'alert'     => [
                    'type'  => 'error',
                    'title' => 'Server error!'
                ],
                'error'     => "$e"
            ]);
        }
    }


    private function configurationUrl(string $environment, string $configuration): string
    {
        return "https://ms-config-manager-$environment.digitalpfizer.com/api/v1/configs/$configuration";
    }
}
