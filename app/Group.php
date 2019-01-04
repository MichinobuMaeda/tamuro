<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get roles for the group.
     * 
     * @return collection
     */
    public function roles()
    {
        return $this->hasMany('App\GroupRole');
    }

    /**
     * Get sub-groups of the group.
     * 
     * @return collection
     */
    public function subGroups()
    {
        return $this->belongsToMany('App\Group', 'sub_groups', 'group_id', 'sub_group_id');
    }

    /**
     * Get super-groups of the group.
     * 
     * @return collection
     */
    public function superGroups()
    {
        return $this->belongsToMany('App\Group', 'sub_groups', 'sub_group_id', 'group_id');
    }

    /**
     * Get members of the group.
     * 
     * @return collection
     */
    public function members()
    {
        return $this->belongsToMany('App\User', 'members', 'group_id', 'user_id');
    }

    /**
     * Get managers of the group.
     * 
     * @return collection
     */
    public function managers()
    {
        return $this->belongsToMany('App\User', 'group_managers', 'group_id', 'user_id');
    }
}
