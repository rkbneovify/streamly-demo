<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $email email
@property varchar $occurences occurences
@property varchar $bug_severity bug severity
@property varchar $platform platform
@property text $message message
@property timestamp $created_at created at
@property timestamp $updated_at updated at

 */
class BugReporting extends Model
{

    /**
     * Database table name
     */
    protected $table = 'bug_reporting';

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'email',
        'occurences',
        'bug_severity',
        'platform',
        'message'
    ];

    /**
     * Date time columns.
     */
    protected $dates = ['updated_at', 'created_at'];

    public function files(){
        return $this->hasMany(File::class, 'bug_report_id', 'id');
    }

      public function scopeSearch($query, $txt)
    {
        if ($txt != null) {
            $query->where('email', 'like', '%' . $txt . '%')
            ->orWhere('name', 'like', '%' . $txt . '%');
        }
    }
    public function scopeFilterDateOrder($query, ?string $date, string $column = 'created_at')
    {
        if (!$date) {
            return $query;
        }


        return $query->orderBy($column, $date);
    }



}