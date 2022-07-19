<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function getEmailTemplate(Request $request): JsonResponse
    {
        try {
            $response = $this->defaultHttp()->get(
                $this->emailUrl('production', $request->name)
            )->json();

            return response()->json([
                'success' => true,
                'data' => $response['data'],
                'alert' => [
                    'type'  => 'success',
                    'title' => "Email template retrieved!"
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function updateEmailTemplate(Request $request): JsonResponse
    {
        try {
            $response = $this->defaultHttp()->put(
                $this->emailUrl('production'),
                $request['body']
            )->json();

            return response()->json([
                'data'    => $response['data'],
                'success' => true,
                'alert' => [
                    'type'  => 'success',
                    'title' => "Email template updated!"
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'alert' => [
                    'type'  => 'error',
                    'title' => "Server Error!"
                ],
                'error' => "$e"
            ]);
        }
    }

    private function emailUrl(string $environment = 'production', string $template = ''): string
    {
        return "https://ms-config-manager-$environment.digitalpfizer.com/api/v1/emails/templates/$template";
    }
}
