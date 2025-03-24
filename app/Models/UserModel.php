<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['level_id', 'nama', 'username', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];
    public function level(): BelongsTo 
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}
