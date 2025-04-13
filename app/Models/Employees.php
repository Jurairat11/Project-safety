<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $table = "employees";
    protected $fillable = [
        'emp_id',
        'emp_name',
        'lastname',
        'dept_id'];

    public function employee()
    {
        return $this->hasMany(Employees::class,'emp_id','emp_id');
    }

    public function dept(){
        return $this->belongsTo(Dept::class,'dept_id','dept_id');
    }

    public function safeties()
    {
        return $this->hasMany(Issue_report::class, 'emp_id', 'emp_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->emp_name} {$this->lastname}";
    }
}
