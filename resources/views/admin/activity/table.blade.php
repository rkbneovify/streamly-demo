<style>
    /*
    |--------------------------------------------------------------------------
    | Main JSON viewer
    |--------------------------------------------------------------------------
    */

    .json-viewer {
       background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0 0 6px 6px;
    padding: 12px 14px;

    font-family:
        "SFMono-Regular",
        Consolas,
        "Liberation Mono",
        Menlo,
        monospace;

    font-size: 13px;
    line-height: 1.7;

    color: #212529;

    overflow-x: hidden;
    overflow-y: auto;

    max-height: 500px;
    }


    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    .json-title {
        background: #f1f3f5;

        border: 1px solid #dee2e6;

        padding: 8px 12px;

        border-radius: 6px 6px 0 0;

        font-size: 13px;
    }


    /*
    |--------------------------------------------------------------------------
    | JSON container
    |--------------------------------------------------------------------------
    */

    .json-container {
        margin-left: 2px;
    }


    /*
    |--------------------------------------------------------------------------
    | JSON row
    |--------------------------------------------------------------------------
    */

    .json-row {
        position: relative;
    min-height: 24px;

    padding: 2px 6px;
    border-radius: 4px;

    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
    }


    /*
    |--------------------------------------------------------------------------
    | Hover
    |--------------------------------------------------------------------------
    */

    .json-row:hover {
        background-color: #eef1f4;
    }


    /*
    |--------------------------------------------------------------------------
    | JSON key
    |--------------------------------------------------------------------------
    */

    .json-key {
        color: #922;

        font-weight: 500;
    }


    /*
    |--------------------------------------------------------------------------
    | Array index
    |--------------------------------------------------------------------------
    */

    .json-index {
        color: #666;

        font-weight: 500;
    }


    /*
    |--------------------------------------------------------------------------
    | Colon
    |--------------------------------------------------------------------------
    */

    .json-colon {
        color: #333;

        margin-left: 4px;

        margin-right: 5px;
    }


    /*
    |--------------------------------------------------------------------------
    | String
    |--------------------------------------------------------------------------
    */

    .json-string {
        color: #008000;

    white-space: pre-wrap;
    overflow-wrap: anywhere;
    word-break: break-word;
    }


    /*
    |--------------------------------------------------------------------------
    | Number
    |--------------------------------------------------------------------------
    */

    .json-number {
        color: #005cc5;
    }


    /*
    |--------------------------------------------------------------------------
    | Boolean
    |--------------------------------------------------------------------------
    */

    .json-boolean {
        color: #7a3e9d;

        font-weight: 500;
    }


    /*
    |--------------------------------------------------------------------------
    | Null
    |--------------------------------------------------------------------------
    */

    .json-null {
        color: #808080;

        font-style: italic;
    }


    /*
    |--------------------------------------------------------------------------
    | Empty object / array
    |--------------------------------------------------------------------------
    */

    .json-empty {
        color: #777;
    }


    /*
    |--------------------------------------------------------------------------
    | Brackets
    |--------------------------------------------------------------------------
    */

    .json-bracket {
        color: #555;

        font-weight: 600;

        min-height: 22px;
    }


    /*
    |--------------------------------------------------------------------------
    | Details
    |--------------------------------------------------------------------------
    */

    .json-viewer details {
        margin: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    .json-viewer summary {
         cursor: pointer;
    list-style: none;
    user-select: none;

    min-height: 24px;
    padding: 2px 6px;

    border-radius: 4px;

    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
    }


    /*
    |--------------------------------------------------------------------------
    | Remove default browser arrow
    |--------------------------------------------------------------------------
    */

    .json-viewer summary::-webkit-details-marker {
        display: none;
    }


    /*
    |--------------------------------------------------------------------------
    | Custom expand arrow
    |--------------------------------------------------------------------------
    */

    .json-viewer summary::before {
        content: "▶";

        display: inline-block;

        width: 16px;

        color: #6c757d;

        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | Expanded arrow
    |--------------------------------------------------------------------------
    */

    .json-viewer details[open] > summary::before {
        content: "▼";
    }


    /*
    |--------------------------------------------------------------------------
    | Nested JSON
    |--------------------------------------------------------------------------
    */

    .json-nested {
         margin-left: 18px;
    border-left: 1px solid #d6d6d6;
    padding-left: 8px;

    min-width: 0;
    overflow-wrap: anywhere;
    }


    /*
    |--------------------------------------------------------------------------
    | Changed value
    |--------------------------------------------------------------------------
    */

    .json-changed {
        background-color: #fff3cd;
    border: 1px solid #ffda6a;
    border-radius: 4px;

    padding: 2px 6px;
    margin: 1px 0;

    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
    }


    /*
    |--------------------------------------------------------------------------
    | Changed parent object
    |--------------------------------------------------------------------------
    */

    .json-changed-container > summary {
        background-color: #fff8e1;
    }


    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

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

        @php
            /*
            |--------------------------------------------------------------------------
            | Normalize JSON data
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

            /*
            |--------------------------------------------------------------------------
            | Recursive diff checker
            |--------------------------------------------------------------------------
            |
            | Returns an array containing the paths of changed values.
            |
            */
            $getChangedPaths = function (
                $old,
                $new,
                $path = ''
            ) use (&$getChangedPaths) {

                $changedPaths = [];

                if (is_object($old)) {
                    $old = (array) $old;
                }

                if (is_object($new)) {
                    $new = (array) $new;
                }

                /*
                |--------------------------------------------------------------------------
                | Both arrays
                |--------------------------------------------------------------------------
                */
                if (is_array($old) && is_array($new)) {

                    $keys = array_unique(
                        array_merge(
                            array_keys($old),
                            array_keys($new)
                        )
                    );

                    foreach ($keys as $key) {

                        $currentPath = $path === ''
                            ? (string) $key
                            : $path . '.' . $key;

                        $oldExists = array_key_exists($key, $old);
                        $newExists = array_key_exists($key, $new);

                        /*
                        |--------------------------------------------------------------------------
                        | Added / removed
                        |--------------------------------------------------------------------------
                        */
                        if (!$oldExists || !$newExists) {
                            $changedPaths[] = $currentPath;
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Nested arrays
                        |--------------------------------------------------------------------------
                        */
                        if (
                            is_array($old[$key]) ||
                            is_object($old[$key]) ||
                            is_array($new[$key]) ||
                            is_object($new[$key])
                        ) {
                            $nestedChanges = $getChangedPaths(
                                $old[$key],
                                $new[$key],
                                $currentPath
                            );

                            $changedPaths = array_merge(
                                $changedPaths,
                                $nestedChanges
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | If nested structure itself changed
                            |--------------------------------------------------------------------------
                            */
                            if (
                                empty($nestedChanges) &&
                                $old[$key] !== $new[$key]
                            ) {
                                $changedPaths[] = $currentPath;
                            }

                        } elseif (
                            $old[$key] !== $new[$key]
                        ) {

                            $changedPaths[] = $currentPath;
                        }
                    }

                    return array_values(
                        array_unique($changedPaths)
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Primitive values
                |--------------------------------------------------------------------------
                */
                if ($old !== $new) {
                    $changedPaths[] = $path;
                }

                return $changedPaths;
            };

            /*
            |--------------------------------------------------------------------------
            | JSON Viewer
            |--------------------------------------------------------------------------
            */
            $renderJson = function (
                $data,
                $changedPaths = [],
                $currentPath = '',
                $isRoot = true
            ) use (&$renderJson) {

                /*
                |--------------------------------------------------------------------------
                | Object to array
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

                    /*
                    |--------------------------------------------------------------------------
                    | String
                    |--------------------------------------------------------------------------
                    */
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

                    $isList = array_is_list($data);

                    return '<span class="json-empty">' .
                        ($isList ? '[ ]' : '{ }') .
                        '</span>';
                }

                /*
                |--------------------------------------------------------------------------
                | Determine object / array
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
                | Render values
                |--------------------------------------------------------------------------
                */
                foreach ($data as $key => $value) {

                    $childPath = $currentPath === ''
                        ? (string) $key
                        : $currentPath . '.' . $key;

                    /*
                    |--------------------------------------------------------------------------
                    | Check if this value changed
                    |--------------------------------------------------------------------------
                    */
                    $isChanged = false;

                    foreach ($changedPaths as $changedPath) {

                        /*
                        | Exact match
                        */
                        if ($changedPath === $childPath) {
                            $isChanged = true;
                            break;
                        }

                        /*
                        | Parent / nested change
                        */
                        if (
                            str_starts_with(
                                $changedPath,
                                $childPath . '.'
                            )
                        ) {
                            $isChanged = true;
                            break;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Array index or object key
                    |--------------------------------------------------------------------------
                    */
                    if ($isList) {
                        $keyHtml =
                            '<span class="json-index">' .
                            e($key) .
                            '</span>';
                    } else {
                        $keyHtml =
                            '<span class="json-key">"' .
                            e($key) .
                            '"</span>';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Nested array/object
                    |--------------------------------------------------------------------------
                    */
                    if (
                        is_array($value) ||
                        is_object($value)
                    ) {

                        $html .=
                            '<div class="json-row ' .
                            ($isChanged
                                ? 'json-changed-container'
                                : '') .
                            '">';

                        $html .= '<details open>';

                        $html .= '<summary>';

                        $html .= $keyHtml;

                        $html .=
                            '<span class="json-colon">:</span>';

                        $html .= '</summary>';

                        $html .=
                            '<div class="json-nested">';

                        $html .= $renderJson(
                            $value,
                            $changedPaths,
                            $childPath,
                            false
                        );

                        $html .= '</div>';

                        $html .= '</details>';

                        $html .= '</div>';

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | Primitive value
                        |--------------------------------------------------------------------------
                        */
                        $valueHtml = $renderJson(
                            $value,
                            $changedPaths,
                            $childPath,
                            false
                        );

                        $html .=
                            '<div class="json-row ' .
                            ($isChanged
                                ? 'json-changed'
                                : '') .
                            '">';

                        $html .= $keyHtml;

                        $html .=
                            '<span class="json-colon">:</span>';

                        $html .= $valueHtml;

                        $html .= '</div>';
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

        <details class="mt-2">

            <summary class="text-primary">
                View details
            </summary>

            @if (
                $item->event_type ==
                \App\Enums\ActivityEventType::UPDATED
            )

                @php
                    /*
                    |--------------------------------------------------------------------------
                    | Old / New Data
                    |--------------------------------------------------------------------------
                    */
                    $oldData = $normalizeData(
                        $item->properties['old_data'] ?? []
                    );

                    $newData = $normalizeData(
                        $item->properties['new_data'] ?? []
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Find recursive changes
                    |--------------------------------------------------------------------------
                    */
                    $changedPaths = $getChangedPaths(
                        $oldData,
                        $newData
                    );
                @endphp

                <div class="row mt-2">

                    {{-- =========================================================
                         OLD DATA
                    ========================================================== --}}

                    <div class="col-md-6">

                        <div class="json-title">
                            <strong>Old data</strong>
                        </div>

                        <div class="json-viewer">

                            {!! $renderJson(
                                $oldData,
                                $changedPaths
                            ) !!}

                        </div>

                    </div>


                    {{-- =========================================================
                         NEW DATA
                    ========================================================== --}}

                    <div class="col-md-6">

                        <div class="json-title">
                            <strong>New data</strong>
                        </div>

                        <div class="json-viewer">

                            {!! $renderJson(
                                $newData,
                                $changedPaths
                            ) !!}

                        </div>

                    </div>

                </div>

            @else

                @php
                    $propertiesData = $normalizeData(
                        $item->properties
                    );
                @endphp

                <div class="json-viewer mt-2">

                    {!! $renderJson($propertiesData) !!}

                </div>

            @endif

        </details>

    @endif
</div>




{{-- end --}}
                 </div>
             @endforeach

         </div>
     @else
         <p class="text-center h3 mt-5">Data not found !</p>
     @endif

 </form>
