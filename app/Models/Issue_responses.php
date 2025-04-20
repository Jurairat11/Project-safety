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
        'reply_at',
        'remark',
        'form_no',
    ];

    protected $casts = [
        'temp_due_date' => 'date',     // จะได้เป็น Carbon
        'perm_due_date' => 'date',
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

    public function tempResponsible()
    {

        return $this->belongsTo(Employees::class, 'temp_responsible', 'emp_id');
    }

    public function permResponsible()
    {
        return $this->belongsTo(Employees::class, 'perm_responsible', 'emp_id');
    }
}
