<style>
    .custom-badge {
        font-size: 14px;
        font-weight: 500;
        border-radius: 14px;
        padding: 5px 10px;
        display: inline-block;
    }

    .badge-active {
        color: #578f6d;
        background-color: #dff5e8;
    }

    .badge-inactive {
        color: #cf6773;
        background-color: #ffebf0;
    }

    .table thead {
        background-color: #fafafa;
    }

    .table thead tr th {
        font-size: 14px;
        color: #7d7d7d;
    }

    .table tbody tr td {
        font-size: 16px;
        color: #383838;
        font-weight: 500;

    }

    .badge-light {
        background: none;
    }
</style>


<div class="row mt-2 px-2">
    <div class="col-md-2 mb-3">
        <div class="select-wrapper">
            <img src="{{ asset('images/user-mode.png') }}" alt="status icon">
            <select name="limit" id="sel_limit" class="select-custom">
                <option value="10" {{ request()->limit == 10 ? 'selected' : '' }}>Show 10</option>
                <option value="30" {{ request()->limit == 30 ? 'selected' : '' }}>Show 30</option>
                <option value="50" {{ request()->limit == 50 ? 'selected' : '' }}>Show 50</option>
                <option value="100" {{ request()->limit == 100 ? 'selected' : '' }}>Show 100</option>
            </select>
        </div>
    </div>
    <div class="col-md-2 mb-3">



    </div>
    <div class="col-md-2 mb-3">
        <a href="javascript:void(0)" id="btn-show-selected">
            <h3 id="selected" class="card-title mt-2"></h3>
        </a>

    </div>

</div>
<form id="tbl-form" method="POST" action="{{ route('admin.users.bulk.action') }}">
    @csrf

    <input hidden type="text" name="action">
    <div class="table-responsive">
        @if ($houseList->count() > 0)
            <table class="table">
                <thead>
                    <tr>

                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            House No
                        </th>
                        <th>
                            Passcode
                        </th>
                        <th>
                            Status
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?status=' . (request()->get('status', '1') === '1' ? '0' : '1')
                                : request()->fullUrlWithQuery([
                                    'status' => request()->get('status', '1') === '1' ? '0' : '1',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>
                        <th>
                            Active <br> Devices
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?device=' . (request()->get('device', 'asc') === 'asc' ? 'desc' : 'asc')
                                : request()->fullUrlWithQuery([
                                    'device' => request()->get('device', 'asc') === 'asc' ? 'desc' : 'asc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>
                        <th>
                            Created At
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?created=' . (request()->get('created', 'desc') === 'desc' ? 'asc' : 'desc')
                                : request()->fullUrlWithQuery([
                                    'created' => request()->get('created', 'desc') === 'desc' ? 'asc' : 'desc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>



                    </tr>
                </thead>
                <tbody>
                    @foreach ($houseList as $key => $item)
                        <tr>

                            <td>
                                <div class="mb-2"
                                    style="width:40px; height:40px;
                                            background:#d62d41;
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                    {{ getAvatarName($item->name ?? 'N/A') }}
                                </div>


                                <span> {{ ucwords(Str::limit($item->name, 10, '...')) }}</span>
                            </td>
                            <td style="vertical-align:middle;">{{ $item->email ?? 'N/A' }}</td>
                            <td style="vertical-align:middle;">
                                {{ $item->house_no == null ? 'House' . $item->ordering : $item->house_no }}
                            </td>
                            <td style="vertical-align:middle;">{{ $item->passcode ?? 'N/A' }}</td>
                            <td style="vertical-align:middle;">
                                @if ($item->status == 1)
                                    <span class="custom-badge badge-active">Active</span>
                                @else
                                    <span class="custom-badge badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td style="vertical-align:middle;">
                                @if ($item->session_count > 0)
                                    <a href="{{ route('admin.hoa.sub-users-session', $item->passcode) }}">
                                        {{ $item->session_count }} / {{ $item->device_limit ?? 0 }}
                                    </a>
                                @else
                                    {{ $item->session_count ?? 0 }} / {{ $item->device_limit ?? 0 }}
                                @endif

                            </td>
                            <td style="vertical-align:middle;">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table class="table table-striped projects">
                <thead>
                    <tr>

                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>

                        <th>
                            Passcode
                        </th>
                        <th>
                            Status
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?status=' . (request()->get('status', 'active') === 'active' ? 'inactive' : 'active')
                                : request()->fullUrlWithQuery([
                                    'status' => request()->get('status', 'active') === 'active' ? 'inactive' : 'active',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>
                        <th>
                            Active <br> Devices
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?device=' . (request()->get('device', 'asc') === 'asc' ? 'desc' : 'asc')
                                : request()->fullUrlWithQuery([
                                    'device' => request()->get('device', 'asc') === 'asc' ? 'desc' : 'asc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>
                        <th>
                            Created At
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?created=' . (request()->get('created', 'desc') === 'desc' ? 'asc' : 'desc')
                                : request()->fullUrlWithQuery([
                                    'created' => request()->get('created', 'desc') === 'desc' ? 'asc' : 'desc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>



                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="text-center">No records found</td>
                    </tr>
            </table>
        @endif
    </div>
</form>
<div class="d-flex justify-content-end mt-5 mr-1">
    {{ $houseList->withQueryString()->links() }}
</div>
