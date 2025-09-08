<?php

namespace App\Exports;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class UsersSubscriptionExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
        $query = Subscription::query()->groupBy('user_id')->latest('updated_at')->with('user');

        $query = Subscription::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('subscriptions')
                ->groupBy('user_id');
        });
        if ($mode == 'Testing' || $mode == 'Test') {
            $query->whereNotIn("stripe_plan", ['lLqM2zp3oUMO', 'price_1MqxXZGbCi8tAySGisSrhf0S']);
        }
        $query->whereHas('user', function ($query) use ($mode) {

            $query->whereNull("user_type")->where('mode', $mode);
        });

        if (!empty($this->filters['sort_subscribe_at'])) {
            if ($this->filters['sort_subscribe_at'] == 'desc') {
                $query->latest('updated_at');
            } else {
                $query->orderBy('updated_at');
            }
        }

        if (!empty($this->filters['status'])) {
            $query->where('stripe_status', $this->filters['status']);
        }
        return $query->get()->map(function ($item, $key) use ($mode) {
            return [
                'Sl.No.' => $key + 1,
                'Name' => $item->user->name ?? '',
                'Email' => $item->user->email ?? '',
                'Status' => $item->stripe_status,
                'Subscription Type' => ($mode == 'Testing' || $mode == 'Test') ? 'Custom' : 'Live',
                'Subscribe At' => $item->updated_at->format('d/m/Y'),
                'Subscription Expire At' => $item->renews_at
                    ? $item->renews_at->format('d/m/Y')
                    : ($item->ends_at
                        ? $item->ends_at->format('d/m/Y')
                        : ($item->trial_ends_at && ($item->user->is_custom_user != 1)
                            ? $item->trial_ends_at->format('d/m/Y')
                            : 'Never'
                        )
                    ),
            ];
        });
    }

    public function headings(): array
    {
        return ['Sl.No.', 'Name', 'Email', 'Status', 'Subscription Type', 'Subscribe At', 'Subscription Expire At'];
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
