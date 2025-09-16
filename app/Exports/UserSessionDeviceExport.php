<?php

namespace App\Exports;

use App\HotelRoomTable;
use App\Models\Role;
use App\SessionTable;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UserSessionDeviceExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $id;
    protected $user;
    protected $totalRooms;
    public function __construct($id)
    {
        $this->id = $id;
        $user = User::find($this->id);
        $this->user = $user;
        $this->totalRooms = HotelRoomTable::where('user_id', $id)->count();
    }

    public function collection()
    {



        return SessionTable::with([
            'user.roles',
            'hotel',
            'pdInfo' => fn($query) => $query->where('user_id', $this->id),
        ])
            ->selectRaw("id, users_id, connected_devices, access_token, device_id,
        SUBSTRING_INDEX(mac_address, '#~', 1) as device_name,device_title,device_model,platform,
        build_version,device_os_version,zipcode,
        created_on,
        JSON_UNQUOTE(JSON_EXTRACT(device, '$.useragent')) as useragent")
            ->where('status', 1)
            ->where('users_id', $this->id)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('session_table')
                    ->where('users_id', $this->id)
                    ->where('status', 1)
                    ->groupBy('device_id');
            })
            ->orderBy('connected_devices', 'asc')
            ->get()->map(function ($item, $key) {

                if ($this->user->roles->first()->id == 5) {


                    return [
                        'Sl. No.' => $key + 1,
                        'Device Name' => $item->device_type == 'web' ? 'web' : $item->pdInfo->personal_devicename,
                        'Platform' => getPlatform($item->useragent, $item->device_title),
                        'Device ID' => $item->device_id,
                        'Device Model' => getDeviceModel($item->useragent, $item->device_title, $item->device_model),
                        'Build Version' => $item->build_version,
                        'Device OS Version' => $item->device_os_version,
                        'Zipcode' => $item->zipcode,
                        'Hotel Room' => $item->hotel != null ? $item->hotel->hotel_room : 'N/A',
                        'Date Added' => $item->created_on,
                    ];
                } else {
                    return [
                        'Sl. No.' => $key + 1,
                        'Device Name' => $item->device_type == 'web' ? 'web' : $item->pdInfo->personal_devicename,
                        'Platform' => getPlatform($item->useragent, $item->device_title),
                        'Device ID' => $item->device_id,
                        'Device Model' => getDeviceModel($item->useragent, $item->device_title, $item->device_model),
                        'Build Version' => $item->build_version,
                        'Device OS Version' => $item->device_os_version,
                        'Zipcode' => $item->zipcode,
                        'Date Added' => $item->created_on,
                    ];
                }

            });

    }
    public function headings(): array
    {

        if ($this->user->roles->first()->id == 5) {

            return [
                ["User Session Device Report"],
                ['Name', 'Email', 'Role', 'Total Devices'],
                [$this->user->name, $this->user->email, $this->user->roles->first()->name, $this->user->roles->first()->id == 5 ? $this->collection()->count() . "/" . $this->totalRooms : $this->collection()->count() . "/" . 5],
                ['Sl. No.', 'Device Name', 'Platform', 'Device ID', 'Device Model', 'Build Version', 'Device OS Version', 'Zipcode', 'Hotel Room', 'Date Added']
            ];


        } else {

            return [
                ["User Session Device Report"],
                ['Name', 'Email', 'Role', 'Total Devices'],
                [$this->user->name, $this->user->email, $this->user->roles->first()->name, $this->user->roles->first()->id == 5 ? $this->collection()->count() . "/" . $this->totalRooms : $this->collection()->count() . "/" . 5],
                ['Sl. No.', 'Device Name', 'Platform', 'Device ID', 'Device Model', 'Build Version', 'Device OS Version', 'Zipcode', 'Date Added']
            ];

        }

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                if ($this->user->roles->first()->id == 5) {
                    $event->sheet->mergeCells('A1:I1');
                    // Center align
                    $event->sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Set font bold and size
                    $event->sheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(14);
                } else {
                    $event->sheet->mergeCells('A1:I1');
                    // Center align
                    $event->sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Set font bold and size
                    $event->sheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(14);
                }


                // blank row insert
                $event->sheet->insertNewRowBefore(2, 1);
                $event->sheet->insertNewRowBefore(5, 1);

            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],

                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            3 => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],

            4 => [
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
