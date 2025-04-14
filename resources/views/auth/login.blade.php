<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- ถ้าใช้ Vite --}}
</head>

<body class="flex items-center justify-center min-h-screen px-4 bg-gray-100">

    <div class="w-full max-w-sm p-6 bg-white rounded shadow">
        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="pl-5 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="emp_id" class="block mb-1 text-sm font-medium text-gray-700">Employee ID</label>
                <input type="text" name="emp_id" id="emp_id" value="{{ old('emp_id') }}"
                    class="w-full px-3 py-2 text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    required autofocus>
            </div>

            <div class="mt-4">
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full px-3 py-2 text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                    Don't have an account? Register
                </a>

                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                    Log in
                </button>
            </div>
        </form>
    </div>

</body>

</html>
