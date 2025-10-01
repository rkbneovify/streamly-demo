<?php

namespace App\Exports;

use App\Models\Subscription;
use Illuminate\Http\Request;
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


        $query = Subscription::query()
            ->whereHas('user')   // only subscriptions with a related user
            ->with('user');      // still eager load user data



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
                'User Type' => getUserType($item->user),
                'Subscription Type' => $item->livemode === null ? 'Admin/Custom' : ($item->livemode === 1 ? 'Stripe/Live' : 'Stripe/Test'),
                'Status' => $item->stripe_status,
                'Subscribe At' => $item->created_at ? $item->created_at->format('d/m/Y') : '',
                'Subscription Expire At' => getSubscriptionExpireAt($item) != null ? getSubscriptionExpireAt($item) : 'Never',
            ];
        });


    }
    public function headings(): array
    {
        return ['Sl. No.', 'Name', 'Email', 'User Type', 'Subscription Type', 'Status', 'Subscribe At', 'Subscription Expire At'];
    }


}
