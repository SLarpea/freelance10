<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleService
{
    public function getData($filters)
    {
        $data = [
            'users' => $this->getUsers($filters),
            'roles' => $this->getRoles()
        ];
        return $data;
    }

    public function assignRole($request)
    {
        $data = $request->validated();
        $user = User::findOrFail($data['user_id']);
        $role = Role::findByName($data['role_name']);
        $user->syncRoles([]);
        $user->assignRole($role);
    }

    private function getUsers($filters)
    {
        $userRole = get_authorized_user()->roles[0]->name;
        $query = User::with('roles')
        ->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Superadmin');
        })->when(isset($filters['search']), function ($query) use ($filters) {
            $query->where('name', 'LIKE', '%' . $filters['search'] . '%');
        });

        if ($userRole == 'Administrator' || $userRole == 'Admin') $query->whereNot('id', get_authorized_user()->id);

        return $query->orderBy('id', 'asc')
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    private function getRoles()
    {
        $userRole = get_authorized_user()->roles[0]->name;
        if ($userRole == 'Superadmin')
            $query = Role::whereNot('status', 0)->get();
        if ($userRole == 'Administrator' || $userRole == 'Admin')
            $query = Role::where([
                ['name', '!=', 'Superadmin'],
                ['status', '!=', 0]
            ])->get();
        return $query;
    }
}