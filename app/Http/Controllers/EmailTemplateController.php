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

            return response()->json(['data' => $response['data']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function updateEmailTemplate(Request $request): JsonResponse
    {
        try {
            $response = $this->defaultHttp()->put(
                'production',
                $request['body']
            )->json();

            return response()->json([
                'data'    => $response['data'],
                'success' => true,
                'message' => 'Email Update'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    private function emailUrl(string $environment, string $template = ''): string
    {
        return "https://ms-config-manager-$environment.digitalpfizer.com/api/v1/emails/templates/$template";
    }
}
