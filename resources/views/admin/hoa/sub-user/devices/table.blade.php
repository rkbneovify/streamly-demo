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
                            Status
                        </th>

                        <th>
                            Date added
                        </th>
                        <th>
                            Expired At
                        </th>
                        <th>
                            Action
                        </th>






                    </tr>
                </thead>
                <tbody>
                    @foreach ($allSession as $key => $user)
                        @php
                            $colspan = 8;
                         @endphp
                        <tr>
                            <td style="vertical-align: middle;">
                                <input type="checkbox" name="user_ids[]" class="row-checkbox" value="{{ $user->id }}">
                            </td>



                            <td>

                                <div class="flex">
                                    <div class="device-box">
                                        @php
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
                                            <b> {{ $user->pdInfoPasscode->personal_devicename ?? '' }}</b>
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
                            <td style="vertical-align: middle;">
                                @if ($user->status == 1 && $user->token_expire_at != null)
                                    <span class="badge badge-info badge-pill p-2  px-3">Active</span>

                                @else

                                    <span class="badge badge-danger badge-pill p-2 px-3">Expired</span>


                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                {{ date('d/m/Y', strtotime($user->created_on)) }}
                                <br>

                                {{ \Carbon\Carbon::parse($user->created_on)->setTimezone(config('app.timezone'))->format('h:i:s A') }}
                            </td>
                            <td style="vertical-align: middle;">
                                @if($user->token_expire_at != null)
                                    {{ date('d/m/Y', strtotime($user->token_expire_at)) }}
                                    <br>
                                    {{ \Carbon\Carbon::parse($user->token_expire_at)->setTimezone(config('app.timezone'))->format('h:i:s A') }}

                                @else
                                    {{ date('d/m/Y', strtotime($user->modified_on)) }}
                                    <br>
                                    {{ \Carbon\Carbon::parse($user->modified_on)->setTimezone(config('app.timezone'))->format('h:i:s A') }}
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                <div class="d-flex">
                                    <a class=" btn-delete" href="{{ route('admin.users.session.destroy', $user->id) }}"
                                        data-url="{{ route('admin.users.session.destroy', $user->id) }}">
                                        <img class="img-delete" src="{{ asset('images/trash.png') }}" alt="Delete Icon">
                                    </a>
                                    <a class="btn-activity" target="_blank"
                                        href="{{ route('admin.sub-user.activity.session', $user->access_token) }}"
                                        data-url="{{ route('admin.sub-user.activity.session', $user->access_token) }}">
                                        <svg width="25px" height="25px" viewBox="0 -0.5 25 25" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">

                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />

                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M15.5 7.75C15.0858 7.75 14.75 8.08579 14.75 8.5C14.75 8.91421 15.0858 9.25 15.5 9.25V7.75ZM19.5 9.25C19.9142 9.25 20.25 8.91421 20.25 8.5C20.25 8.08579 19.9142 7.75 19.5 7.75V9.25ZM20.25 8.5C20.25 8.08579 19.9142 7.75 19.5 7.75C19.0858 7.75 18.75 8.08579 18.75 8.5H20.25ZM18.75 12.5C18.75 12.9142 19.0858 13.25 19.5 13.25C19.9142 13.25 20.25 12.9142 20.25 12.5H18.75ZM20.0303 9.03033C20.3232 8.73744 20.3232 8.26256 20.0303 7.96967C19.7374 7.67678 19.2626 7.67678 18.9697 7.96967L20.0303 9.03033ZM12.5 15.5L11.9697 16.0303C12.2626 16.3232 12.7374 16.3232 13.0303 16.0303L12.5 15.5ZM9.5 12.5L10.0303 11.9697C9.73744 11.6768 9.26256 11.6768 8.96967 11.9697L9.5 12.5ZM4.96967 15.9697C4.67678 16.2626 4.67678 16.7374 4.96967 17.0303C5.26256 17.3232 5.73744 17.3232 6.03033 17.0303L4.96967 15.9697ZM15.5 9.25H19.5V7.75H15.5V9.25ZM18.75 8.5V12.5H20.25V8.5H18.75ZM18.9697 7.96967L11.9697 14.9697L13.0303 16.0303L20.0303 9.03033L18.9697 7.96967ZM13.0303 14.9697L10.0303 11.9697L8.96967 13.0303L11.9697 16.0303L13.0303 14.9697ZM8.96967 11.9697L4.96967 15.9697L6.03033 17.0303L10.0303 13.0303L8.96967 11.9697Z"
                                                    fill="#000000" />
                                            </g>

                                        </svg>
                                    </a>
                                </div>
                            </td>


                            {{-- ✅ DETAILS ROW (NEXT ROW) --}}
                            {{-- IMPORTANT: This
                        <tr> must be immediately after the <details> element sibling for CSS selector to work.
                                Because <details> is inside <td>, we can't use "details + tr" reliably in pure CSS.
                                        So we use a tiny JS below to toggle this row. --}}
                        <tr class="details-row" data-user="{{ $user->id }}">
                            <td colspan="{{ $colspan }}">
                                <div class="timelinex">
                                    <h5> Device Id : {{ $user->device_id }}</h5>
                                    <hr>
                                    @foreach ($user->subSessionPasscode as $item)
                                        <div class="timelinex-item">
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
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach


                                </div>
                            </td>
                        </tr>


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

<script>
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
</script>