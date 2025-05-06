<?php

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

return [
    'openapi' => '3.0.0',
    'info' => [
        'title' => 'CareConnect API Documentation',
        'description' => 'API documentation for CareConnect Healthcare Management System',
        'version' => '1.0.0',
        'contact' => [
            'email' => 'support@careconnect.com'
        ]
    ],
    'servers' => [
        [
            'url' => 'http://127.0.0.1:8000',
            'description' => 'API Server'
        ]
    ],
    'security' => [
        ['bearerAuth' => []]
    ],
    'components' => [
        'securitySchemes' => [
            'bearerAuth' => [
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT'
            ]
        ],
        'schemas' => [
            'Error' => [
                'type' => 'object',
                'properties' => [
                    'message' => [
                        'type' => 'string',
                        'example' => 'Error message'
                    ],
                    'errors' => [
                        'type' => 'object'
                    ]
                ]
            ],
            'Success' => [
                'type' => 'object',
                'properties' => [
                    'message' => [
                        'type' => 'string',
                        'example' => 'Success message'
                    ],
                    'data' => [
                        'type' => 'object'
                    ]
                ]
            ]
        ]
    ],
    'paths' => [
        '/api' => [
            'get' => [
                'summary' => 'API Root',
                'responses' => [
                    '200' => [
                        'description' => 'API is running'
                    ]
                ]
            ]
        ]
    ],
    'tags' => [
        [
            'name' => 'Authentication',
            'description' => 'API Endpoints for user authentication'
        ],
        [
            'name' => 'User Profile',
            'description' => 'API Endpoints for user profile management'
        ],
        [
            'name' => 'Doctors',
            'description' => 'API Endpoints for doctor management'
        ],
        [
            'name' => 'Patients',
            'description' => 'API Endpoints for patient management'
        ],
        [
            'name' => 'Appointments',
            'description' => 'API Endpoints for appointment management'
        ],
        [
            'name' => 'Prescriptions',
            'description' => 'API Endpoints for prescription management'
        ],
        [
            'name' => 'Reports',
            'description' => 'API Endpoints for medical reports'
        ],
        [
            'name' => 'Feedbacks',
            'description' => 'API Endpoints for feedback management'
        ],
        [
            'name' => 'Chats',
            'description' => 'API Endpoints for chat management'
        ],
        [
            'name' => 'Messages',
            'description' => 'API Endpoints for message management'
        ],
        [
            'name' => 'Sports',
            'description' => 'API Endpoints for sport recommendations'
        ],
        [
            'name' => 'Foods',
            'description' => 'API Endpoints for food recommendations'
        ]
    ]
]; 