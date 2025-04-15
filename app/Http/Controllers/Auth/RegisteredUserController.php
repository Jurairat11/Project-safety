<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employees;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'emp_name' => ['required', 'string', 'max:255'],
        'lastname' => ['required', 'string', 'max:255'],
        'emp_id' => ['required', 'string', 'unique:users', 'unique:employees,emp_id'],
        'dept_id' => ['required', 'exists:depts,dept_id'], // ตรวจสอบว่าใช้ id หรือ dept_id จริง
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'emp_name' => $request->emp_name,
        'lastname' => $request->lastname,
        'emp_id' => $request->emp_id,
        'dept_id' => $request->dept_id,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'employee',
    ]);

    Employees::create([
        'emp_id'   => $user->emp_id,
        'emp_name' => $user->emp_name,
        'lastname' => $user->lastname,
        'dept_id'  => $user->dept_id,
    ]);

    event(new Registered($user));
    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
    }
}

