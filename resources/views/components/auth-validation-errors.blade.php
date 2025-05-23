@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'mb-4']) }}>
        <div class="font-medium text-red-600">Whoops! Something went wrong.</div>
        <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
