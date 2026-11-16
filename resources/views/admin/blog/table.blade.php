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
<form id="tbl-form" method="POST" action="{{ route('admin.blog.bulk.action') }}">
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
                            Title
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Sub Category
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Created At
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?date=' . (request()->get('date', 'desc') === 'desc' ? 'asc' : 'desc')
                                : request()->fullUrlWithQuery([
                                    'date' => request()->get('date', 'desc') === 'desc' ? 'asc' : 'desc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>


                        <th>
                            Action
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

                                {{ Str::limit($item->title, 30) }}
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $item->category }}
                            </td>

                            <td style="vertical-align: middle;">
                                {{ $item->sub_category }}
                            </td>


                            <td style="vertical-align: middle;">
                                <a href="javascript:void(0)" data-url="{{ route('admin.blog.status', $item->id) }}"
                                    title="Change user status" class="change-status">
                                    @if ($item->status)
                                        <span class="custom-badge badge-active">Active</span>
                                    @else
                                        <span class="custom-badge badge-inactive">Inactive</span>
                                    @endif

                                </a>

                            </td>
                            <td style="vertical-align: middle;">
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                <a class="btn btn-sm btn-info edit-category shadow text-white"
                                    data-url="{{ route('admin.blog.edit', $item->id) }}">
                                    Edit
                                </a>
                                <a data-url="{{ route('admin.blog.destroy', $item->id) }}"
                                    class="btn-delete btn btn-sm btn-danger shadow text-white">
                                    Delete
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
                            <input type="checkbox" class="custom-checkbox" id="selectAll">
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Sub Category
                        </th>
                        <th>
                            Status
                        </th>

                        <th>
                            Created At
                            <a href="{{ request()->get('page', 1) > 1
                                ? request()->url() . '?date=' . (request()->get('date', 'desc') === 'desc' ? 'asc' : 'desc')
                                : request()->fullUrlWithQuery([
                                    'date' => request()->get('date', 'desc') === 'desc' ? 'asc' : 'desc',
                                ]) }}"
                                class="text-primary ms-1">↑↓</a>
                        </th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center">No records found</td>
                    </tr>
            </table>
        </div>
    @endif
</form>
<div class="d-flex justify-content-end mt-5 mr-1">
    {{ $data->links() }}
</div>

<script>
    $(document).on('click', '.change-status', function() {
        let $btn = $(this); // store the clicked button
        let url = $btn.data('url');
        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to change status?",
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

    $(document).on('click', '.btn-delete', function() {
        let $btn = $(this); // store the clicked button
        let url = $btn.data('url');
        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
                showLoader();


            }

        });

    });
</script>
