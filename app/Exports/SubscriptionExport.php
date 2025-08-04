<?php

namespace App\Exports;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionExport implements FromCollection, WithHeadings
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

        $userIds = DB::table('users')
            ->where('mode', $mode)
            ->pluck('id')
            ->toArray();


        $query = Subscription::query();
        $query = $query->with('user:id,name,email,zipcode,stripe_id')
            ->select('id', 'user_id', 'stripe_id', 'stripe_plan', 'stripe_status', 'trial_ends_at', 'renews_at', 'ends_at', 'created_at')
            ->whereIn('user_id', $userIds)
            ->whereNotIn('stripe_plan', ['price_1R7AsbJSdAGJhCxpc0nHB1e8']);

        if (!empty($this->filters['status'])) {
            $query->where('stripe_status', $this->filters['status']);
        }

        return $query->get()->map(function ($subscription) {
            return [
                'Id'    => $subscription->id,
                'Stripe Id'     => $subscription->stripe_id,
                // 'Name'       => $subscription->user->name ?? '',
                'Email'      => $subscription->user->email ?? '',
                'Status'     => $subscription->stripe_status,
                'Start Date' => $subscription->created_at,
                'End Date'   => $subscription->ends_at ?? $subscription->renews_at,
            ];
        });
    }
    public function headings(): array
    {
        return ['Id', 'Stripe Id', 'Email', 'Status', 'Start Date', 'End Date'];
    }
}
