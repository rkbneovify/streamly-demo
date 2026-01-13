<?php

namespace App\Services;

use App\GracenoteScheduleProgram;
use App\GracenoteSource;
use App\Models\Channel;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\ErrorHandler\Throwing;

class GracenoteService
{
    private $apiKey = '3fb9jgqzfd9jsbdw4fpt5krs';
    private $baseUrl = 'http://on-api.gracenote.com/v3';
    private function xmlToJson($xml)
    {
        // ini_set('memory_limit', '256M');
        $xmlObject = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $jsonArray = $this->xmlToArray($xmlObject);
        return json_decode(json_encode($jsonArray));
    }

    private function xmlToArray($xml)
    {
        // ini_set('memory_limit', '256M');

        $array = [];
        // If there are attributes, add them to the array
        if ($xml->attributes()) {
            foreach ($xml->attributes() as $key => $value) {
                if (strpos($key, '__') === 0) {
                    $array[substr($key, 2)] = (string) $value;
                } else {
                    $array[$key] = (string) $value;
                }
            }
        }

        $text = trim((string) $xml);
        if (!empty($text)) {
            $array['text'] = $text;
        }
        // If there are child elements, recursively convert them to arrays
        if ($xml->children()) {
            foreach ($xml->children() as $child) {
                // If the child has siblings of the same name, convert them to an array
                if ($xml->{$child->getName()}->count() > 1) {
                    $array[$child->getName()][] = $this->xmlToArray($child);
                } else {
                    $array[$child->getName()] = $this->xmlToArray($child);
                }
            }
        } else {
            // If the element has a value, add it to the array
            $array = (string) $xml;
        }
        return $array;
    }


    public function getChannelByPrgSvcId($prgSvcId)
    {
        return Channel::whereNotNull('prgSvcId')->where('prgSvcId', $prgSvcId)->first();
    }

    public function getSourceByPrgSvcId($prgSvcId)
    {
        $client = new Client();

        // Fetch source data
        $sourceResponse = $client->get($this->baseUrl . '/Sources', [
            'query' => [
                'prgSvcId' => $prgSvcId,
                'api_key' => $this->apiKey,
            ],
        ]);

        // Parse XML response for sources
        $sourceXml = $sourceResponse->getBody()->getContents();
        return $this->xmlToJson($sourceXml);
    }

    public function updateGracenoteSourceDbByPrgSvcId($prgSvcId, $source)
    {

        DB::beginTransaction();
        try {


            $gracenoteSource = GracenoteSource::where('prgSvcId', $prgSvcId)
                ->first();
            if ($gracenoteSource != null) {
                $gracenoteSource->stationName = $source->name;
                $gracenoteSource->callSign = $source->callSign;
                $gracenoteSource->enhancedCallSign = $source->enhancedCallSign ?? null;
                $gracenoteSource->timeZone = $source->timeZone;
                $gracenoteSource->url = $source->URL ?? null;
                $gracenoteSource->assets = $source->assets ?? [];
                $gracenoteSource->save();
                $gracenoteSource->touch();
            } else {
                $gracenoteSource = new GracenoteSource();
                $gracenoteSource->prgSvcId = $source->prgSvcId;
                $gracenoteSource->stationName = $source->name;
                $gracenoteSource->callSign = $source->callSign;
                $gracenoteSource->enhancedCallSign = $source->enhancedCallSign ?? null;
                $gracenoteSource->timeZone = $source->timeZone;
                $gracenoteSource->url = $source->URL ?? null;
                $gracenoteSource->assets = $source->assets ?? [];
                $gracenoteSource->save();
            }

            DB::commit();


        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $th->getMessage();

        }
    }
    private function getScheduleByPrgSvcId($prgSvcId)
    {
        $client = new Client();


        $startDate = Carbon::now('UTC')
            ->subDay()
            ->format('Y-m-d\TH:i:s\Z');

        $endDate = Carbon::now('UTC')
            ->addDays(2)
            ->endOfDay()
            ->format('Y-m-d\TH:i:s\Z');


        $scheduleResponse = $client->get($this->baseUrl . '/Schedules', [
            'query' => [
                'prgSvcId' => $prgSvcId,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'api_key' => $this->apiKey,
            ],
        ]);

        // Parse XML response for sources
        $sourceXml = $scheduleResponse->getBody()->getContents();
        return $this->xmlToJson($sourceXml);
    }

    private function getPrograms($tmsIdList)
    {
        $client = new Client();

        // Fetch source data
        $sourceResponse = $client->get($this->baseUrl . '/Programs', [
            'query' => [
                'tmsId' => $tmsIdList,
                'api_key' => $this->apiKey,
            ],
        ]);

        // Parse XML response for sources
        $sourceXml = $sourceResponse->getBody()->getContents();
        return $this->xmlToJson($sourceXml);
    }

    private function formatProgramData($program)
    {




        $time = $program->event->time;
        $duration = $program->event->dur;
        $timezone = 'UTC';
        $today = Carbon::today($timezone);
        $startDateTime = Carbon::parse($today->toDateString() . ' ' . $time, $timezone);

        $durationInterval = new \DateInterval($duration);
        $endDateTime = $startDateTime->copy()->add($durationInterval);




        return [
            'prgSvcId' => data_get($program, 'event.schedule.prgSvcId'),
            'date' => data_get($program, 'event.schedule.date'),
            'TMSId' => data_get($program, 'event.TMSId'),
            'rootId' => data_get($program, 'event.rootId'),
            'time' => data_get($program, 'event.time'),
            'duration' => data_get($program, 'event.dur'),
            'start' => $startDateTime->timestamp,
            'end' => $endDateTime->timestamp,
            'quals' => data_get($program, 'event.quals'),
            'title' => data_get($program, 'titles.title.0.text'),
            'description' => data_get($program, 'descriptions.desc.0.text')
                ?? data_get($program, 'descriptions.desc.text'),
            'assets' => json_encode($program->assets ?? []),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    public function getProgramList($prgSvcId)
    {
        $scheduleData = json_decode(json_encode($this->getScheduleByPrgSvcId($prgSvcId)));
        $eventList = [];
        $programList = [];
        $scheduleData = collect($scheduleData->schedules->schedule);

        foreach ($scheduleData as $schedule) {

            foreach ($schedule->event as $event) {

                $event->schedule = [
                    'prgSvcId' => $schedule->prgSvcId,
                    'date' => $schedule->date
                ];

                $eventList[] = $event;
            }



        }
        $events = collect($eventList);
        $TMSIdListPerSchedule = $events->pluck('TMSId')->toArray();
        $allPrograms = []; // accumulator

        // program data in chunks
        foreach (array_chunk($TMSIdListPerSchedule, 50) as $items) {
            $response = $this->getPrograms(implode(",", $items));
            $programData = $response->programs->program;

            if (!empty($programData)) {
                $allPrograms = array_merge(
                    $allPrograms,
                    (array) $programData
                );
            }
        }

        foreach ($events->toArray() as $event) {
            foreach ($allPrograms as $program) {
                if ($event->TMSId == $program->TMSId) {
                    $program->event = $event;
                }
            }
        }

        foreach ($allPrograms as $item) {
            $programList[] = $this->formatProgramData($item);
        }

        return $programList;

    }

    public function insertProgramData($prgSvcId)
    {
        $today = Carbon::today()->toDateString();
        $programList = collect($this->getProgramList($prgSvcId))
            // ->filter(fn($item) => $item['date'] === $today)
            ->sortBy(function ($item) {
                return Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $item['date'] . ' ' . $item['time']
                );
            })
            ->toArray();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GracenoteScheduleProgram::where('prgSvcId', $prgSvcId)->delete();

        foreach (array_chunk($programList, 500) as $chunk) {
            GracenoteScheduleProgram::insert($chunk);

        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }



}