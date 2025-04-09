<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HazardType extends Model
{
    use HasFactory;

    protected $primaryKey = 'hazard_type_id';
    protected $fillable = [
        'Desc'
    ];

    public function hazard_type()
    {
        return $this->hasMany(HazardType::class,'hazard_type_id','hazard_type_id');
    }
}
