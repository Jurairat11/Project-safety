<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HazardLevel extends Model
{
    use HasFactory;

    protected $primaryKey = 'hazard_level_id';

    protected $fillable = [
        'Level',
        'desc'
    ];

    public function hazard_level()
    {
        return $this->hasMany(HazardLevel::class,'hazard_level_id','hazard_level_id');
    }
}
