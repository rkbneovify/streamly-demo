<?php

namespace App\Exports;

use App\HoaUser;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HoaSubUserExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $request;
    protected $id;
    public function __construct(Request $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {



        $query = HoaUser::query();
        $query->where('user_id', $this->id);
        $query->with('session');
        $query->when($this->request->created, function ($q, $created) {


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
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('passcode', 'like', "%{$search}%");
            });
        });



        return $query->get()->map(function ($item, $key) {

            $activeDevices = '';
            if ($item->session_count > 0) {
                $activeDevices = $item->session_count . '/' . $item->device_limit;
            } else {
                $activeDevices = '0/' . $item->device_limit;
            }
            return [
                "Sr. No." => $key + 1,
                "Name" => $item->name,
                "Email" => $item->email,
                "Phone" => $item->phone,
                "House No." => $item->house_no,
                "Passcode" => $item->passcode,
                "Status" => $item->status == 1 ? 'Active' : 'Inactive',
                "Active Devices" => $activeDevices,
                "Created At" => $item->created_at->format('d/m/Y'),


            ];
        });
    }

    public function headings(): array
    {
        return ["Sr. No.", "Name", "Email", "Phone", "House No", "Passcode", "Status", "Active Devices", "Created At"];
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