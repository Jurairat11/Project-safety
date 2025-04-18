<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dept extends Model
{
    use HasFactory;

    protected $table = 'depts';

    protected $primaryKey = 'dept_id';

    protected $fillable = [
        'dept_name','dept_code'];

    public function users()
    {
        return $this->hasMany(User::class,'dept_id','dept_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue_report::class, 'responsible_dept_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'sec_id');
    }
}
