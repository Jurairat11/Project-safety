@if (auth()->user()->unreadNotifications->count())
    <div class="p-4 mb-4 bg-yellow-100 rounded shadow">
        <h3 class="mb-2 text-lg font-bold">Notifications</h3>
        <ul class="space-y-2">
            @foreach (auth()->user()->unreadNotifications as $notification)
                <li class="p-2 bg-white rounded shadow">
                    <div class="font-semibold">{{ $notification->data['title'] }}</div>
                    <div class="text-sm">{{ $notification->data['message'] }}</div>
                    <a href="{{ $notification->data['url'] }}" class="text-sm text-blue-500">View</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
