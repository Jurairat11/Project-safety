@if ($path)
    <div class="mt-4">
        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-100">
            Picture After
        </label>
        <img src="{{ asset('storage/' . $path) }}" class="object-contain max-w-xs border rounded shadow md:max-w-sm"
            alt="Picture After">
    </div>
@else
    <span class="text-gray-400">No image available.</span>
@endif
