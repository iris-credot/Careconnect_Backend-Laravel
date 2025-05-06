<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/documentation",
     *     summary="Get API documentation",
     *     tags={"Documentation"},
     *     @OA\Response(
     *         response=200,
     *         description="API documentation"
     *     )
     * )
     */
    public function index()
    {
        $documentation = 'default';
        $config = config('l5-swagger.documentations.' . $documentation);
        
        $urlToDocs = route('l5-swagger.'.$documentation.'.docs', config('l5-swagger.defaults.routes.docs'), true);
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
} 