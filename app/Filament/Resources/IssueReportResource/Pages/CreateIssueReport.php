<?php

namespace App\Filament\Resources\IssueReportResource\Pages;

use App\Filament\Resources\IssueReportResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Problem;
use Carbon\Carbon;



class CreateIssueReport extends CreateRecord
{
    protected static string $resource = IssueReportResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->prob_id) {
            Problem::where('prob_id', $record->prob_id)
                ->where('status', 'new')
                ->update(['status' => 'reported']);
        }
    }
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'prob_id'             => request()->get('prob_id'),
            'form_no' => 'C ' . str_pad(
                    \App\Models\Issue_report::whereYear('created_at', now()->year)->count() + 1,
                    2,
                    '0',
                    STR_PAD_LEFT
                ) . '/' . Carbon::now()->format('y'),
            'safety_dept'         => request()->get('safety_dept'),
            'section'             => request()->get('section'),
            'issue_date'          => request()->get('issue_date'),
            'dead_line'           => request()->get('dead_line'),
            'issue_desc'          => request()->get('issue_desc'),
            'hazard_level_id'     => request()->get('hazard_level_id'),
            'hazard_type_id'      => request()->get('hazard_type_id'),
            'img_before'          => request()->get('img_before'),
            'created_by'         => request()->get('created_by'),
            'responsible_dept_id' => request()->get('responsible_dept_id'),
            'parent_id'           => request()->get('parent_id'),
        ]);
    }

}
