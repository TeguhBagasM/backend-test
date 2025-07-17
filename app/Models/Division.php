<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Division extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}