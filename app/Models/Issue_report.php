<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue_report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    protected $fillable = [
        'report_id',
        'prob_id',
        'prob_desc',
        'issue_desc',
        'hazard_level_id',
        'hazard_type_id',
        'img_before',
        'status',
        'emp_id',
        'dept_id',
        'responsible_dept_id',
    ];

    public function problem(){
        return $this->belongsTo(Problem::class,'prob_id','prob_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id', 'emp_id');
    }

    public function dept(){
        return $this->belongsTo(Dept::class,'dept_id','dept_id');
    }

    public function issue_report()
    {
        return $this->hasMany(Issue_report::class, 'report_id', 'report_id');
    }

    public function hazardLevel(){
        return $this->belongsTo(HazardLevel::class,'hazard_level_id','hazard_level_id');
    }

    public function hazardType(){
        return $this->belongsTo(HazardType::class,'hazard_type_id','hazard_type_id');
    }

    public function responsibleDept()
{
    return $this->belongsTo(Dept::class, 'responsible_dept_id');
}
}
