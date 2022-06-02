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
            $form        = $request->form;
            $environment = $request->environment;

            $response = $this->defaultHttp($form)->get(
                $this->formUrl($environment)
            )->json();

            $obj = $response['data'];
            $fields = array_map(function ($field) {
                if ($field['name'] !== 'submit') {
                    return [$field['name'] => 'change-me'];
                }
            }, $obj['fields']);

            return response()->json([
                'data'    => $this->flatten_array(['csrfToken' => $obj['csrfToken'], $fields]),
                'success' => true,
                'message' => 'Form Retrieved'
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => "Server Error: $e"]);
        }
    }

    public function submit(Request $request): JsonResponse
    {
        try {
            $config_token = $request['params']['form'];
            $environment = $request['params']['environment'];
            $form        = $request['body'];

            $response = $this->defaultHttp($config_token)->post(
                $this->formUrl($environment),
                $form
            )->json();

            return response()->json([
                'data'    => $response,
                'success' => true,
                'message' => 'Form Submitted'
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => "Server Error: $e"]);
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
