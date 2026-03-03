 <form id="tbl-form" method="POST" action="{{ route('admin.users.bulk.action') }}">
     @csrf
     {{-- <input hidden type="text" name="action" value="session">
     <div class="table-responsive">
         @if ($allSession->count() > 0)
             <table class="table">
                 <thead>
                     <tr>
                         <th>
                             <input type="checkbox" name="" id="selectAll">
                         </th>

                         <th>
                             Device Name
                         </th>
                         <th>
                             Platform
                         </th>
                         <th>
                             Device Model
                         </th>
                         <th>
                             Build Version
                         </th>
                         <th>
                             OS Version
                         </th>
                         <th>
                             Zipcode
                         </th>
                         @if ($allSession->first()->user->roles->first()->id == 5 && $allSession->first()->user->user_type != 'custom')
                             <th>
                                 Hotel room
                             </th>
                         @endif
                         <th>
                             Date added
                         </th>

                         <th>
                             Action
                         </th>




                     </tr>
                 </thead>
                 <tbody>
                     @foreach ($allSession as $key => $user)
                         <tr>
                             <td style="vertical-align: middle;">
                                 <input type="checkbox" name="user_ids[]" class="row-checkbox"
                                     value="{{ $user->id }}">
                             </td>



                             <td>

                                 <div class="flex">
                                     <div class="device-box">
                                         @php
                                             $device = detectDeviceByUserAgent($user->useragent ?? '');
                                         @endphp

                                         @if (preg_match('/pc|tv/i', $device))
                                             <img class="device-icon" src="{{ asset('images/desktop.png') }}"
                                                 alt="device icon">
                                         @elseif (preg_match('/pc|tv/i', $user->device_title))
                                             <img class="device-icon" src="{{ asset('images/desktop.png') }}"
                                                 alt="device icon">
                                         @else
                                             <img class="device-icon" src="{{ asset('images/mobile.png') }}"
                                                 alt="device icon">
                                         @endif



                                     </div>
                                     <div class="device-name">
                                         @if ($user->device_type === 'web')
                                             <b> Web</b>
                                         @else
                                             <b> {{ $user->pdInfo->personal_devicename ?? '' }}</b>
                                         @endif
                                         <span>
                                             ID :
                                             <span style="cursor: pointer" class="short-id"
                                                 onclick="toggleDeviceId(this)" data-full="{{ $user->device_id }}">

                                                 {{ \Illuminate\Support\Str::limit($user->device_id, 10, '...') }}
                                             </span>
                                         </span>
                                     </div>
                                 </div>




                             </td>
                             <td style="vertical-align: middle;">

                                 {{ getPlatform($user->useragent, $user->device_title) ?? 'N/A' }}



                             </td>



                             <td style="vertical-align: middle;">

                                 {{ getDeviceModel($user->useragent, $user->device_title, $user->device_model) ?? 'N/A' }}


                             </td>

                             <td style="vertical-align: middle;">

                                 {{ $user->build_version ?? 'N/A' }}
                             </td>
                             <td style="vertical-align: middle;">

                                 {{ $user->device_os_version ?? 'N/A' }}
                             </td>
                             <td style="vertical-align: middle;">
                                 {{ $user->zipcode ?? 'N/A' }}
                             </td>
                             @if ($allSession->first()->user->roles->first()->id == 5 && $allSession->first()->user->user_type != 'custom')
                                 <td style="vertical-align: middle;">

                                     @if ($user->hotel != null)
                                         {{ $user->hotel->hotel_room ?? 'N/A' }}
                                     @endif

                                 </td>
                             @endif
                             <td style="vertical-align: middle;">
                                 {{ date('d/m/Y', strtotime($user->created_on)) }}
                                 <br>

                                 {{ \Carbon\Carbon::parse($user->created_on)->setTimezone(config('app.timezone'))->format('h:i:s A') }}
                             </td>

                             <td style="vertical-align: middle;">
                                 <a class="btn-delete" href="{{ route('admin.users.session.destroy', $user->id) }}"
                                     data-url="{{ route('admin.users.session.destroy', $user->id) }}">
                                     <img class="img-delete" src="{{ asset('images/trash.png') }}" alt="Delete Icon">
                                 </a>
                             </td>



                         </tr>
                     @endforeach
                 </tbody>
             </table>
         @else
             <table class="table">
                 <thead>
                     <tr>
                         <th>
                             <input type="checkbox" name="" id="selectAll">
                         </th>

                         <th>
                             Device Name
                         </th>
                         <th>
                             Platform
                         </th>
                         <th>
                             Device Model
                         </th>
                         <th>
                             Build Version
                         </th>
                         <th>
                             OS Version
                         </th>
                         <th>
                             Zipcode
                         </th>

                         <th>
                             Date added
                         </th>

                         <th>
                             Action
                         </th>




                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td colspan="9"> Data not found !</td>
                     </tr>
                 </tbody>
             </table>
         @endif

     </div> --}}

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
