<?php
namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    protected $table = 'roles';

    public function getAllRoles()
    {
        return $this->findAll('id ASC');
    }
}
