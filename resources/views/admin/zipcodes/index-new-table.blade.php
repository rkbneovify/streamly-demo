@if (count($zipcode) > 0)
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

    <h3 class="card-title mt-2">Total : {{ $zipcode->total() }}</h3>

    </div>
    <div class="col-md-2 mb-3">
    <a href="javascript:void(0)" id="btn-show-selected">
    <h3 id="selected" class="card-title mt-2"></h3>
    </a>

    </div>


    </div>
    </div>
    <div class="card-body p-0">
    <table class="table">
    <thead>
    <tr>
    <th>
    <input type="checkbox" class="custom-checkbox" id="selectAll">
    </th>
    <th>Id</th>
    <th>City</th>
    <th>State</th>
    <th>Zipcode</th>
    <th>Status</th>
    <th>Action</th>
    </tr>
    </thead>
    @foreach ($zipcode as $key => $item)
    <tr>
    <td>
    <input type="checkbox" class="row-checkbox custom-checkbox"
    value="{{ $item->id }}">
    </td>
    <td>{{ $item->id }}</td>
    <td>{{ $item->city??"N/A" }}</td>
    <td>{{ $item->state??"N/A" }}</td>
    <td>{{ $item->zipcode??"N/A" }}</td>
    <td>
    @if ($item->status == 1)
    <span class="custom-badge badge-active">Active</span>
    @else
    <span class="custom-badge badge-inactive">Inactive</span>
    @endif
    </td>
    <td>
    <a class="btn btn-xs btn-primary" href="/admin/zipcode/view/{{ $item->id }}">View</a>
    <a class="btn btn-xs btn-info" href="/admin/zipcode/edit/{{ $item->id }}">Edit</a>
    </td>

    </tr>
    @endforeach
    </table>
    <div class="d-flex justify-content-end mt-5 mr-1">
    {{ $zipcode->withQueryString()->links() }}
    </div>
    </div>

@else
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

    <h3 class="card-title mt-2">Total : 0</h3>

    </div>
    <div class="col-md-2 mb-3">
    <a href="javascript:void(0)" id="btn-show-selected">
    <h3 id="selected" class="card-title mt-2"></h3>
    </a>

    </div>


    </div>
    </div>
    <div class="card-body p-0">
    <table class="table ">
    <thead>
    <tr>
    <th>
    <input type="checkbox" class="custom-checkbox" id="selectAll">
    </th>
    <th>Id</th>
    <th>City</th>
    <th>State</th>
    <th>Zipcode</th>
    <th>Status</th>
    <th>Action</th>
    </tr>
    </thead>
    <tr>
        <td colspan="7" class="text-center">
            Data not found !
        </td>

    </tr>
    </table>

    </div>
@endif
