<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

    protected $primaryKey = 'prob_id';
    protected $fillable =[
        'prob_id',
        'prob_desc',
        'emp_id',
        'pic_before',
        'location',
        'linked_report_id',
        'status'
    ];

    public function employee() {
        return $this->belongsTo(Employees::class, 'emp_id', 'emp_id');
    }

    public function problem()
    {
        return $this->hasMany(Problem::class,'prob_id','prob_id');
    }

}
