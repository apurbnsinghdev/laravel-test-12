<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Employee extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['name', 'email', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }
}
