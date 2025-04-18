<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $primaryKey = 'sec_id';

    protected $fillable = [
        'sec_name',
        'dept_id'];


    public function sections()
    {
        return $this->hasMany(Section::class,'sec_id','sec_id');
    }

    public function dept() {
        return $this->belongsTo(Dept::class, 'dept_id', 'dept_id');
    }
}
