<x-filament::page>
    <div class="mb-4 text-lg font-bold">
        Problem Reported List
    </div>
    <div class="container px-4 py-6 mx-auto">
        <div class="flex flex-wrap gap-4">
            <x-dashboard-card class="w-[24%]" title="All Problems" :count="$totalProblems" color="gray" />
            <x-dashboard-card class="w-[24%]" title="New" :count="$newProblems" color="primary" />
            <x-dashboard-card class="w-[24%]" title="Reported" :count="$reportedProblems" color="info" />
            <x-dashboard-card class="w-[24%]" title="In Progress" :count="$inProgressProblems" color="warning" />
            <x-dashboard-card class="w-[24%]" title="Pending Review" :count="$pendingReviewProblems" color="success" />
            <x-dashboard-card class="w-[24%]" title="Dismissed" :count="$dismissedProblems" color="danger" />
            <x-dashboard-card class="w-[24%]" title="Closed" :count="$closedProblems" color="secondary" />
            <x-dashboard-card class="w-[24%]" title="Reopened" :count="$reopenedProblems" color="warning" />
        </div><br>

        {{ $this->table }}
    </div>

    <script>
        Livewire.on('redirect-to', event => {
            window.open(event.url, '_blank'); // หรือเปลี่ยนเป็น window.location.href ถ้าอยากเปิดในหน้าเดิม
        });
    </script>

</x-filament::page>
