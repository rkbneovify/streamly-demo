<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GracenoteService;
use App\GracenoteSource;
use App\Traits\ActionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminGracenoteController extends Controller
{
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


        $today = Carbon::today()->toDateString();
        $programList = collect($this->gracenote->getProgramList($prgSvcId))
            // ->filter(fn($item) => $item['date'] === $today)
            ->sortBy(function ($item) {
                return Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $item['date'] . ' ' . $item['time']
                );
            })
            ->values();
        return response()->json($programList);



    }
}
