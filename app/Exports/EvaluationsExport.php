<?php

namespace App\Exports;

use App\Models\Evaluation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EvaluationsExport implements FromCollection, WithHeadings
{
    /**
     * Return all evaluations with necessary fields.
     */
    public function collection()
    {
        return Evaluation::with('repairRequest', 'user')
            ->get()
            ->map(function ($evaluation) {
                return [

                    'Ticket ID' => $evaluation->repairRequest->TicketNumber,
                    'วันที่แจ้งซ่อม' => $evaluation->repairRequest->created_at->format('Y-m-d'), 
                    'ผู้แจ้งซ่อม' => $evaluation->user->name,
                    'ความพึงพอใจ' => $evaluation->rating,
       
                ];
            });
    }

    /**
     * Define headings for the Excel file.
     */
    public function headings(): array
    {
        return [
            'Ticket ID',
            'วันที่แจ้งซ่อม',
            'ผู้แจ้งซ่อม',
            'ความพึงพอใจ',
            
        ];
    }
}
