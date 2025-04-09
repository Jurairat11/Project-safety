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
}
