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



        $source = $this->gracenote->getSourceByPrgSvcId($prgSvcId);
        $this->gracenote->updateGracenoteSourceDbByPrgSvcId($prgSvcId, $source);
        return $this->dbTransaction(function () use ($prgSvcId) {
            $this->gracenote->getProgramList($prgSvcId);
            toast('Data pulled successfully !', 'success')
                ->autoClose(5000)
                ->timerProgressBar();
            return redirect()->back();
        });




        return response()->json(collect($this->gracenote->getProgramList($prgSvcId)));

    }
}
