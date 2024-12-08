<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use OpenApi\Attributes as OA;

class SwaggerController extends Controller
{
    #[OA\Get(
        path: "/api/v1/admin/user-listing",
        summary: "List all non-admin users",
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "users retrieved success"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: "not found"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function sample()
    {
        return response()->json([
            'code' => 1,
            'status' => "success",
            "data" => []
        ]);
    }

    public function index(Request $request)
    {
        $data = [
            'appURL' => config('app.url'),
        ];
        return Inertia::render('Module/Swagger', $data);
    }
}
