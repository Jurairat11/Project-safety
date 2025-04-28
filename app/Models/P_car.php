<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class P_car extends Model
{
    use HasFactory;

    protected $primaryKey = 'form_no';
    protected $fillable =[
        'form_no',
        'safety_dept',
        'section',
        'issue_date',
        'dead_line',
        'issue_desc',
        'hazard_level_id',
        'hazard_type_id',
        'img_before',
        'img_after',
        'cause',
        'temporary_act',
        'temp_due_date',
        'temp_responsible',
        'permanent_act',
        'perm_due_date',
        'perm_responsible',
        'preventive_act',
        'status',
        'parent_id',
        'report_id',
        'response_id'
    ];

    public function issue_reports(){
        return $this->belongsTo(Issue_report::class,'report_id','report_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id', 'emp_id');
    }

    public function hazardLevel(){
        return $this->belongsTo(HazardLevel::class,'hazard_level_id','hazard_level_id');
    }

    public function hazardType(){
        return $this->belongsTo(HazardType::class,'hazard_type_id','hazard_type_id');
    }

    public function safetyDept()
    {
        return $this->belongsTo(Dept::class, 'safety_dept');
    }

    public function sectionRelation()
    {
        return $this->belongsTo(Section::class, 'section', 'sec_id');
    }

    public function parent()
    {
        return $this->belongsTo(Issue_report::class, 'parent_id','report_id');
    }



}
