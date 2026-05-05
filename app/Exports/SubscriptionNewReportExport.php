<?php

namespace App\Exports;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SubscriptionNewReportExport implements FromCollection, WithHeadings
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


        $latestSubscriptions = Subscription::query()
            ->select('user_id', DB::raw('MAX(created_at) as latest_created_at'))
            ->where('livemode', 1)
            ->whereNull('plan_duration')
            ->whereNotNull('renews_at')
            ->whereNotNull('current_period_start')
            ->groupBy('user_id');

        $query = Subscription::query()
            ->with('user')
            ->joinSub($latestSubscriptions, 'latest', function ($join) {
                $join->on('subscriptions.user_id', '=', 'latest.user_id')
                    ->on('subscriptions.created_at', '=', 'latest.latest_created_at');
            })
            ->whereHas('user');


        // // Filter by status if provided
        if (!empty($this->filters->status)) {
            $query->where('stripe_status', $this->filters->status);
        }


        if ($this->filters->sort_subscribe_at == null || $this->filters->sort_subscribe_at == 'desc') {
            $query->orderByDesc('created_at');
        } else {
            $query->orderBy('created_at');
        }
        return $query->get()->map(function ($item, $key) {
            return [
                'Sl. No.' => $key + 1,
                'Name' => $item->user->name,
                'Email' => $item->user->email,
                'Subscription Type' => $item->livemode === null ? 'Admin(Custom)' : ($item->livemode === 1 ? 'Stripe(Live)' : 'Stripe(Test)'),
                'Status' => $item->stripe_status,
                'Amount' => $item->amount,
                'Subscribe At' =>
                    date('d/m/Y', strtotime($item->current_period_start)),

                'Subscription Expire At' =>
                    date('d/m/Y', strtotime($item->renews_at))
            ];
        });


    }
    public function headings(): array
    {
        return ['Sl. No.', 'Name', 'Email', 'Subscription Type', 'Status', 'Amount', 'Subscribe At', 'Subscription Expire At'];
    }


}
