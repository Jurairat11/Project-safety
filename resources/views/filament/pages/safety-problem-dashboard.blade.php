<x-filament::page>
    <div class="mb-4 text-lg font-bold">
        Reported Problems List
    </div>
    {{ $this->table }}

    <script>
        Livewire.on('redirect-to', event => {
            window.open(event.url, '_blank'); // หรือเปลี่ยนเป็น window.location.href ถ้าอยากเปิดในหน้าเดิม
        });
    </script>

</x-filament::page>
