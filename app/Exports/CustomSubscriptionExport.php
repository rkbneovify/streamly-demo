<?php

namespace App\Exports;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CustomSubscriptionExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(Request $filters)
    {
        $this->filters = $filters;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {


        $query = Subscription::query()
            ->whereHas('user')   // only subscriptions with a related user
            ->with(['user.roles', 'plan'])      // still eager load user data
            ->whereNotNull('custom_status');






        return $query->get()->map(function ($item, $key) {
            return [
                'Sl. No.' => $key + 1,
                'Name' => $item->user->name,
                'Email' => $item->user->email,
                'Subscription Type' => 'custom',
                'Status' => $item->custom_status,
                'Plan Duration' => $item->plan_duration == null ? ($item->custom_status == 'active' ? 'Never' : 'Never') :  $item->plan_duration,
                'Subscribe At' =>
                date('d/m/Y', strtotime($item->current_period_start)),

                'Subscription Expire At' => $item->renews_at == null ? 'Never' :
                    date('d/m/Y', strtotime($item->renews_at))
            ];
        });
    }
    public function headings(): array
    {
        return ['Sl. No.', 'Name', 'Email', 'Subscription Type', 'Status', 'Plan Duration', 'Subscribe At', 'Subscription Expire At'];
    }
}
