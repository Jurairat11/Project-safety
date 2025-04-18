<x-filament::page>
    <div class="mb-4 text-lg font-bold">
        Problem Reported List
    </div>
    <div class="container px-4 py-6 mx-auto">
        <div class="flex flex-wrap justify-center gap-4">
            <x-dashboard-card title="All Problems" :count="$totalProblems" color="gray" />
            <x-dashboard-card title="New" :count="$newProblems" color="blue" />
            <x-dashboard-card title="Reported" :count="$reportedProblems" color="yellow" />
            <x-dashboard-card title="In Progress" :count="$inProgressProblems" color="indigo" />
            <x-dashboard-card title="Resolved" :count="$resolvedProblems" color="green" />
            <x-dashboard-card title="Dismissed" :count="$dismissedProblems" color="red" />
        </div>
    </div>
    {{ $this->table }}

    <script>
        Livewire.on('redirect-to', event => {
            window.open(event.url, '_blank'); // หรือเปลี่ยนเป็น window.location.href ถ้าอยากเปิดในหน้าเดิม
        });
    </script>

</x-filament::page>
