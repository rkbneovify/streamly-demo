<?php

namespace App\Exports;

use App\LocalZipcode;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LocalZipcodeExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        $query = LocalZipcode::query()
            ->search($this->request->search)
            ->status($this->request->status)
            ->orderByCreated($this->request->created);
        if (!$this->request->has('created')) {
            $query->orderByDesc('created_at');
        }

        return $query->get()->map(function ($item, $key) {
            return [
                "Sr. No." => $key + 1,
                "Zipcode" => $item->zipcode,
                "City" => $item->city,
                "State" => $item->state,
                "Total Channels" => LocalZipcode::zipcodeStatusCountByUse($item->zipcode),
                "Created At" => $item->created_at
            ];
        });



    }
    public function headings(): array
    {
        return ["Sr. No.", "Zipcode", "City", "State", "Total Channels", "Created At"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // Row 1 (headings)
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '000000'], // Black background
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
