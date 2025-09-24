 <form id="tbl-form" method="POST" action="{{ route('admin.users.bulk.action') }}">
     @csrf
     <input hidden type="text" name="action" value="session">
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
                         @if ($allSession->first()->user->roles->first()->id == 5)
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

                                 {{ getPlatform($user->useragent, $user->device_title) }}



                             </td>



                             <td style="vertical-align: middle;">
                                 @if ($user->device_type === 'web')
                                     N/A
                                 @endif
                                 {{ getDeviceModel($user->useragent, $user->device_title, $user->device_model) }}


                             </td>

                             <td style="vertical-align: middle;">
                                 @if ($user->device_type === 'web')
                                     N/A
                                 @endif
                                 {{ $user->build_version ?? '' }}
                             </td>
                             <td style="vertical-align: middle;">
                                 @if ($user->device_type === 'web')
                                     N/A
                                 @endif
                                 {{ $user->device_os_version ?? '' }}
                             </td>
                             <td style="vertical-align: middle;">
                                 {{ $user->zipcode ?? '' }}
                             </td>
                             @if ($allSession->first()->user->roles->first()->id == 5)
                                 <td style="vertical-align: middle;">
                                     @if ($user->device_type === 'web')
                                         N/A
                                     @endif
                                     @if ($user->hotel != null)
                                         {{ $user->hotel->hotel_room ?? '' }}
                                     @endif

                                 </td>
                             @endif
                             <td style="vertical-align: middle;">
                                 📅 {{ date('d/m/Y', strtotime($user->created_on)) }}
                                 <br>
                                 🕒
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

     </div>
 </form>
