<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Admin extends Model
{
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
