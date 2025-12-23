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

        $query->when($this->request->has('status'), function ($q) {
            if ($this->request->status == 1) {
                $q->where('status', 1);
            } else {
                $q->where('status', 0);
            }

        });
        $query->when($this->request->has('sort'), function ($q) {


            if ($this->request->sort == 'latest') {
                $q->latest();
            } elseif ($this->request->sort == 'oldest') {
                $q->orderBy('created_at', 'asc');
            }

        });
        // search 
        $query->when($this->request->has('search'), function ($q) {
            $txt = $this->request->search;
            if ($txt != '') {
                $q->where(function ($subQuery) use ($txt) {
                    $subQuery->where('ordering', 'like', "%{$txt}%")
                        ->orWhere('passcode', 'like', "%{$txt}%")
                        ->orWhere('name', 'like', "%{$txt}%")
                        ->orWhere('email', 'like', "%{$txt}%")
                        ->orWhere('phone', 'like', "%{$txt}%")
                        ->orWhere('house_no', 'like', "%{$txt}%");
                });
            }
        });

        // search 
        $query->when($this->request->has('q'), function ($q) {
            $txt = $this->request->q;
            if ($txt != '') {
                $q->where(function ($subQuery) use ($txt) {
                    $subQuery->where('ordering', 'like', "%{$txt}%")
                        ->orWhere('passcode', 'like', "%{$txt}%")
                        ->orWhere('name', 'like', "%{$txt}%")
                        ->orWhere('email', 'like', "%{$txt}%")
                        ->orWhere('phone', 'like', "%{$txt}%")
                        ->orWhere('house_no', 'like', "%{$txt}%");
                });
            }
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
                "House No." => $item->house_no == null ? 'House' . $item->ordering : $item->house_no,
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