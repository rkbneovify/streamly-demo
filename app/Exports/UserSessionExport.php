<?php

namespace App\Exports;

use App\SessionTable;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserSessionExport implements FromCollection, WithHeadings
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
        if (!empty($this->filters['role'])) {
            $query->with('roles');
            $query->whereHas('roles', function ($q) {
                $q->where('id', $this->filters['role']);
            });
        }
        $filteredUserIds = $query->pluck('id')->toArray();
        $allSession = SessionTable::with(['user.roles'])
            ->selectRaw("users_id, COUNT(id) as activesession")
            ->where("status", '1')
            ->whereIn('users_id', $filteredUserIds)
            ->groupBy('users_id')
            ->get();

        return   $allSession->map(function ($item, $key) {

            return [
                'Sl.No.' => $key + 1,
                'Role' => $item->user->roles->first()->name ?? '',
                'Name' => $item->user->name ?? '',
                'Email' => $item->user->email ?? '',
                'Active Devices' => $item->activesession ?? '',

                'Mode' => $item->user->mode === 'Testing' && $item->user->user_type === 'custom'
                    ? 'Custom Test'
                    : ($item->user->mode === 'Testing' && is_null($item->user->user_type)
                        ? 'Web Test'
                        : ($item->user->mode === 'Live'
                            ? 'Live'
                            : ''
                        )
                    ),
                'Created At' => $item->user->created_at->format("d/m/Y"),

            ];
        });
    }
    public function headings(): array
    {
        return ['Sl.No.', 'Role', 'Name', 'Email', "Active Devices", 'Mode', 'Created At'];
    }
}
