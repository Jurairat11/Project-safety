<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Safety extends Model
{

    use HasFactory;

    protected $primaryKey = 'safety_id';
    protected $fillable = [
        'prob_id',
        'hazard_level_id',
        'hazard_type_id',
        'pic_before',
        'pic_after'
    ];

    public function problem(){
        return $this->belongsTo(Problem::class,'prob_id','prob_id');
    }

    public function hazardLevel(){
        return $this->belongsTo(HazardLevel::class,'hazard_level_id','hazard_level_id');
    }

    public function hazardType(){
        return $this->belongsTo(HazardType::class,'hazard_type_id','hazard_type_id');
    }
}
