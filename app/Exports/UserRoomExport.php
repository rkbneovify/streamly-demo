<?php

namespace App\Exports;

use App\HotelRoomTable;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserRoomExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');

        $query = User::query()
            ->where('mode', $mode);
        $userIds = $query->pluck('id')->toArray();
        $rooms = HotelRoomTable::whereIn('user_id', $userIds)->with('user')
            ->whereHas('user', function ($query) use ($mode) {
                $query->where('mode', $mode);
            })
            ->selectRaw("id,user_id , count(id) activesession")
            ->groupBy('user_id')->get();

        return $rooms->map(function ($item, $key) {
            return [
                'Sl.No.' => $key + 1,
                'Name' => $item->user->name ?? '',
                'Email' => $item->user->email ?? '',
                'Active Sessions' => $item->activesession ?? '',
                'Created At' => $item->user->created_at->format("d/m/Y"),
            ];
        });
    }

    public function headings(): array
    {
        return ['Sl.No.', 'Name', 'Email',  'Active Sessions', "Created At"];
    }
}
