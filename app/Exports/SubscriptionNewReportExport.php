<?php

namespace App\Exports;

use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SubscriptionNewReportExport implements FromCollection, WithHeadings, WithChunkReading
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
        $stripeService = new StripeService();

        $query = Subscription::query()
            ->whereHas('user')   // only subscriptions with a related user
            ->with('user');      // still eager load user data


        if ($this->filters->sort_subscribe_at == null || $this->filters->sort_subscribe_at == 'desc') {
            $query->orderByDesc('updated_at');
        } else {
            $query->orderBy('updated_at');
        }
        // // Filter by status if provided
        if (!empty($this->filters->status)) {
            $query->where('stripe_status', $this->filters->status);
        }



        return $query->get()->map(function ($item, $key) use ($stripeService) {
            return [
                'Sl. No.' => $key + 1,
                'Name' => $item->user->name,
                'Email' => $item->user->email,
                'User Type' => getUserType($item->user),
                'Subscription Type' => $stripeService->hasSubscription($item),
                'Status' => $item->stripe_status,
                'Subscribe At' => $item->updated_at ? $item->updated_at->format('d/m/Y') : '',
                'Subscription Expire At' => getSubscriptionExpireAt($item) != null ? getSubscriptionExpireAt($item) : 'Never',
            ];
        });


    }
    public function headings(): array
    {
        return ['Sl. No.', 'Name', 'Email', 'User Type', 'Subscription Type', 'Status', 'Subscribe At', 'Subscription Expire At'];
    }

    public function chunkSize(): int
    {
        return 5; // Process 1000 records at a time
    }
}
