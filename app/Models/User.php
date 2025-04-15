<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'email', 'password', 'emp_id', 'dept_id' ,'emp_name','lastname','role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentName(): string
    {
        return trim(($this->emp_name ?? '') . ' ' . ($this->lastname ?? ''));
    }

    public function getNameAttribute(): string
    {
        return trim(($this->emp_name ?? '') . ' ' . ($this->lastname ?? ''));
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'safety' => 'Safety Officer',
            'department' => 'Department',
            'employee' => 'Employee',
            default => 'Unknown',
        };
    }
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id', 'emp_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Dept::class, 'dept_id', 'dept_id');
    }



}
