<style>
    .device-summary {
        cursor: pointer;
        color: #0d6efd;
        font-weight: 500;
        list-style: none;
        display: inline-block;
    }

    .device-toggle summary::-webkit-details-marker {
        display: none;
    }

    .timelinex {
        position: relative;
        margin-top: 10px;
        padding-left: 20px;
        border-left: 2px solid #dee2e6;
    }

    .timelinex-item {
        position: relative;
        margin-bottom: 15px;
    }



    .timelinex-content strong {
        display: block;
        font-size: 13px;
        color: #333;
    }

    .timelinex-content p {
        margin: 2px 0 0;
        font-size: 14px;
        color: #555;
    }

    /* ✅ details open in next row */
    .details-row {
        display: none;
        background: #f9f9f9;
    }

    /* IMPORTANT: details must be sibling of the next <tr> */
    details.device-toggle[open]+tr.details-row {
        display: table-row;
    }
</style>

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

                        <th>Device Name</th>
                        <th>Platform</th>
                        <th>Device Model</th>
                        <th>Build Version</th>
                        <th>OS Version</th>
                        <th>Zipcode</th>

                        @if ($allSession->first()->user->roles->first()->id == 5 && $allSession->first()->user->user_type != 'custom')
                            <th>Hotel room</th>
                        @endif

                        <th>Date added</th>
                        <th>Expire At</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($allSession as $key => $user)
                        @php
                            $showHotelRoom =
                                $allSession->first()->user->roles->first()->id == 5 &&
                                $allSession->first()->user->user_type != 'custom';

                            // columns: checkbox + device + platform + model + build + os + zipcode + (hotel?) + date + action
                            $colspan = $showHotelRoom ? 10 : 9;
                        @endphp

                        {{-- MAIN ROW --}}
                        <tr>
                            <td style="vertical-align: middle;">
                                <input type="checkbox" name="user_ids[]" class="row-checkbox" value="{{ $user->id }}">
                            </td>

                            <td>
                                <div class="flex">
                                    <div class="device-box"> @php
                                        $device = detectDeviceByUserAgent($user->useragent ?? '');
                                    @endphp

                                        @if (preg_match('/pc|tv/i', $device))
                                            <img class="device-icon" src="{{ asset('images/desktop.png') }}" alt="device icon">
                                        @elseif (preg_match('/pc|tv/i', $user->device_title))
                                            <img class="device-icon" src="{{ asset('images/desktop.png') }}" alt="device icon">
                                        @else
                                            <img class="device-icon" src="{{ asset('images/mobile.png') }}" alt="device icon">
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
                                            {{-- ✅ Use details only as toggle --}}
                                            <details class="device-toggle">
                                                <summary class="device-summary">
                                                    {{ \Illuminate\Support\Str::limit($user->device_id, 10, '...') }}
                                                </summary>
                                            </details>
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td style="vertical-align: middle;">
                                {{ $user->device_title ?? (getPlatform($user->useragent, $user->device_title) ?? 'N/A') }}
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

                            @if ($showHotelRoom)
                                <td style="vertical-align: middle;">
                                    @if ($user->hotel != null)
                                        {{ $user->hotel->hotel_room ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            @endif

                            <td style="vertical-align: middle;">
                                {{ date('d/m/Y', strtotime($user->created_on)) }}
                                <br>
                                {{ \Carbon\Carbon::parse($user->created_on)->setTimezone(config('app.timezone'))->format('h:i:s A') }}
                            </td>
                              <td style="vertical-align: middle;">
                                {{ date('d/m/Y', strtotime($user->token_expire_at)) }}
                                <br>
                                {{ \Carbon\Carbon::parse($user->token_expire_at)->setTimezone(config('app.timezone'))->format('h:i:s A') }}
                            </td>

                            <td style="vertical-align: middle;">
                                <a class=" btn-delete" href="{{ route('admin.users.session.destroy', $user->id) }}"
                                    data-url="{{ route('admin.users.session.destroy', $user->id) }}">
                                    <img class="img-delete" src="{{ asset('images/trash.png') }}" alt="Delete Icon">
                                </a>
                            </td>
                        </tr>

                        {{-- ✅ DETAILS ROW (NEXT ROW) --}}
                        {{-- IMPORTANT: This <tr> must be immediately after the <details> element sibling for CSS selector to
                                work.
                                Because <details> is inside <td>, we can't use "details + tr" reliably in pure CSS.
                                        So we use a tiny JS below to toggle this row. --}}
                        <tr class="details-row" data-user=" {{ $user->id }}">
                            <td colspan="{{ $colspan }}">
                                <div class="timelinex">
                                    <h5> Device Id : {{ $user->device_id }}</h5>
                                    <hr>
                                    @foreach ($user->subSession as $item)
                                        <div class=" timelinex-item">
                                            <div class="timelinex-content">
                                                <div class="row">

                                                    <h5>
                                                        {{ \Carbon\Carbon::parse($item->created_at)->setTimezone(config('app.timezone'))->format('d/m/Y h:i:s A') }}
                                                    </h5>
                                                </div>
                                                <div class="row">
                                                    <b>Platform : </b>
                                                    <p> &nbsp;&nbsp; {{ $item->platform }}</p>
                                                </div>
                                                <div class="row">
                                                    <b>Device Model : </b>
                                                    <p> &nbsp;&nbsp; {{ $item->device_model }}</p>
                                                </div>
                                                <div class="row">
                                                    <b>Build Version : </b>
                                                    <p> &nbsp;&nbsp; {{ $item->build_version }}</p>
                                                </div>
                                                <div class="row">
                                                    <b>OS Version : </b>
                                                    <p> &nbsp;&nbsp; {{ $item->device_os_version }}</p>
                                                </div>
                                                <div class="row">
                                                    <b>Zipcode : </b>
                                                    <p> &nbsp;&nbsp; {{ $item->zipcode }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach


                                </div>
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

                        <th>Device Name</th>
                        <th>Platform</th>
                        <th>Device Model</th>
                        <th>Build Version</th>
                        <th>OS Version</th>
                        <th>Zipcode</th>
                        <th>Date added</th>
                        <th>Expire At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="10"> Data not found !</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = selectAll
                    .checked);
            });
        }

        // Toggle next row by clicking summary
        document.querySelectorAll('details.device-toggle').forEach(function (d) {
            d.addEventListener('toggle', function () {
                const mainRow = d.closest('tr');
                const detailsRow = mainRow ? mainRow.nextElementSibling : null;

                if (!detailsRow || !detailsRow.classList.contains('details-row')) return;

                // close other open ones (optional)
                document.querySelectorAll('tr.details-row').forEach(r => {
                    if (r !== detailsRow) r.style.display = 'none';
                });
                document.querySelectorAll('details.device-toggle').forEach(other => {
                    if (other !== d) other.open = false;
                });

                detailsRow.style.display = d.open ? 'table-row' : 'none';
            });
        });
    });
</script>