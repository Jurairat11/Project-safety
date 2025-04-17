<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue_responses extends Model
{
    use HasFactory;
    protected $primaryKey = 'response_id';
    protected $fillable = [
        'response_id',
        'report_id',
        'safety_emp_id',
        'cause',
        'img_after',
        'temporary_act',
        'permanent_act',
        'temp_due_date',
        'perm_due_date',
        'temp_responsible',
        'perm_responsible',
        'preventive_act',
        'created_by',
        'reply_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id', 'emp_id');
    }

    public function issue_report()
    {
        return $this->belongsTo(Issue_report::class, 'report_id', 'report_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Employees::class, 'created_by', 'emp_id');
    }


}
