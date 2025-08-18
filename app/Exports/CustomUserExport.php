<?php

namespace App\Exports;

use App\Models\Subscription;
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

        $query = User::query()
            ->with('roles', 'customSubscription')
            ->where('is_custom_user', 1)
            ->orderBy(
                Subscription::select('created_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->limit(1),
                'desc'
            );
        if (!empty($this->filters['role'])) {
            $query->whereHas('roles', function ($q) {
                $q->where('id', $this->filters['role']);
            });
        }
        $query->orderByDesc("created_at");
        return $query->get()->map(function ($user, $key) {
            return [
                'Sl.No.' => $key + 1,
                "Email" => $user->email,
                "Name" => $user->name,
                "Role" => $user->roles->first()->display_name,
                "Subscription Type" => $user->is_live_on_stripe == 1 ? "Live" : "Custom",
                "Status" => $user->customSubscription->stripe_status,
                "Subscription Expires At" => $user->customSubscription->ends_at != null ? $user->customSubscription->ends_at->format('d/m/Y') : ($user->customSubscription->renews_at != null ? $user->customSubscription->renews_at->format('d/m/Y') : $user->customSubscription->trial_ends_at->format('d/m/Y')),
            ];
        });
    }

    public function headings(): array
    {
        return ['Sl.No.', 'Email', 'Name', 'Role', "Subscription Type", 'Status', "Subscription Expires At"];
    }
}
