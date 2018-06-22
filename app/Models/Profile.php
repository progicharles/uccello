<?php

namespace Sardoj\Uccello\Models;

use Sardoj\Uccello\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, $this->tablePrefix . 'profiles_roles');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Returns profile capabilities on a module
     *
     * @param Module $module
     * @return array
     */
    public function capabilitiesOnModule(Module $module) : array
    {
        $capabilities = [];

        // Get profile permissions on module
        $permissions = $this->permissions->where('module_id', $module->id);

        foreach ($permissions as $permission) {
            if (!in_array($permission->capability, $capabilities)) {
                $capabilities[] = $permission->capability;
            }
        }

        return $capabilities;
    }

    /**
     * Checks if the profil has a capability on a module
     *
     * @param string $capability
     * @param Module $module
     * @return boolean
     */
    public function hasCapabilityOnModule(string $capability, Module $module) : bool
    {
        $hasCapability = false;

        foreach ($this->capabilitiesOnModule($module) as $capabilityOnModule) {
            if ($capabilityOnModule === $capability) {
                $hasCapability = true;
                break;
            }
        }

        return $hasCapability;
    }
}
