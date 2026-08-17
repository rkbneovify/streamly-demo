<style>
    /* =========================================================
   JSON VIEWER
========================================================= */

.json-viewer {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 12px 14px;

    font-family:
        "SFMono-Regular",
        Consolas,
        "Liberation Mono",
        Menlo,
        monospace;

    font-size: 13px;
    line-height: 1.7;

    overflow-x: auto;
    color: #212529;
}

/* =========================================================
   TITLE
========================================================= */

.json-title {
    background: #f1f3f5;
    border: 1px solid #dee2e6;
    border-bottom: 0;

    padding: 8px 12px;

    border-radius: 6px 6px 0 0;

    font-size: 13px;
}

/* =========================================================
   ROOT
========================================================= */

.json-root {
    min-width: 300px;
}

/* =========================================================
   CONTAINER
========================================================= */

.json-container {
    margin-left: 2px;
}

/* =========================================================
   JSON ROW
========================================================= */

.json-row {
    position: relative;

    min-height: 24px;

    padding: 1px 5px;

    border-radius: 4px;
}

/* =========================================================
   HOVER
========================================================= */

.json-row:hover {
    background-color: #eef1f4;
}

/* =========================================================
   KEY
========================================================= */

.json-key {
    color: #922;
    font-weight: 500;
}

/* =========================================================
   COLON
========================================================= */

.json-colon {
    color: #333;
    margin-right: 5px;
}

/* =========================================================
   STRING
========================================================= */

.json-string {
    color: #008000;
}

/* =========================================================
   NUMBER
========================================================= */

.json-number {
    color: #005cc5;
}

/* =========================================================
   BOOLEAN
========================================================= */

.json-boolean {
    color: #7a3e9d;
    font-weight: 500;
}

/* =========================================================
   NULL
========================================================= */

.json-null {
    color: #808080;
    font-style: italic;
}

/* =========================================================
   EMPTY ARRAY / OBJECT
========================================================= */

.json-empty {
    color: #777;
}

/* =========================================================
   BRACKETS
========================================================= */

.json-bracket {
    color: #555;
    font-weight: 600;
}

/* =========================================================
   NESTED JSON
========================================================= */

.json-nested {
    margin-left: 18px;

    border-left: 1px solid #d6d6d6;

    padding-left: 8px;
}

/* =========================================================
   DETAILS
========================================================= */

.json-viewer details {
    margin: 0;
}

.json-viewer summary {
    cursor: pointer;

    list-style: none;

    user-select: none;

    min-height: 24px;

    padding: 1px 5px;

    border-radius: 4px;
}

.json-viewer summary:hover {
    background-color: #eef1f4;
}

/* Remove browser default arrow */

.json-viewer summary::-webkit-details-marker {
    display: none;
}

/* =========================================================
   CUSTOM ARROW
========================================================= */

.json-viewer summary::before {
    content: "▶";

    display: inline-block;

    width: 16px;

    color: #6c757d;

    font-size: 9px;

    transition: transform 0.1s ease;
}

.json-viewer details[open] > summary::before {
    content: "▼";
}

/* =========================================================
   TYPE LABEL
========================================================= */

.json-type {
    color: #999;

    font-size: 11px;

    margin-left: 5px;
}

/* =========================================================
   CHANGED VALUE
========================================================= */

.json-changed {
    background-color: #fff3cd;

    border: 1px solid #ffda6a;

    border-radius: 4px;

    padding: 2px 6px;

    margin: 1px 0;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .json-viewer {
        font-size: 12px;
    }

}
</style>

<form id="tbl-form" method="POST" action="{{ route('admin.users.bulk.action') }}">
     @csrf
     @if (count($data) > 0)
         <div class="px-5">

             @foreach ($data as $key => $item)
                 @if ($key > 0)
                     <hr>
                 @endif

                 <div class="col mb-3">


                     <b>
                         {{ \Carbon\Carbon::parse($item->created_at)->setTimezone(config('app.timezone'))->format('d/m/y h:i:s A') }}
                     </b>

                     <div class="row">
                         @switch($item->event_type)
                             @case(\App\Enums\ActivityEventType::CREATED)
                                 <strong class="text-success">{{ $item->event }}
                                     (<span>{{ $item->event_type }}</span>)
                                 </strong>
                             @break

                             @case(\App\Enums\ActivityEventType::UPDATED)
                                 <strong class="text-info">{{ $item->event }}
                                     (<span>{{ $item->event_type }}</span>)
                                 </strong>
                             @break

                             @case(\App\Enums\ActivityEventType::STATUSDISABLE)
                                 <strong class="text-danger">{{ $item->event }}
                                     (<span>{{ $item->event_type }}</span>)
                                 </strong>
                             @break

                             @case(\App\Enums\ActivityEventType::STATUSENABLE)
                                 <strong class="text-success">{{ $item->event }}
                                     (<span>{{ $item->event_type }}</span>)
                                 </strong>
                             @break

                             @case(\App\Enums\ActivityEventType::DELETED)
                                 <strong class="text-danger">{{ $item->event }}
                                     (<span>{{ $item->event_type }}</span>)
                                 </strong>
                             @break

                             @case(\App\Enums\ActivityEventType::REMOVED)
                                 <strong class="text-danger">{{ $item->event }}
                                     (<span>{{ $item->event_type }}</span>)
                                 </strong>
                             @break

                             @default
                                 <strong>{{ $item->event }}
                                     (<span>{{ $item->event_type }}</span>)
                                 </strong>
                         @endswitch


                     </div>
                     @if ($item->effectedUser)
                         <div class="row">
                             <b>Effected User : &nbsp;&nbsp; </b>{{ $item->effectedUser->email }}

                         </div>
                     @endif
                     <div class="row">
                         {!! $item->description !!}

                     </div>

                     {{-- <div class="row">
                         @if ($item->properties)
                             <details class="mt-2">
                                 <summary class="text-primary">View details</summary>
                                 <div class="row">
                                     @if ($item->event_type == \App\Enums\ActivityEventType::UPDATED)
                                         @php
                                             $normalizeData = function ($data) {
                                                 if (is_array($data)) {
                                                     return $data;
                                                 }

                                                 if (is_object($data)) {
                                                     return (array) $data;
                                                 }

                                                 if (is_string($data)) {
                                                     $decoded = json_decode($data, true);
                                                     if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                         return $decoded;
                                                     }
                                                 }

                                                 return [];
                                             };

                                             $oldData = $normalizeData($item->properties['old_data'] ?? []);
                                             $newData = $normalizeData($item->properties['new_data'] ?? []);
                                             $diffKeys = array_values(
                                                 array_filter(
                                                     array_unique(
                                                         array_merge(array_keys($oldData), array_keys($newData)),
                                                     ),
                                                     fn($diffKey) => ($oldData[$diffKey] ?? null) !==
                                                         ($newData[$diffKey] ?? null),
                                                 ),
                                             );
                                         @endphp

                                         @php
                                             $allKeys = array_values(
                                                 array_unique(array_merge(array_keys($oldData), array_keys($newData))),
                                             );
                                             $formatJsonValue = function ($value) {
                                                 $encoded = json_encode(
                                                     $value,
                                                     JSON_PRETTY_PRINT |
                                                         JSON_UNESCAPED_UNICODE |
                                                         JSON_UNESCAPED_SLASHES,
                                                 );

                                                 return $encoded === false ? 'null' : $encoded;
                                             };
                                         @endphp
                                         <div class="col-6">
                                             <pre class="text-wrap bg-light p-2 small mt-1 rounded"><b>Old data</b>
{
@foreach ($allKeys as $index => $allKey)
<span class="d-block {{ in_array($allKey, $diffKeys, true) ? 'border rounded px-1 border-warning' : '' }}" style="{{ in_array($allKey, $diffKeys, true) ? 'background-color: #fff3cd;' : '' }}">"{{ $allKey }}": {{ $formatJsonValue($oldData[$allKey] ?? null) }}@if ($index < count($allKeys) - 1)
,
@endif
</span>
@endforeach}</pre>
                                         </div>
                                         <div class="col-6">
                                             <pre class="text-wrap bg-light p-2 small mt-1 rounded"><b>New data</b>
{
@foreach ($allKeys as $index => $allKey)
<span class="d-block {{ in_array($allKey, $diffKeys, true) ? 'border rounded px-1 border-warning' : '' }}" style="{{ in_array($allKey, $diffKeys, true) ? 'background-color: #fff3cd;' : '' }}">"{{ $allKey }}": {{ $formatJsonValue($newData[$allKey] ?? null) }}@if ($index < count($allKeys) - 1)
,
@endif
</span>
@endforeach}</pre>
                                         </div>
                                     @else
                                         <pre class="bg-light p-2 small mt-1 rounded">
{{ json_encode($item->properties, JSON_PRETTY_PRINT) }}
                                </pre>
                                     @endif
                                 </div>

                             </details>
                         @endif
                     </div> --}}

                     <div class="row">
    @if ($item->properties)

        <details class="mt-2">
            <summary class="text-primary">View details</summary>

            @if ($item->event_type == \App\Enums\ActivityEventType::UPDATED)

                @php
                    /*
                    |--------------------------------------------------------------------------
                    | Normalize data
                    |--------------------------------------------------------------------------
                    */
                    $normalizeData = function ($data) {
                        if (is_array($data)) {
                            return $data;
                        }

                        if (is_object($data)) {
                            return (array) $data;
                        }

                        if (is_string($data)) {
                            $decoded = json_decode($data, true);

                            if (
                                json_last_error() === JSON_ERROR_NONE &&
                                is_array($decoded)
                            ) {
                                return $decoded;
                            }
                        }

                        return [];
                    };

                    $oldData = $normalizeData(
                        $item->properties['old_data'] ?? []
                    );

                    $newData = $normalizeData(
                        $item->properties['new_data'] ?? []
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Get all keys
                    |--------------------------------------------------------------------------
                    */
                    $allKeys = array_values(
                        array_unique(
                            array_merge(
                                array_keys($oldData),
                                array_keys($newData)
                            )
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Find changed keys
                    |--------------------------------------------------------------------------
                    */
                    $diffKeys = array_values(
                        array_filter(
                            $allKeys,
                            function ($key) use ($oldData, $newData) {
                                return ($oldData[$key] ?? null) !==
                                    ($newData[$key] ?? null);
                            }
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Recursive JSON Viewer
                    |--------------------------------------------------------------------------
                    */
                    $renderJson = function (
                        $data,
                        $changed = false,
                        $level = 0
                    ) use (&$renderJson) {

                        /*
                        |--------------------------------------------------------------------------
                        | Object
                        |--------------------------------------------------------------------------
                        */
                        if (is_object($data)) {
                            $data = (array) $data;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Primitive values
                        |--------------------------------------------------------------------------
                        */
                        if (!is_array($data)) {

                            if ($data === null) {
                                return '<span class="json-null">null</span>';
                            }

                            if (is_bool($data)) {
                                return '<span class="json-boolean">' .
                                    ($data ? 'true' : 'false') .
                                    '</span>';
                            }

                            if (is_int($data) || is_float($data)) {
                                return '<span class="json-number">' .
                                    e($data) .
                                    '</span>';
                            }

                            return '<span class="json-string">"' .
                                e($data) .
                                '"</span>';
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Empty array
                        |--------------------------------------------------------------------------
                        */
                        if (empty($data)) {
                            return '<span class="json-empty">{ }</span>';
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Determine array/object
                        |--------------------------------------------------------------------------
                        */
                        $isList = array_is_list($data);

                        $openingBracket = $isList ? '[' : '{';
                        $closingBracket = $isList ? ']' : '}';

                        $html = '<div class="json-container">';

                        /*
                        |--------------------------------------------------------------------------
                        | Opening bracket
                        |--------------------------------------------------------------------------
                        */
                        $html .=
                            '<div class="json-bracket">' .
                            $openingBracket .
                            '</div>';

                        /*
                        |--------------------------------------------------------------------------
                        | Children
                        |--------------------------------------------------------------------------
                        */
                        foreach ($data as $key => $value) {

                            $keyLabel = $isList
                                ? $key
                                : '"' . e($key) . '"';

                            $isNested =
                                is_array($value) ||
                                is_object($value);

                            /*
                            |--------------------------------------------------------------------------
                            | Nested object / array
                            |--------------------------------------------------------------------------
                            */
                            if ($isNested) {

                                $nestedType = is_array($value) &&
                                    array_is_list($value)
                                    ? 'array'
                                    : 'object';

                                $html .= '
                                    <div class="json-row">
                                        <details open>
                                            <summary>
                                                <span class="json-key">
                                                    ' . $keyLabel . '
                                                </span>

                                                <span class="json-colon">
                                                    :
                                                </span>

                                                <span class="json-type">
                                                    ' . $nestedType . '
                                                </span>
                                            </summary>

                                            <div class="json-nested">
                                                ' .
                                    $renderJson(
                                        $value,
                                        $changed,
                                        $level + 1
                                    ) .
                                    '
                                            </div>
                                        </details>
                                    </div>
                                ';
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Primitive
                            |--------------------------------------------------------------------------
                            */
                            else {

                                $valueHtml = $renderJson(
                                    $value,
                                    $changed,
                                    $level + 1
                                );

                                $highlightClass = $changed
                                    ? 'json-changed'
                                    : '';

                                $html .= '
                                    <div class="json-row ' .
                                    $highlightClass .
                                    '">

                                        <span class="json-key">
                                            ' .
                                    $keyLabel .
                                    '
                                        </span>

                                        <span class="json-colon">
                                            :
                                        </span>

                                        ' .
                                    $valueHtml .
                                    '
                                    </div>
                                ';
                            }
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Closing bracket
                        |--------------------------------------------------------------------------
                        */
                        $html .=
                            '<div class="json-bracket">' .
                            $closingBracket .
                            '</div>';

                        $html .= '</div>';

                        return $html;
                    };
                @endphp

                <div class="row mt-2">

                    {{-- OLD DATA --}}
                    <div class="col-md-6">

                        <div class="json-title">
                            <strong>Old data</strong>
                        </div>

                        <div class="json-viewer">

                            <div class="json-root">
                                {!! $renderJson($oldData) !!}
                            </div>

                        </div>

                    </div>

                    {{-- NEW DATA --}}
                    <div class="col-md-6">

                        <div class="json-title">
                            <strong>New data</strong>
                        </div>

                        <div class="json-viewer">

                            <div class="json-root">
                                {!! $renderJson($newData) !!}
                            </div>

                        </div>

                    </div>

                </div>

            @else

                {{-- OTHER EVENTS --}}
                @php
                    $otherProperties = $normalizeData(
                        $item->properties ?? []
                    );
                @endphp

                <div class="json-viewer mt-2">
                    {!! $renderJson($otherProperties) !!}
                </div>

            @endif

        </details>

    @endif
</div>

                 </div>
             @endforeach

         </div>
     @else
         <p class="text-center h3 mt-5">Data not found !</p>
     @endif

 </form>
