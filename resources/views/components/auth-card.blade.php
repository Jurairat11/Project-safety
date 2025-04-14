<div class="flex flex-col items-center justify-center min-h-screen px-4 bg-gray-100">
    {{-- เอาโลโก้ออกถ้าไม่ใช้ --}}
    {{-- <div>
        {{ $logo ?? '' }}
    </div> --}}

    <div class="w-full max-w-sm p-6 bg-white rounded-md shadow">
        {{ $slot }}
    </div>
</div>
