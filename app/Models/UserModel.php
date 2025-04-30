<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['level_id', 'nama', 'username', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];
    public function level(): BelongsTo 
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
    public function getRoleName(){
        return $this->level->level_name;
    }
    public function hasRole($role){
        return $this->level->level_name == $role;
    }
    public function getRole(){
        return $this->level->level_kode;
    }
}
