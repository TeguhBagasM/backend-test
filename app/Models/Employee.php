<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Employee extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['image', 'name', 'phone', 'division_id', 'position'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}