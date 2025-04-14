<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 text-gray-500 fill-current" />
            </a>
        </x-slot>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- First Name -->
            <div>
                <x-input-label for="emp_name" :value="__('First Name')" />
                <x-text-input id="emp_name" class="block w-full mt-1" type="text" name="emp_name" :value="old('emp_name')"
                    required autofocus />
            </div>

            <!-- Last Name -->
            <div class="mt-4">
                <x-input-label for="lastname" :value="__('Last Name')" />
                <x-text-input id="lastname" class="block w-full mt-1" type="text" name="lastname" :value="old('lastname')"
                    required />
            </div>

            <!-- Employee ID -->
            <div class="mt-4">
                <x-input-label for="emp_id" :value="__('Employee ID')" />
                <x-text-input id="emp_id" class="block w-full mt-1" type="text" name="emp_id" :value="old('emp_id')"
                    required />
            </div>

            <!-- Department -->
            <div class="mt-4">
                <x-input-label for="dept_id" :value="__('Department')" />
                <select name="dept_id" id="dept_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                    required>
                    <option value="">-- Select Department --</option>
                    @foreach (App\Models\Dept::all() as $dept)
                        <option value="{{ $dept->dept_id }}">{{ $dept->dept_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')"
                    required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block w-full mt-1" type="password"
                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
