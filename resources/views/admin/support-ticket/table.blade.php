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

    .dropdown-toggle::after {
        display: none !important;
    }

    .radius {
        border-radius: 20px;
        padding: 10px;
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

        {{-- <h3 class="card-title mt-2">Total Users : {{ $users->total() }}</h3> --}}

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
    @if ($data->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="custom-checkbox" id="selectAll">
                        </th>
                        <th>
                            Ticket Number
                        </th>
                        <th>
                            User
                        </th>
                        <th>
                            Platform
                        </th>
                        <th>
                            Subject
                        </th>

                        <th>
                            Priority

                        </th>
                        <th>
                            Status

                        </th>

                        <th>
                            Created At
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?sort_date=' . (request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc')
                                : request()->fullUrlWithQuery([
                                    'sort_date' => request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>


                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td style="vertical-align: middle;">
                                <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $item->id }}">
                            </td>
                            <td style="vertical-align: middle;">
                                #{{ $item->ticket_number }}
                            </td>
                            <td>
                                <a href="{{ route('admin.users.view', $item->user->id) }}">
                                    <div class="mb-2"
                                        style="width:40px; height:40px;
                                            background:#d62d41;
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                        {{ getAvatarName($item->user->name) }}
                                    </div>


                                    <span> {{ ucwords(Str::limit($item->user->name, 10, '...')) }}</span>
                                </a>
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $item->platform }}
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $item->subject }}
                            </td>
                            <td style="vertical-align: middle;">

                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn-ticket-status dropdown-toggle"
                                        id="priorityDropdown{{ $item->id }}" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">

                                        {{-- @if ($item->priority != 'high')
                                            <span class="custom-badge badge-active">
                                                {{ ucwords($item->priority) }}
                                            </span>
                                        @else
                                            <span class="custom-badge badge-inactive">
                                                {{ ucwords($item->priority) }}
                                            </span>
                                        @endif --}}

                                        @switch($item->priority)
                                            @case('high')
                                                <span class="custom-badge badge-inactive">
                                                    {{ ucwords($item->priority) }}
                                                </span>
                                            @break

                                            @case('critical')
                                                <span class="custom-badge badge-inactive">
                                                    {{ ucwords($item->priority) }}
                                                </span>
                                            @break

                                            @default
                                                <span class="custom-badge badge-active">
                                                    {{ ucwords($item->priority) }}
                                                </span>
                                        @endswitch
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="priorityDropdown{{ $item->id }}">
                                        <a class="dropdown-item change-priority" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.priority.update', ['priority' => 'low', 'id' => $item->id]) }}">
                                            Low
                                        </a>

                                        <a class="dropdown-item change-priority" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.priority.update', ['priority' => 'medium', 'id' => $item->id]) }}">
                                            Medium
                                        </a>

                                        <a class="dropdown-item change-priority" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.priority.update', ['priority' => 'high', 'id' => $item->id]) }}">
                                            High
                                        </a>
                                        <a class="dropdown-item change-priority" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.priority.update', ['priority' => 'critical', 'id' => $item->id]) }}">

                                            Critical
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align: middle;">

                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn-ticket-status dropdown-toggle no-arrow"
                                        id="statusDropdown{{ $item->id }}" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">

                                        @switch($item->status)
                                            @case('under review')
                                                <span class="badge badge-warning radius">Under Review</span>
                                            @break

                                            @case('in progress')
                                                <span class="badge badge-primary radius">In Progress</span>
                                            @break

                                            @case('on hold')
                                                <span class="badge badge-secondary radius">On Hold</span>
                                            @break

                                            @case('completed')
                                                <span class="badge badge-success radius">Completed</span>
                                            @break

                                            @case('cancelled')
                                                <span class="badge badge-danger radius">Cancelled</span>
                                            @break

                                            @case('closed')
                                                <span class="badge badge-dark radius">Closed</span>
                                            @break
                                        @endswitch
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="statusDropdown{{ $item->id }}">
                                        <a class="dropdown-item change-status" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.status.update', ['status' => 'under review', 'id' => $item->id]) }}">
                                            <span>Under Review</span>
                                        </a>

                                        <a class="dropdown-item change-status" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.status.update', ['status' => 'in progress', 'id' => $item->id]) }}">

                                            <span>In Progress</span>
                                        </a>

                                        <a class="dropdown-item change-status" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.status.update', ['status' => 'on hold', 'id' => $item->id]) }}">
                                            <span>On Hold</span>
                                        </a>

                                        <a class="dropdown-item change-status" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.status.update', ['status' => 'completed', 'id' => $item->id]) }}">
                                            <span>Completed</span>
                                        </a>

                                        <a class="dropdown-item change-status" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.status.update', ['status' => 'cancelled', 'id' => $item->id]) }}">
                                            <span>Cancelled</span>
                                        </a>

                                        <a class="dropdown-item change-status" href="javascript:void(0);"
                                            data-url="{{ route('admin.ticket.status.update', ['status' => 'closed', 'id' => $item->id]) }}">
                                            <span>Closed</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>

                        </tr>
                    @endforeach
            </table>

        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>

                        <th>
                            Role
                        </th>
                        <th>
                            User Type
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?mode=' . (request()->get('mode', 'Live') === 'Live' ? 'Testing' : 'Live')
                                : request()->fullUrlWithQuery([
                                    'mode' => request()->get('mode', 'Live') === 'Live' ? 'Testing' : 'Live',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
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
                                ? request()->url() . '?sort_device=' . (request()->get('sort_device', 'asc') === 'asc' ? 'desc' : 'asc')
                                : request()->fullUrlWithQuery([
                                    'sort_device' => request()->get('sort_device', 'asc') === 'asc' ? 'desc' : 'asc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>
                        <th>
                            Created At
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?sort_date=' . (request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc')
                                : request()->fullUrlWithQuery([
                                    'sort_date' => request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>
                        <th>Action</th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="text-center">No records found</td>
                    </tr>
            </table>
        </div>
    @endif
</form>
<div class="d-flex justify-content-end mt-5 mr-1">
    {{ $data->withQueryString()->links() }}
</div>

<script>
    $(document).on('click', '.change-priority', function() {

        let $btn = $(this); // store the clicked button
        let url = $btn.data('url');
        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to change the priority?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
                showLoader();


            }

        });

    });
</script>

<script>
    $(document).on('click', '.change-status', function() {
        let $btn = $(this); // store the clicked button
        let url = $btn.data('url');
        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to change the status?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
                showLoader();


            }

        });





    });
</script>
