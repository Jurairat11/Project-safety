@if ($path)
    <div class="mt-4">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">
            Picture Before
        </label>
        <img src="{{ asset('storage/' . $path) }}" class="object-contain max-w-xs border rounded shadow md:max-w-sm"
            alt="Picture Before">
    </div>
@else
    <span class="text-gray-400 dark:text-gray-500">No image.</span>
@endif
