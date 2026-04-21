<?php
namespace App\Core;

class Middleware
{
    private $auth;

    /**
     * Permission matrix: role => [controller => [allowed actions]]
     * '*' means all actions allowed
     */
    private $permissions = [
        'admin' => [
            '*' => ['*'], // Admin has full access
        ],
        'pm' => [
            'Dashboard'  => ['*'],
            'Users'      => ['index', 'profile'],
            'Projects'   => ['*'],
            'Tasks'      => ['*'],
            'Timesheets' => ['*'],
            'Resources'  => ['*'],
            'Reports'    => ['*'],
            'Excel'      => ['*'],
        ],
        'hr' => [
            'Dashboard'  => ['*'],
            'Users'      => ['index', 'create', 'store', 'edit', 'update', 'toggleActive', 'rates', 'saveRates', 'profile'],
            'Projects'   => ['index', 'detail'],
            'Tasks'      => ['index'],
            'Timesheets' => ['index', 'history', 'calendar'],
            'Resources'  => ['allocation'],
            'Reports'    => ['*'],
            'Excel'      => ['*'],
        ],
        'staff' => [
            'Dashboard'  => ['index'],
            'Users'      => ['profile'],
            'Projects'   => ['index', 'detail'],
            'Tasks'      => ['index', 'kanban', 'detail'],
            'Timesheets' => ['index', 'create', 'store', 'edit', 'update', 'delete', 'history', 'calendar'],
            'Resources'  => ['allocation'],
            'Reports'    => ['individual'],
            'Excel'      => ['exportTimesheets'],
        ],
    ];

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function checkAccess($controller, $action)
    {
        $user = $this->auth->user();
        if (!$user) {
            return false;
        }

        $userRoles = $user['roles'] ?? [$user['role']];

        // Check each role - if any role allows access, grant it
        foreach ($userRoles as $role) {
            $perms = $this->permissions[$role] ?? [];

            // Check wildcard access (admin)
            if (isset($perms['*']) && in_array('*', $perms['*'])) {
                return true;
            }

            // Check controller-specific access
            if (isset($perms[$controller])) {
                $allowedActions = $perms[$controller];
                if (in_array('*', $allowedActions) || in_array($action, $allowedActions)) {
                    return true;
                }
            }
        }

        return false;
    }
}
