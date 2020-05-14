<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'phone_number', 'image', 'gender', 'teacher_id'];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
