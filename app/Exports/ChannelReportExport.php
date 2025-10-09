<?php

namespace App\Exports;

use App\Models\Channel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ChannelReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $query = Channel::query();

        $query = Channel::query();
        $query->with('genreNames');

        $query->when($this->request->sort_created, function ($q, $created) {


            if ($created == 'asc') {
                $q->orderBy('created_at');
            } else {
                $q->orderBy('created_at', 'desc');
            }


        });

        $query->when($this->request->status, function ($q, $status) {
            if ($status == 1) {
                $q->where('status', 1);
            } else {
                $q->where('status', 0);
            }

        });
        // search 
        $query->when($this->request->search, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('stationName', 'like', "%{$search}%")
                    ->orWhere('callSign', 'like', "%{$search}%");
            });
        });

        $query->when($this->request->availability, function ($q, $availability) {
            if (trim($availability == 'all')) {
                $q->whereJsonContains('zip_code', 'All');
            } else {
                $q->whereJsonDoesntContain('zip_code', 'All');
            }
        });



        $query->when($this->request->genre, function ($q, $genre) {
            if ($genre == "Others") {
                $q->where('genres', "");
            } else {
                $q->whereHas('genreNames', function ($q) use ($genre) {
                    return $q->where('name', $genre);
                });
            }

        });

        return $query->get()->map(function ($item) {

            return [
                "Channel Id" => $item->id,
                "Name" => $item->name,
                "Station Name" => $item->stationName,
                "Genres" => $item->genreNames->count() <= 0 ? "Others" : $item->genreNames->pluck('name')->implode(', '),
                "Availability" => count($item->zip_code) > 1 ? implode(', ', $item->zip_code) : $item->zip_code[0],
                "Status" => $item->status == 1 ? 'Active' : 'Inactive',
                "Call Sign" => $item->callSign,
                "Created At" => $item->created_at->format('d/m/Y'),
                "Url" => $item->url,
                "Dash Url" => $item->dash_url,
            ];
        });
    }

    public function headings(): array
    {
        return ["Channel Id", "Name", "Station", "Genres", "Availability", "Status", "Call Sign", "Created At", "Url", "Dash Url"];
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
