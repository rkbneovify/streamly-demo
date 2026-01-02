<?php

namespace App;

use App\Models\Channel;
use App\Models\Zipcode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalZipcode extends Model
{
    use SoftDeletes;
    protected $table = 'local_zipcodes';

    public function channel()
    {
        return $this->hasOne(Channel::class, 'id', 'channel_id');
    }


    public static function zipcodeStatusCountByUse($zipcode)
    {
        $totalCount = LocalZipcode::where('zipcode', $zipcode)
            ->count();
        $activeCount = LocalZipcode::where('zipcode', $zipcode)
            ->where('status', 1)
            ->count();

        return $activeCount . "/" . $totalCount;

    }

    // Scope for search
    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('zipcode', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('state', 'like', "%{$term}%");
            });
        }
        return $query;
    }

    // Scope for status
    public function scopeStatus($query, $status)
    {
        if (in_array($status, ['0', '1'])) {
            $query->where('status', $status);
        }
        return $query;
    }

    // Scope for ordering by created_at
    public function scopeOrderByCreated($query, $order)
    {
        $query->orderBy('created_at', $order === 'asc' ? 'asc' : 'desc');
        return $query;
    }




}

