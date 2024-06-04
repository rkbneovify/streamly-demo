<?php

namespace App\Http\Controllers\GracenoteCron;

use App\GracenoteScheduleProgram;
use App\GracenoteSource;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class GracenoteCronController extends Controller
{
    private $apiKey = '3fb9jgqzfd9jsbdw4fpt5krs';

    private function xmlToJson($xml)
    {
        $xmlObject = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $jsonArray = $this->xmlToArray($xmlObject);
        return json_decode(json_encode($jsonArray));
    }
    private function xmlToArray($xml)
    {
        $array = [];
        // If there are attributes, add them to the array
        if ($xml->attributes()) {
            foreach ($xml->attributes() as $key => $value) {
                if (strpos($key, '__') === 0) {
                    $array[substr($key, 2)] = (string)$value;
                } else {
                    $array[$key] = (string)$value;
                }
            }
        }

        $text = trim((string)$xml);
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
            $array = (string)$xml;
        }
        return $array;
    }

    public function gracenoteSourcesCron(Request $request)
    {
        $prgSvcIds = Channel::whereNotNull('prgSvcId')->pluck('prgSvcId')->toArray();

        // Create a Guzzle client instance
        $client = new Client();

        // Fetch source data
        $sourceResponse = $client->get('http://on-api.gracenote.com/v3/Sources', [
            'query' => [
                'prgSvcId' => implode(',', $prgSvcIds),
                'api_key' => $this->apiKey,
            ],
        ]);

        // Parse XML response for sources
        $sourceXml = $sourceResponse->getBody()->getContents();
        $sourceData = $this->xmlToJson($sourceXml);

        $dataArray = [];
        // Process each source
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GracenoteSource::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($sourceData->sources->prgSvcs->prgSvc as $source) {
            // $dataArray[] = [
            //     'stationName' => $source->name,
            //     'prgSvcId' => $source->prgSvcId,
            //     'callSign' => $source->callSign,
            //     'enhancedCallSign' => $source->enhancedCallSign ?? null,
            //     'timeZone' => $source->timeZone,
            //     'url' => $source->URL ?? null,
            //     'assets' => isset($source->assets) ? json_encode($source->assets) : "[]"
            // ];
            $gracenoteSource = new GracenoteSource();
            $gracenoteSource->stationName = $source->name;
            $gracenoteSource->prgSvcId = $source->prgSvcId;
            $gracenoteSource->callSign = $source->callSign;
            $gracenoteSource->enhancedCallSign = $source->enhancedCallSign ?? null;
            $gracenoteSource->timeZone = $source->timeZone;
            $gracenoteSource->url = $source->URL ?? null;
            $gracenoteSource->assets = $source->assets ?? [];
            $result = $gracenoteSource->save();
        }
        // $result = GracenoteSource::insert($dataArray);
        // return GracenoteSource::all();
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Gracenote Sources Data stored successfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function gracenoteScheduleProgramCron(Request $request)
    {
        $prgSvcIds = Channel::whereNotNull('prgSvcId')->pluck('prgSvcId')->toArray();

        // Create a Guzzle client instance
        $client = new Client();
        $startDate = Carbon::today()->format('Y-m-d'); //'2024-05-16';
        $endDate = Carbon::today()->addDays(2)->format('Y-m-d'); //'2024-05-17'
        $scheduleResponse = $client->get('http://on-api.gracenote.com/v3/Schedules', [
            'query' => [
                'prgSvcId' => implode(',', $prgSvcIds),
                'startDate' => $startDate,
                'endDate' => $endDate,
                'api_key' => $this->apiKey,
            ],
        ]);
        // Parse XML response for schedules
        $scheduleXml = $scheduleResponse->getBody()->getContents();
        $scheduleData = $this->xmlToJson($scheduleXml);
        // GracenoteScheduleProgram::truncate();

        $dataArray = [];
        foreach ($scheduleData->schedules->schedule as $schedule) {
            foreach ($schedule->event as $event) {
                $programResponse = $client->get('http://on-api.gracenote.com/v3/Programs', [
                    'query' => [
                        'tmsId' => $event->TMSId,
                        'api_key' => $this->apiKey,
                    ],
                ]);

                // Parse XML response for programs
                $programXml = $programResponse->getBody()->getContents();
                $programData = $this->xmlToJson($programXml);

                $program = $programData->programs->program;

                // Extract schedule details
                $time = $event->time;
                $duration = $event->dur;
                $timezone = 'UTC';
                $today = Carbon::today($timezone);

                $startDateTime = Carbon::parse($today->toDateString() . ' ' . $time, $timezone);

                $durationInterval = new \DateInterval($duration);
                $endDateTime = $startDateTime->copy()->add($durationInterval);

                $startTimestamp = $startDateTime->timestamp;
                $endTimestamp = $endDateTime->timestamp;

                $dataArray[] = [
                    'prgSvcId' => $schedule->prgSvcId,
                    'date' => $schedule->date,
                    'TMSId' => $event->TMSId,
                    'rootId' => $event->rootId,
                    'time' => $event->time,
                    'duration' => $event->dur,
                    'start' => $startTimestamp,
                    'end' => $endTimestamp,
                    'quals' => $event->quals ?? null,
                    // 'program' => $scheduleProgram
                    'title' => is_array($program->titles->title) ? $program->titles->title[0]->text : null,
                    'description' => isset($program->descriptions->desc)
                        ? (is_array($program->descriptions->desc)
                            ? ($program->descriptions->desc[0]->text ?? null)
                            : ($program->descriptions->desc->text ?? null))
                        : null,
                    'assets' => isset($program->assets) ? json_encode($program->assets) : "[]"
                ];
            }
            // if (!empty($dataArray)) {
            //     $result = GracenoteScheduleProgram::insert($dataArray);
            //     if ($result) {
            //         $dataArray = [];
            //     }
            // }
        }
        // return $dataArray;
        if (!empty($dataArray)) {
            GracenoteScheduleProgram::truncate();
            $chunkSize = 500; // Adjust the chunk size as needed
            $chunks = array_chunk($dataArray, $chunkSize);

            foreach ($chunks as $chunk) {
                $result = GracenoteScheduleProgram::insert($chunk);
            }
            // $result = GracenoteScheduleProgram::insert($dataArray);
            DB::table('gracenote_schedule_program')->update(['created_at' => now()]);
            DB::table('gracenote_schedule_program')->update(['updated_at' => now()]);
        }
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Gracenote Schedule Program Data stored successfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Something went wrong!'
            ], 500);
        }
        // return $dataArray;
    }
}
