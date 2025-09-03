<style>
    .custom-checkbox {
        width: 18px;
        height: 18px;
        position: relative;
        cursor: pointer;
    }

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
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Active <br> Devices
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
                            <input type="checkbox" class="row-checkbox custom-checkbox" name="user_ids[]"
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
                            @if ($user->is_active)
                                {{-- <label class="custom-switch">
                                    <input type="checkbox" checked>
                                    <span class="custom-slider"></span>
                                </label> --}}
                                <span class="custom-badge badge-active">Active</span>
                            @else
                                {{-- <label class="custom-switch">
                                    <input type="checkbox">
                                    <span class="custom-slider"></span>
                                </label> --}}
                                <span class="custom-badge badge-inactive">Inactive</span>
                            @endif

                        </td>
                        <td style="vertical-align: middle;">
                            <a href="{{ route('admin.users.connected.devices', $user->id) }}">
                                @if ($user->rooms_count > 0)
                                    {{ $user->sessions_count ?? 0 }} / {{ $user->rooms_count ?? 0 }}
                                @else
                                    {{ $user->sessions_count ?? 0 }} / 5
                                @endif
                            </a>

                        </td>
                        <td style="vertical-align: middle;">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td style="vertical-align: middle;">
                            <a class="badge badge-light" title="Edit User"
                                href="{{ route('admin.users.edit', $user->id) }}">
                                <span class="text-black"> Edit</span>

                            </a>
                            <a href="{{ route('admin.users.delete', $user->id) }}" title="Delete User"
                                data-url="{{ route('admin.users.delete', $user->id) }}"
                                class="badge badge-light btn-user-delete">
                                <span class="text-danger"> Delete</span>

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
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Active <br> Devices
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
