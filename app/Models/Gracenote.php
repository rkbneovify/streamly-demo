<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $StationName StationName
@property varchar $CallSign CallSign
@property varchar $Rating Rating
@property varchar $EventType EventType
@property int $EventID EventID
@property int $StartDate StartDate
@property int $StartTime StartTime
@property datetime $StartDateTime StartDateTime
@property int $RunLenHour RunLenHour
@property int $RunLenMinute RunLenMinute
@property int $EventTitle EventTitle
@property varchar $EventDescription EventDescription
@property varchar $UnixStartDate UnixStartDate
@property varchar $TimeZoneName TimeZoneName
@property int $EndDate EndDate
@property datetime $EndaDateTime EndaDateTime
   
 */
class Gracenote extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'gracenote';

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'StationName',
'CallSign',
'Rating',
'EventType',
'EventID',
'StartDate',
'StartTime',
'StartDateTime',
'RunLenHour',
'RunLenMinute',
'EventTitle',
'EventDescription',
'UnixStartDate',
'TimeZoneName',
'EndDate',
'EndaDateTime'];

    /**
    * Date time columns.
    */
    protected $dates=['StartDateTime',
'EndaDateTime'];


public function gracenotechannel()
    {
        return $this->belongsTo(Gracenotechannel::class,'CallSign');
    }

}