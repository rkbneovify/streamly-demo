 <form id="tbl-form" method="POST" action="{{ route('admin.users.bulk.action') }}">
     @csrf

     @forelse($activities as $date => $activity)
         <div class="card">
             <div class="card-body">
                 <div class="card-header mb-2">
                     <b>{{ $date }}</b>
                 </div>

                 @foreach ($activity as $key => $item)
                     @if ($key > 0)
                         <hr>
                     @endif

                     <div class="col mb-3">


                         <b>
                             {{ \Carbon\Carbon::parse($item->created_at)->setTimezone(config('app.timezone'))->format('h:i:s A') }}
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
                             {{ $item->description }}

                         </div>

                         <div class="row">
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
                                                         if (
                                                             json_last_error() === JSON_ERROR_NONE &&
                                                             is_array($decoded)
                                                         ) {
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
                                                     array_unique(
                                                         array_merge(array_keys($oldData), array_keys($newData)),
                                                     ),
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
                                                 <pre class="bg-light p-2 small mt-1 rounded"><b>Old data</b>
{
@foreach ($allKeys as $index => $allKey)
<span class="d-block {{ in_array($allKey, $diffKeys, true) ? 'border rounded px-1 border-warning' : '' }}" style="{{ in_array($allKey, $diffKeys, true) ? 'background-color: #fff3cd;' : '' }}">"{{ $allKey }}": {{ $formatJsonValue($oldData[$allKey] ?? null) }}@if ($index < count($allKeys) - 1)
,
@endif
</span>
@endforeach}</pre>
                                             </div>
                                             <div class="col-6">
                                                 <pre class="bg-light p-2 small mt-1 rounded"><b>New data</b>
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
                         </div>
                     </div>
                 @endforeach

             </div>
         </div>

         {{-- Pagination --}}

         @empty
             <p class="text-center text-muted">No activity found</p>
         @endforelse

     </form>
