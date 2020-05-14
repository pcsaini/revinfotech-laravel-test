<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'phone_number', 'image', 'gender'];

    public function students() {
        return $this->hasMany(Student::class, 'teacher_id');
    }
}
