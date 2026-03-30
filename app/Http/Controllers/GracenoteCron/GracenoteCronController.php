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
use Illuminate\Support\Facades\Log;

class GracenoteCronController extends Controller
{
    private $apiKey = '3fb9jgqzfd9jsbdw4fpt5krs';

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
        ini_set('memory_limit', '256M');

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

    public function gracenoteScheduleProgramCron1(Request $request)
    {
        $prgSvcIds = Channel::whereNotNull('prgSvcId')->pluck('prgSvcId')->toArray();

        // Create a Guzzle client instance
        $client = new Client();
        $startDate = Carbon::today()->format('Y-m-d');
        $endDate = Carbon::today()->addDays(2)->format('Y-m-d');
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

        $dataArray = [];
        $tmsIds = [];

        foreach ($scheduleData->schedules->schedule as $schedule) {
            foreach ($schedule->event as $event) {
                $tmsIds[] = $event->TMSId;

                // Extract schedule details to be used later
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
                    'title' => null,
                    'description' => null,
                    'assets' => "[]"
                ];
            }
        }

        // Batch request for programs by TMSIds
        $programResponse = $client->get('http://on-api.gracenote.com/v3/Programs', [
            'query' => [
                'tmsId' => implode(',', $tmsIds),
                'api_key' => $this->apiKey,
            ],
        ]);

        // Parse XML response for programs
        $programXml = $programResponse->getBody()->getContents();
        $programData = $this->xmlToJson($programXml);
        $programs = $programData->programs->program;

        // Create a map for quick lookup
        $programMap = [];
        foreach ($programs as $program) {
            $programMap[$program->TMSId] = $program;
        }

        // Map program details to events
        foreach ($dataArray as &$data) {
            if (isset($programMap[$data['TMSId']])) {
                $program = $programMap[$data['TMSId']];
                $data['title'] = is_array($program->titles->title) ? $program->titles->title[0]->text : null;
                $data['description'] = isset($program->descriptions->desc)
                    ? (is_array($program->descriptions->desc)
                        ? ($program->descriptions->desc[0]->text ?? null)
                        : ($program->descriptions->desc->text ?? null))
                    : null;
                $data['assets'] = isset($program->assets) ? json_encode($program->assets) : "[]";
            }
        }

        if (!empty($dataArray)) {
            GracenoteScheduleProgram::truncate();
            $chunkSize = 500; // Adjust the chunk size as needed
            $chunks = array_chunk($dataArray, $chunkSize);

            foreach ($chunks as $chunk) {
                GracenoteScheduleProgram::insert($chunk);
            }

            DB::table('gracenote_schedule_program')->update([
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Gracenote Schedule Program Data stored successfully!'
        ], 200);
    }
    public function gracenoteScheduleProgramCron2(Request $request)
    {
        // ini_set('memory_limit', '512M');
        // phpinfo();
        // die;
        $prgSvcIds = Channel::whereNotNull('prgSvcId')->pluck('prgSvcId')->toArray();

        // Create a Guzzle client instance
        $client = new Client();
        $startDate = Carbon::today()->format('Y-m-d');
        $endDate = Carbon::today()->addDays(2)->format('Y-m-d');
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

        $dataArray = [];
        $tmsIds = [];

        foreach ($scheduleData->schedules->schedule as $schedule) {
            foreach ($schedule->event as $event) {
                $tmsIds[] = $event->TMSId;

                // Extract schedule details to be used later
                $time = $event->time;
                $duration = $event->dur;
                $timezone = 'UTC';
                $today = Carbon::today($timezone);

                $startDateTime = Carbon::parse($schedule->date . ' ' . $time, $timezone);
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
                    'title' => null,
                    'description' => null,
                    'assets' => "[]"
                ];
            }
        }
        unset($scheduleXml, $scheduleData); // Clear memory
        // Batch the TMSIds into smaller groups to avoid the 414 error
        $chunkedTmsIds = array_chunk($tmsIds, 50); // Adjust the chunk size as needed
        $programMap = [];

        foreach ($chunkedTmsIds as $chunk) {
            $programResponse = $client->get('http://on-api.gracenote.com/v3/Programs', [
                'query' => [
                    'tmsId' => implode(',', $chunk),
                    'api_key' => $this->apiKey,
                ],
            ]);

            // Parse XML response for programs
            $programXml = $programResponse->getBody()->getContents();
            $programData = $this->xmlToJson($programXml);

            if (isset($programData->programs)) {
                // Extract programs from standard or nested structure
                $programList = $programData->programs->program ?? $programData->programs->stdClass->program ?? null;

                if (is_array($programList)) {
                    foreach ($programList as $program) {
                        if (isset($program->TMSId)) {
                            $programMap[$program->TMSId] = $program;
                        } else {
                            Log::warning('Program object missing TMSId:', (array) $program);
                        }
                    }
                } elseif (is_object($programList)) {
                    if (isset($programList->TMSId)) {
                        $programMap[$programList->TMSId] = $programList;
                    } else {
                        Log::warning('Program object missing TMSId:', (array) $programList);
                    }
                } else {
                    Log::error('Invalid program data structure: Missing or invalid program list', (array) $programData);
                }
            } else {
                Log::error('Invalid program data structure: Missing programs property', (array) $programData);
            }
        }
        unset($programXml, $programData); // Clear memory
        // Map program details to events
        foreach ($dataArray as $index => &$data) {
            if (isset($programMap[$data['TMSId']])) {
                $program = $programMap[$data['TMSId']];
                $data['title'] = is_array($program->titles->title) ? $program->titles->title[0]->text : null;
                $data['description'] = isset($program->descriptions->desc)
                    ? (is_array($program->descriptions->desc)
                        ? ($program->descriptions->desc[0]->text ?? null)
                        : ($program->descriptions->desc->text ?? null))
                    : null;
                $data['assets'] = isset($program->assets) ? json_encode($program->assets) : "[]";
            }
            // Free memory periodically
            if ($index % 1000 == 0) {
                gc_collect_cycles();
            }
        }
        unset($programMap); // Clear memory

        if (!empty($dataArray)) {
            GracenoteScheduleProgram::truncate();
            $chunkSize = 400; // Adjust the chunk size as needed
            $chunks = array_chunk($dataArray, $chunkSize);

            foreach ($chunks as $chunk) {
                GracenoteScheduleProgram::insert($chunk);
            }

            DB::table('gracenote_schedule_program')->update([
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Gracenote Schedule Program Data stored successfully!'
        ], 200);
    }

    public function gracenoteScheduleProgramCron3(Request $request)
    {
        try {
            ini_set('memory_limit', '512M');


            set_time_limit(0);

            $prgSvcIds = Channel::whereNotNull('prgSvcId')
                ->pluck('prgSvcId')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (empty($prgSvcIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No prgSvcId found.'
                ], 404);
            }

            $client = new Client([
                'timeout' => 120,
                'connect_timeout' => 30,
            ]);

            $startDate = now('UTC')->format('Y-m-d');
            $endDate = now('UTC')->addDays(2)->format('Y-m-d');
            $now = now();

            // 1. Fetch schedules
            $scheduleResponse = $client->get('http://on-api.gracenote.com/v3/Schedules', [
                'query' => [
                    'prgSvcId' => implode(',', $prgSvcIds),
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'api_key' => $this->apiKey,
                ],
            ]);

            $scheduleXml = $scheduleResponse->getBody()->getContents();
            $scheduleData = $this->xmlToJson($scheduleXml);

            unset($scheduleXml);

            $dataArray = [];
            $tmsIds = [];

            if (!isset($scheduleData->schedules->schedule)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No schedule data found.'
                ], 404);
            }

            $schedules = is_array($scheduleData->schedules->schedule)
                ? $scheduleData->schedules->schedule
                : [$scheduleData->schedules->schedule];

            foreach ($schedules as $schedule) {
                if (!isset($schedule->event)) {
                    continue;
                }

                $events = is_array($schedule->event) ? $schedule->event : [$schedule->event];

                foreach ($events as $event) {
                    if (empty($event->TMSId) || empty($schedule->date) || empty($event->time) || empty($event->dur)) {
                        continue;
                    }

                    try {
                        $startDateTime = Carbon::parse($schedule->date . ' ' . $event->time, 'UTC');
                        $endDateTime = (clone $startDateTime)->add(new \DateInterval($event->dur));
                    } catch (\Throwable $e) {
                        Log::warning('Invalid schedule event skipped', [
                            'prgSvcId' => $schedule->prgSvcId ?? null,
                            'TMSId' => $event->TMSId ?? null,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }

                    $tmsIds[] = $event->TMSId;

                    $dataArray[] = [
                        'prgSvcId' => $schedule->prgSvcId ?? null,
                        'date' => $schedule->date,
                        'TMSId' => $event->TMSId,
                        'rootId' => $event->rootId ?? null,
                        'time' => $event->time,
                        'duration' => $event->dur,
                        'start' => $startDateTime->timestamp,
                        'end' => $endDateTime->timestamp,
                        'quals' => isset($event->quals) ? json_encode($event->quals) : null,
                        'title' => null,
                        'description' => null,
                        'assets' => '[]',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            unset($scheduleData);

            if (empty($dataArray)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No event data found.'
                ], 404);
            }

            // 2. Deduplicate TMS IDs before calling program API
            $tmsIds = array_values(array_unique($tmsIds));

            // 3. Fetch program details in chunks
            $programMap = [];

            foreach (array_chunk($tmsIds, 50) as $chunk) {
                $programResponse = $client->get('http://on-api.gracenote.com/v3/Programs', [
                    'query' => [
                        'tmsId' => implode(',', $chunk),
                        'api_key' => $this->apiKey,
                    ],
                ]);

                $programXml = $programResponse->getBody()->getContents();
                $programData = $this->xmlToJson($programXml);

                unset($programXml);

                if (!isset($programData->programs->program)) {
                    unset($programData);
                    continue;
                }

                $programs = is_array($programData->programs->program)
                    ? $programData->programs->program
                    : [$programData->programs->program];

                foreach ($programs as $program) {
                    if (!empty($program->TMSId)) {
                        $programMap[$program->TMSId] = $program;
                    }
                }

                unset($programData);
                gc_collect_cycles();
            }

            // 4. Map program data
            foreach ($dataArray as &$row) {
                if (!isset($programMap[$row['TMSId']])) {
                    continue;
                }

                $program = $programMap[$row['TMSId']];

                // title
                if (isset($program->titles->title)) {
                    if (is_array($program->titles->title)) {
                        $row['title'] = $program->titles->title[0]->text ?? null;
                    } else {
                        $row['title'] = $program->titles->title->text ?? null;
                    }
                }

                // description
                if (isset($program->descriptions->desc)) {
                    if (is_array($program->descriptions->desc)) {
                        $row['description'] = $program->descriptions->desc[0]->text ?? null;
                    } else {
                        $row['description'] = $program->descriptions->desc->text ?? null;
                    }
                }

                // assets
                $row['assets'] = isset($program->assets) ? json_encode($program->assets) : '[]';
            }
            unset($row, $programMap);

            // 5. Store in DB
            DB::transaction(function () use ($dataArray) {
                GracenoteScheduleProgram::truncate();

                foreach (array_chunk($dataArray, 250) as $chunk) {
                    GracenoteScheduleProgram::insert($chunk);
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Gracenote Schedule Program Data stored successfully!'
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Gracenote cron failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function programBySinglePrgSvcId(Request $request, $prgSvcId)
    {
        try {
            ini_set('memory_limit', '512M');
            set_time_limit(0);

            if (empty($prgSvcId)) {
                toast("prgSvcId is required.", 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();


            }

            $client = new Client([
                'timeout' => 120,
                'connect_timeout' => 30,
            ]);

            $startDate = now('UTC')->format('Y-m-d');
            $endDate = now('UTC')->addDays(2)->format('Y-m-d');
            $now = now();

            // 1. Fetch schedules for single prgSvcId
            $scheduleResponse = $client->get('http://on-api.gracenote.com/v3/Schedules', [
                'query' => [
                    'prgSvcId' => $prgSvcId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'api_key' => $this->apiKey,
                ],
            ]);

            $scheduleXml = $scheduleResponse->getBody()->getContents();
            $scheduleData = $this->xmlToJson($scheduleXml);

            unset($scheduleXml);

            if (!isset($scheduleData->schedules->schedule)) {

                toast("No schedule data found.", 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();
            }

            $schedules = is_array($scheduleData->schedules->schedule)
                ? $scheduleData->schedules->schedule
                : [$scheduleData->schedules->schedule];

            $dataArray = [];
            $tmsIds = [];

            foreach ($schedules as $schedule) {
                if (!isset($schedule->event)) {
                    continue;
                }

                $events = is_array($schedule->event) ? $schedule->event : [$schedule->event];

                foreach ($events as $event) {
                    if (empty($event->TMSId) || empty($schedule->date) || empty($event->time) || empty($event->dur)) {
                        continue;
                    }

                    try {
                        $startDateTime = Carbon::parse($schedule->date . ' ' . $event->time, 'UTC');
                        $endDateTime = (clone $startDateTime)->add(new \DateInterval($event->dur));
                    } catch (\Throwable $e) {
                        Log::warning('Invalid schedule event skipped', [
                            'prgSvcId' => $schedule->prgSvcId ?? $prgSvcId,
                            'TMSId' => $event->TMSId ?? null,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }

                    $tmsIds[] = $event->TMSId;

                    $dataArray[] = [
                        'prgSvcId' => $schedule->prgSvcId ?? $prgSvcId,
                        'date' => $schedule->date,
                        'TMSId' => $event->TMSId,
                        'rootId' => $event->rootId ?? null,
                        'time' => $event->time,
                        'duration' => $event->dur,
                        'start' => $startDateTime->timestamp,
                        'end' => $endDateTime->timestamp,
                        'quals' => isset($event->quals) ? json_encode($event->quals) : null,
                        'title' => null,
                        'description' => null,
                        'assets' => '[]',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            unset($scheduleData);

            if (empty($dataArray)) {

                toast("No event data found.", 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();

            }

            // 2. Unique TMS IDs
            $tmsIds = array_values(array_unique($tmsIds));

            // 3. Fetch program details
            $programMap = [];

            foreach (array_chunk($tmsIds, 50) as $chunk) {
                $programResponse = $client->get('http://on-api.gracenote.com/v3/Programs', [
                    'query' => [
                        'tmsId' => implode(',', $chunk),
                        'api_key' => $this->apiKey,
                    ],
                ]);

                $programXml = $programResponse->getBody()->getContents();
                $programData = $this->xmlToJson($programXml);

                unset($programXml);

                if (!isset($programData->programs->program)) {
                    unset($programData);
                    continue;
                }

                $programs = is_array($programData->programs->program)
                    ? $programData->programs->program
                    : [$programData->programs->program];

                foreach ($programs as $program) {
                    if (!empty($program->TMSId)) {
                        $programMap[$program->TMSId] = $program;
                    }
                }

                unset($programData);
                gc_collect_cycles();
            }

            // 4. Map program data
            foreach ($dataArray as &$row) {
                if (!isset($programMap[$row['TMSId']])) {
                    continue;
                }

                $program = $programMap[$row['TMSId']];

                if (isset($program->titles->title)) {
                    if (is_array($program->titles->title)) {
                        $row['title'] = $program->titles->title[0]->text ?? null;
                    } else {
                        $row['title'] = $program->titles->title->text ?? null;
                    }
                }

                if (isset($program->descriptions->desc)) {
                    if (is_array($program->descriptions->desc)) {
                        $row['description'] = $program->descriptions->desc[0]->text ?? null;
                    } else {
                        $row['description'] = $program->descriptions->desc->text ?? null;
                    }
                }

                $row['assets'] = isset($program->assets) ? json_encode($program->assets) : '[]';
            }
            unset($row, $programMap);

            // 5. Optional: delete old data only for this prgSvcId, then insert fresh
            DB::transaction(function () use ($prgSvcId, $dataArray) {
                GracenoteScheduleProgram::where('prgSvcId', $prgSvcId)->delete();

                foreach (array_chunk($dataArray, 250) as $chunk) {
                    GracenoteScheduleProgram::insert($chunk);
                }
            });


            toast("Program data stored successfully !", 'success')
                ->autoClose(3000)
                ->timerProgressBar();
            return redirect()->back();



        } catch (\Throwable $e) {

            toast("Program data stored failed", 'error')
                ->autoClose(3000)
                ->timerProgressBar();
            return redirect()->back();


        }
    }
}
