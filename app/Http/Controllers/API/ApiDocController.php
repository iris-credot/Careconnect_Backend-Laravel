<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use L5Swagger\Http\Controllers\SwaggerController;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="CareConnect API Documentation",
 *     description="API documentation for CareConnect Healthcare Management System",
 *     @OA\Contact(
 *         email="support@careconnect.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\PathItem(
 *     path="/api"
 * )
 * 
 * @OA\Get(
 *     path="/api",
 *     summary="API Root",
 *     @OA\Response(
 *         response=200,
 *         description="API is running"
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ApiDocController extends SwaggerController
{
    public function index()
    {
        $documentation = 'default';
        $config = config('l5-swagger.documentations.' . $documentation);
        
        $urlToDocs = route('l5-swagger.'.$documentation.'.docs', [], true);
        $operationsSorter = config('l5-swagger.defaults.operations_sort');
        $configUrl = config('l5-swagger.defaults.additional_config_url');
        $validatorUrl = config('l5-swagger.defaults.validator_url');
        $useAbsolutePath = config('l5-swagger.documentations.' . $documentation . '.paths.use_absolute_path', true);

        return view('l5-swagger::index', compact(
            'documentation',
            'urlToDocs',
            'operationsSorter',
            'configUrl',
            'validatorUrl',
            'useAbsolutePath'
        ));
    }

    public function docs(Request $request)
    {
        $filePath = storage_path('api-docs/api-docs.json');
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Documentation file not found'], 404);
        }
        
        return response()->file($filePath, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function oauth2Callback(Request $request)
    {
        return parent::oauth2Callback($request);
    }
} 