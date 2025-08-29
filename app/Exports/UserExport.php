<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
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
        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');
        $query = User::query();
        $query->where('mode', $mode);
        $query->with('roles');
        if (!empty($this->filters['role'])) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->filters['role']);
            });
        }
        return $query->get()->map(function ($user) {
            return [
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
        return ['Id', 'Role', 'Name', 'Email', "Contact No.", 'Status', "Created At"];
    }
}
