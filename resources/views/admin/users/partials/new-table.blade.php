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

        <h3 class="card-title mt-2">Total Users : {{ $users->total() }}</h3>

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
    @if ($users->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="custom-checkbox" id="selectAll">
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
                    @foreach ($users as $key => $user)
                        <tr>
                            <td style="vertical-align: middle;">
                                <input type="checkbox" class="row-checkbox custom-checkbox"
                                    value="{{ $user->id }}">
                            </td>
                            <td>
                                <a href="{{ route('admin.users.view', $user->id) }}">
                                    <div class="mb-2"
                                        style="width:40px; height:40px;
                                            background:#d62d41;
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                        {{ getAvatarName($user->name) }}
                                    </div>


                                    <span> {{ ucwords(Str::limit($user->name, 10, '...')) }}</span>
                                </a>
                            </td>
                            <td style="vertical-align: middle;">

                                {{ limitEmailName($user->email, 10, '...') }}
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $user->roles->pluck('name')->join(', ') }}
                            </td>
                            <td style="vertical-align: middle;">
                                @if ($user->mode == 'Live')
                                    Live
                                @else
                                    Testing
                                @endif

                            </td>
                            <td style="vertical-align: middle;">
                                <a href="{{ route('admin.users.status', $user->id) }}"
                                    data-url="{{ route('admin.users.status', $user->id) }}" title="Change user status"
                                    class="btn-user-status">
                                    @if ($user->is_active)
                                        <span class="custom-badge badge-active">Active</span>
                                    @else
                                        <span class="custom-badge badge-inactive">Inactive</span>
                                    @endif

                                </a>

                            </td>
                            <td style="vertical-align: middle;">
                                {{-- @if (optional($user->roles->first())->name == 'hoa')
                                    {{ $user->hoa_houses_count ?? 0 }} / {{ $user->num_of_houses ?? 0 }}
                                @endif
                                @if ($user->sessions_count == 0)
                                    @if ($user->is_active)
                                        @if (optional($user->roles->first())->name == 'tieruser')
                                            {{ $user->sessions_count ?? 0 }} / {{ $user->rooms_count ?? 0 }}
                                        @else
                                            {{ $user->sessions_count ?? 0 }} / 5
                                        @endif
                                    @else
                                        0
                                    @endif
                                @else
                                    <a href="{{ route('admin.users.connected.devices.new', $user->id) }}">
                                        @if ($user->is_active)
                                            @if (optional($user->roles->first())->id == 5)
                                                {{ $user->sessions_count ?? 0 }} / {{ $user->rooms_count ?? 0 }}
                                            @else
                                                {{ $user->sessions_count ?? 0 }} / 5
                                            @endif
                                        @else
                                            0
                                        @endif

                                    </a>
                                @endif --}}

                                @switch(optional($user->roles->first())->name)
                                    @case('hoa')
                                        @if ($user->is_active == 1)
                                            @if ($user->hoa_houses_count > 0)
                                                <a href="{{ route('admin.hoa.sub-users', $user->id) }}">
                                                    {{ $user->hoa_houses_count ?? 0 }} / {{ $user->num_of_houses }}
                                                </a>
                                            @else
                                                {{ $user->hoa_houses_count ?? 0 }} / {{ $user->num_of_houses }}
                                            @endif
                                        @else
                                            0
                                        @endif
                                    @break

                                    @case('tieruser')
                                        @if ($user->is_active == 1)
                                            @if ($user->sessions_count > 0)
                                                <a href="{{ route('admin.users.connected.devices.new', $user->id) }}">
                                                    {{ $user->sessions_count ?? 0 }} / {{ $user->rooms_count ?? 0 }}

                                                </a>
                                            @else
                                                0 / {{ $user->rooms_count ?? 0 }}
                                            @endif
                                        @else
                                            0
                                        @endif
                                    @break

                                    @default
                                        @if ($user->is_active == 1)
                                            @if ($user->sessions_count > 0)
                                                <a href="{{ route('admin.users.connected.devices.new', $user->id) }}">
                                                    {{ $user->sessions_count }} / 5
                                                </a>
                                            @else
                                                0 / 5
                                            @endif
                                        @else
                                            0
                                        @endif
                                @endswitch


                            </td>
                            <td style="vertical-align: middle;">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td style="vertical-align: middle;">
                                <a class="badge badge-light" title="Edit User"
                                    href="{{ route('admin.users.edit', $user->id) }}">
                                    <span class="text-black">Edit</span>

                                </a>
                                <a href="{{ route('admin.users.delete', $user->id) }}" title="Delete User"
                                    data-url="{{ route('admin.users.delete', $user->id) }}"
                                    class="badge badge-light btn-user-delete">
                                    <span class="text-danger">Delete</span>

                                </a>
                                <a href="{{ route('admin.users.activity', $user->id) }}" title="Activities"
                                    class="badge badge-light">
                                    <span>Activity</span>

                                </a>
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
    {{ $users->withQueryString()->links() }}
</div>
