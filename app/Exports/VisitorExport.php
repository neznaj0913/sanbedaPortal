<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class VisitorExport implements FromCollection, WithHeadings, WithMapping
{
    protected $visitors;

    public function __construct(Collection $visitors)
    {
        $this->visitors = $visitors;
    }

    public function collection()
    {
        return $this->visitors;
    }

    public function map($visitor): array
    {
        // Calculate status dynamically (same logic as in your controller/blade)
        $status = $visitor->time_out ? 'Timed Out' : ($visitor->time_in ? 'Inside' : 'Pending');

        return [
            'First Name' => $visitor->first_name,
            'Last Name'  => $visitor->last_name,
            'Department' => $visitor->department,
            'Purpose'    => $visitor->purpose,
            'Email'      => $visitor->email, // Added email if needed
            'Status'     => $status, // Use calculated status
            'Time In'    => $visitor->time_in 
                ? Carbon::parse($visitor->time_in)->timezone('Asia/Manila')->format('m/d/Y h:i A') 
                : '',
            'Time Out'   => $visitor->time_out 
                ? Carbon::parse($visitor->time_out)->timezone('Asia/Manila')->format('m/d/Y h:i A') 
                : '',
        ];
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Department',
            'Purpose',
            'Email', // Added email header
            'Status',
            'Time In',
            'Time Out',
        ];
    }
}