<?php
namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        Validator::make($input, [
            'emp_name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'emp_id' => ['required', 'string', 'max:255', 'unique:users'],
            'dept_id' => ['required', 'exists:depts,dept_id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ])->validate();

        return User::create([
            'emp_name' => $input['emp_name'],
            'lastname' => $input['lastname'],
            'emp_id' => $input['emp_id'],
            'dept_id' => $input['dept_id'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'employee',
        ]);
    }
}

?>
