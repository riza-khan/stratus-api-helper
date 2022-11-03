<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        try {
            $form        = $request->configuration;
            $environment = $request->environment;

            $response = $this->defaultHttp($form)->get(
                $this->formUrl($environment)
            )->json();

            if (count($response['data'])) {
                $obj    = $response['data'];
                $fields = array_map(function ($field) {
                    if ($field['name'] !== 'submit') {
                        return [$field['name'] => $field['value'] ?? 'change-me'];
                    }
                }, $obj['fields']);

                return response()->json([
                    'form'    => $this->flatten_array(['csrfToken' => $obj['csrfToken'], $fields]),
                    'all'     => $obj,
                    'success' => true,
                    'alert'   => [
                        'type'  => 'success',
                        'title' => 'Form retrived successfully'
                    ]
                ]);
            } else {
                return response()->json([
                    'form'    => '[]',
                    'success' => false,
                    'alert'   => [
                        'type'  => 'error',
                        'title' => 'Failed to retrieve form'
                    ]
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'alert'   => [
                    'type'  => 'error',
                    'title' => 'Server Error'
                ],
                'error'   => "$e"
            ]);
        }
    }

    public function submit(Request $request): JsonResponse
    {
        try {
            $config_token = $request['params']['configuration'];
            $environment  = $request['params']['environment'];
            $form         = $request['body'];

            $response = $this->defaultHttp($config_token)->post(
                $this->formUrl($environment),
                $form
            )->json();

            return response()->json([
                'data'    => $response,
                'success' => true,
                'alert'   => [
                    'type'  => 'success',
                    'title' => 'Form submitted'
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'alert'   => [
                    'type'    => 'success',
                    'title'   => 'Form submitted',
                    'message' => "$e"
                ]
            ]);
        }
    }


    private function formUrl(string $environment): string
    {
        return "https://ms-forms-service-$environment.digitalpfizer.com/api/v2/forms/";
    }

    private function flatten_array(array $array): array
    {
        return array_filter(iterator_to_array(
            new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array))
        ), function ($value) {
            if ($value !== null) {
                return $value;
            }
        });
    }
}
