<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRoleRequestForm;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    private $userRoleService;

    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    public function index()
    {
        $filters = request()->all();
        $data = $this->userRoleService->getData($filters);
        return Inertia::render('Module/UserRole', $data);
    }

    public function assignRole(UserRoleRequestForm $request)
    {
        if ($request->has('user_id')) {
            $this->userRoleService->assignRole($request);
            return redirect()->route('user-roles.index')->with('response', 'success');
        }
    }
}
