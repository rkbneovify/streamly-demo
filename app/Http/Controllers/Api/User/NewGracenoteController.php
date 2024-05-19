<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class NewGracenoteController extends Controller
{
    private $exculdedUsers = ["jmheil@gmail.com", "llipschultz@neovix.com", "brian_marlin@yahoo.com", "qasuresh@yopmail.com", "qajalaj@yopmail.com", "pm13426@gmail.com", "nathan@neovix.com", "willis@neovix.com", "davea@neovix.com"];

    private $apiKey = '3fb9jgqzfd9jsbdw4fpt5krs';

    public function fetchGracenoteData()
    {

        // Create a Guzzle client instance
        $client = new Client();

        $startDate = '2024-05-10';
        $endDate = '2024-05-11';
        // Fetch source data
        $sourceResponse = $client->get('http://on-api.gracenote.com/v3/Sources', [
            'query' => [
                'prgSvcId' => '61340',
                'api_key' => $this->apiKey,
            ],
        ]);

        // Parse XML response for sources
        $sourceXml = $sourceResponse->getBody()->getContents();
        $sourceData = $this->xmlToJson($sourceXml);

        // Process each source
        $dataArray = [];
        foreach ($sourceData->sources->prgSvcs as $source) {
            // print_r(json_encode($source));
            // die;
            $sourceId = $source->prgSvcId;

            // Fetch schedule data for the current source
            $scheduleResponse = $client->get('http://on-api.gracenote.com/v3/Schedules', [
                'query' => [
                    'prgSvcId' => $sourceId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'api_key' => $this->apiKey,
                ],
            ]);

            // Parse XML response for schedules
            $scheduleXml = $scheduleResponse->getBody()->getContents();
            $scheduleData = $this->xmlToJson($scheduleXml);
            // print_r(json_encode($scheduleData));
            // die;
            // Process and organize schedule data as needed
            $sourceSchedule = [];
            foreach ($scheduleData->schedules->schedule->event as $event) {

                $programResponse = $client->get('http://on-api.gracenote.com/v3/Programs', [
                    'query' => [
                        'tmsId' => $event->TMSId,
                        'api_key' => $this->apiKey,
                    ],
                ]);

                // Parse XML response for schedules
                $programXml = $programResponse->getBody()->getContents();
                $programData = $this->xmlToJson($programXml);

                // Process and organize program data as needed
                $scheduleProgram = [];
                // Extract program details
                // Add the extracted program details to $scheduleProgram array
                // print_r(json_encode($programData));
                // die;
                foreach ($programData->programs as $program) {
                    // if ($program->rootId === $sourceId) {
                    $scheduleProgram[] = [
                        'title' => $program->titles->title[0]->text,
                        'description' => $program->descriptions->desc[0]->text,
                        // Add other program details as needed
                    ];
                    // }
                }
                // Extract schedule details
                $sourceSchedule[] = [
                    'TMSId' => $event->TMSId,
                    'time' => $event->time,
                    'duration' => $event->dur,
                    'quals' => $event->quals,
                    'program' => $scheduleProgram
                    // Add other schedule details as needed
                ];
            }
            // print_r($sourceSchedule);
            // die;
            // Fetch program data for the current source's schedule
            // Fetch program data for the current schedule

            // Combine source, schedule, and program data into a single array
            $dataArray[] = [
                'source_name' => $source->name,
                'prgSvcId' => $source->prgSvcId,
                'callSign' => $source->callSign,
                'enhancedCallSign' => $source->enhancedCallSign,
                'timeZone' => $source->timeZone,
                'url' => $source->URL,
                'assets' => $source->assets,
                'schedule' => $sourceSchedule,
            ];
        }

        // Return the combined array
        return $dataArray;
    }

    // Helper function to convert XML to JSON
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

    public function channelListWithGracenote(Request $request, $validate = 0)
    {
        $validator = Validator::make($request->all(), [
            'typeurl' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = JWTAuth::user();
            $urlType = $request->get('typeurl');
            $zipcode = $request->get('zipcode');
            $validate = 0;
            if (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }
            $imagePath = url("/uploads/channellogo/");

            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            $query = Channel::select(
                'channels.id',
                'channels.prgSvcId',
                DB::raw('IFNULL(channels.callSign,channels.name) as callSign'),
                DB::raw('IFNULL(channels.stationName,channels.name) as stationName'),
                'channels.image',
                "channels.$type as url",
                DB::raw("IFNULL(CONCAT('$imagePath/', `image`), CONCAT('$imagePath/', 'notfound.png')) as logo")
            )
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id');
            if ($validate) {
                // $query->addSelect('channels.zip_code');
                if (!empty($zipcode)) {
                    $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"') OR JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    // $query->where('channels.zip_code', 'like', "%$zipcode%");
                    // $query->orWhere('channels.zip_code', 'like', '%["All"]%');
                }
            }
            $query->where('channels.status', '=', '1');
            $query->groupBy('channels.id');
            $query->orderBy('channels.id', 'asc');

            $record = $query->get();


            // Step 1: Collect prgSvcId values
            $prgSvcIds = $record->pluck('prgSvcId')->filter()->all();

            // Step 2: Send a single API request
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
            $sourceData = json_decode(json_encode($this->xmlToJson($sourceXml)), true);


            // Step 3: Map API response and merge with channels data
            $channelsData = $record->map(function ($channel) use ($sourceData) {
                $prgSvcData = collect($sourceData['sources']['prgSvcs']['prgSvc'])
                    ->where('prgSvcId', $channel->prgSvcId)
                    ->first();

                if ($prgSvcData) {
                    $channel->gracenote_data = [
                        'prgSvcId' => $prgSvcData['prgSvcId'],
                        'updateId' => $prgSvcData['updateId'],
                        'updateDate' => $prgSvcData['updateDate'],
                        'name' => $prgSvcData['name'],
                        'videoQuality' => $prgSvcData['videoQuality'],
                        'timeZone' => $prgSvcData['timeZone'],
                        'callSign' => $prgSvcData['callSign'],
                        'enhancedCallSign' => $prgSvcData['enhancedCallSign'] ?? NULL,
                        'URL' => $prgSvcData['URL'] ?? NULL,
                        'assets' => $prgSvcData['assets'] ?? []
                        // Add more fields as needed
                    ];
                } else {
                    $channel->gracenote_data = [];
                }

                return $channel;
            });


            $response = [
                'success' => true,
                'data' => $channelsData,
                'message' => 'Channel with genre successfully fetched',
                'response_code' => 200,
            ];
            return response()->json($response, 200);
        }
    }
}
