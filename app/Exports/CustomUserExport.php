<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomUserExport implements FromCollection, WithHeadings
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
        $query->with('subscription');
        $query->where('is_custom_user', 1);
        if (!empty($this->filters['role'])) {
            $query->whereHas('roles', function ($q) {
                $q->where('id', $this->filters['role']);
            });
        }
        return $query->get()->map(function ($user, $key) {
            return [
                'Sl.No.' => $key + 1,
                "Subscription Id" => $user->subscription->stripe_id,
                "Role" => $user->roles->first()->display_name,
                "Name" => $user->name,
                "Email" => $user->email,
                "Status" => $user->is_active == 1 ? 'Active' : 'Inactive',
                "Created At" => $user->created_at->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Sl.No.', "Subscription Id", 'Role', 'Name', 'Email', 'Status', "Created At"];
    }
}
