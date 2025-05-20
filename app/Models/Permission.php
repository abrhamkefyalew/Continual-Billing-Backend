<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use SoftDeletes, HasRelationships;


    public $table = 'permissions';

    protected $fillable = [
        'title',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function admins()
    {
        return $this->hasManyDeepFromRelations($this->roles(), (new Role())->admins());
    }

    // no Permission Groups Yet




    // BASIC

    public const INDEX_ADMIN = 'INDEX_ADMIN';

    public const SHOW_ADMIN = 'SHOW_ADMIN';

    public const CREATE_ADMIN = 'CREATE_ADMIN';

    public const EDIT_ADMIN = 'EDIT_ADMIN';

    public const DELETE_ADMIN = 'DELETE_ADMIN';

    public const RESTORE_ADMIN = 'RESTORE_ADMIN';



    


    public const INDEX_ROLE = 'INDEX_ROLE';

    public const SHOW_ROLE = 'SHOW_ROLE';

    public const CREATE_ROLE = 'CREATE_ROLE';

    public const EDIT_ROLE = 'EDIT_ROLE';

    public const DELETE_ROLE = 'DELETE_ROLE';

    public const RESTORE_ROLE = 'RESTORE_ROLE';


    public const INDEX_PERMISSION = 'INDEX_PERMISSION';

    public const SHOW_PERMISSION = 'SHOW_PERMISSION';
    

    public const SYNC_PERMISSION_ROLE = 'SYNC_PERMISSION_ROLE';

    public const INDEX_PERMISSION_ROLE = 'INDEX_PERMISSION_ROLE';

    public const SHOW_PERMISSION_ROLE = 'SHOW_PERMISSION_ROLE';

    public const CREATE_PERMISSION_ROLE = 'CREATE_PERMISSION_ROLE';

    public const EDIT_PERMISSION_ROLE = 'EDIT_PERMISSION_ROLE';

    public const DELETE_PERMISSION_ROLE = 'DELETE_PERMISSION_ROLE';

    public const RESTORE_PERMISSION_ROLE = 'RESTORE_PERMISSION_ROLE';


    public const SYNC_ADMIN_ROLE = 'SYNC_ADMIN_ROLE'; // you need policy for this // abrham comment
    
    public const INDEX_ADMIN_ROLE = 'INDEX_ADMIN_ROLE';

    public const SHOW_ADMIN_ROLE = 'SHOW_ADMIN_ROLE';

    public const CREATE_ADMIN_ROLE = 'CREATE_ADMIN_ROLE';

    public const EDIT_ADMIN_ROLE = 'EDIT_ADMIN_ROLE';

    public const DELETE_ADMIN_ROLE = 'DELETE_ADMIN_ROLE';

    public const RESTORE_ADMIN_ROLE = 'DELETE_ADMIN_ROLE';









}
