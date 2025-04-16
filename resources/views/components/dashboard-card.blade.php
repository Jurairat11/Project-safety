@props(['title', 'count' => 0, 'color' => 'gray'])

@php
    $bg =
        [
            'blue' => 'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100',
            'yellow' => 'bg-yellow-100 text-yellow-900 dark:bg-yellow-900 dark:text-yellow-100',
            'green' => 'bg-green-100 text-green-900 dark:bg-green-900 dark:text-green-100',
            'red' => 'bg-red-100 text-red-900 dark:bg-red-900 dark:text-red-100',
            'gray' => 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white',
            'indigo' => 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100',
        ][$color] ?? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white';
@endphp

<div class="flex-1 min-w-[160px] max-w-[200px] p-4 rounded-lg shadow text-center {{ $bg }}">
    <div class="text-sm font-medium">{{ $title }}</div>
    <div class="text-2xl font-bold">{{ $count }}</div>
</div>
