<?php

namespace App\Http\Controllers\Admin;

use App\GracenoteScheduleProgram;
use App\Http\Controllers\Controller;
use App\Services\GracenoteService;
use App\GracenoteSource;
use App\Traits\ActionTrait;
use App\Traits\DbTransactionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminGracenoteController extends Controller
{
    use DbTransactionTrait;
    private $gracenote;
    public function __construct(GracenoteService $gracenote)
    {
        $this->gracenote = $gracenote;
    }

    public function channelByPrgSvcId($prgSvcId)
    {

        // $channel = $this->gracenote->getChannelByPrgSvcId($prgSvcId);
        // if ($channel == null) {
        //     return response()->json([
        //         "message" => "Data not found !"
        //     ]);
        // }
        // source
        // $sourceData = json_decode(json_encode($this->gracenote->getSourceByPrgSvcId($prgSvcId)));
        // $this->gracenote->updateGracenoteSourceDbByPrgSvcId($prgSvcId, $sourceData->sources->prgSvcs->prgSvc);

        // $this->gracenote->addPrograms($prgSvcId);
        // return $this->gracenote->insertProgramData($prgSvcId);

        // return $this->startAction(function () use ($prgSvcId) {
        //     $this->gracenote->insertProgramData($prgSvcId);
        // })->catch(function ($e) {
        //     return response()->json([
        //         "message" => $e->getMessage()
        //     ]);
        // })->finally(function () {
        //     return response()->json([
        //         "message" => "Data updated successfully !"
        //     ]);
        // })->execute();
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // GracenoteScheduleProgram::where('prgSvcId', $prgSvcId)->delete();
        return $this->dbTransaction(function () use ($prgSvcId) {
            $this->gracenote->insertProgramData($prgSvcId);
            toast('Data pulled successfully !', 'success')
                ->autoClose(5000)
                ->timerProgressBar();
            return redirect()->back();
        });


        // $today = Carbon::today()->toDateString();
        // $programList = collect($this->gracenote->getProgramList($prgSvcId))
        //     // ->filter(fn($item) => $item['date'] === $today)
        //     ->sortBy(function ($item) {
        //         return Carbon::createFromFormat(
        //             'Y-m-d H:i',
        //             $item['date'] . ' ' . $item['time']
        //         );
        //     })->map(function ($item) {
        //         $item['start'] = Carbon::createFromTimestamp($item['start'])->format('Y-m-d H:i:s a');
        //         $item['end'] = Carbon::createFromTimestamp($item['end'])->format('Y-m-d H:i:s a');
        //         return $item;
        //     })
        //     ->values();
        // return response()->json($programList);



    }
}
