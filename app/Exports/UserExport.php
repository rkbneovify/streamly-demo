<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class UserExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $query = User::query();

        $query->with('roles');
        $query->withCount('sessions');
        $query->withCount('rooms');
        if (!empty($this->filters['role'])) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->filters['role']);
            });
        }
        if (!empty($this->filters['sort_date']) && $this->filters['sort_date'] == 'asc') {
            $query->orderBy('created_at', 'asc');
        } elseif (empty($this->filters->sort_date) && !empty($this->filters->daterange)) {
            $query->orderBy('created_at', 'asc');
            request()->merge(['sort_date' => 'asc']);
        } else {

            $query->latest();
        }



        if (!empty($this->filters['mode'])) {
            $query->where('mode', $this->filters['mode']);
        }
        if (!empty($this->filters['status'])) {
            $status = null;
            if ($this->filters['status'] == 'active') {
                $status = 1;
            } elseif ($this->filters['status'] == 'inactive') {
                $status = 0;
            }
            $query->where('is_active', $status);
        }
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if (!empty($this->filters['daterange'])) {
            $daterange = $this->filters['daterange'];
            $query->where(function ($q) use ($daterange) {
                $dates = explode(" - ", $daterange);
                $start = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
                $q->whereBetween('created_at', [$start, $end]);
            });



        }
        return $query->get()->map(function ($user, $key) {
            return [
                "Sl.No." => $key + 1,
                "Name" => $user->name,
                "Email" => $user->email,
                "Role" => $user->roles->first() != null ? $user->roles->first()->display_name : "N/A",
                "User Type" => $user->mode == 'Live' ? 'Live' : 'Testing',
                "Status" => $user->is_active == 1 ? 'Active' : 'Inactive',
                "Active Devices" => ($user->sessions_count ?? 0) . ' / ' . ($user->rooms_count > 0 ? $user->rooms_count : 5),
                "Created At" => $user->created_at->format('d/m/Y'),

            ];
        });
    }

    public function headings(): array
    {
        return ["Sl.No.", 'Name', 'Email', 'Role', "User Type", 'Status', "Active Devices", "Created At"];
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
