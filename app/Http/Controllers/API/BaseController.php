<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

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
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 * 
 * @OA\Tag(
 *     name="User Profile",
 *     description="API Endpoints for user profile management"
 * )
 * 
 * @OA\Tag(
 *     name="Doctors",
 *     description="API Endpoints for doctor management"
 * )
 * 
 * @OA\Tag(
 *     name="Patients",
 *     description="API Endpoints for patient management"
 * )
 * 
 * @OA\Tag(
 *     name="Appointments",
 *     description="API Endpoints for appointment management"
 * )
 * 
 * @OA\Tag(
 *     name="Prescriptions",
 *     description="API Endpoints for prescription management"
 * )
 * 
 * @OA\Tag(
 *     name="Reports",
 *     description="API Endpoints for medical reports"
 * )
 * 
 * @OA\Tag(
 *     name="Feedbacks",
 *     description="API Endpoints for feedback management"
 * )
 * 
 * @OA\Tag(
 *     name="Chats",
 *     description="API Endpoints for chat management"
 * )
 * 
 * @OA\Tag(
 *     name="Messages",
 *     description="API Endpoints for message management"
 * )
 * 
 * @OA\Tag(
 *     name="Sports",
 *     description="API Endpoints for sport recommendations"
 * )
 * 
 * @OA\Tag(
 *     name="Foods",
 *     description="API Endpoints for food recommendations"
 * )
 */
class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * @param  array $result
     * @param  string $message
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * Error response method.
     *
     * @param  string $error
     * @param  array  $errorMessages
     * @param  int    $code
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
} 