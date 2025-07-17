<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

class Admin extends Model
{
    use HasApiTokens; 
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'username', 'password', 'phone', 'email'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
