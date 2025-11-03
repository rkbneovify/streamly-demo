@extends('master')
@section('stylesheets')
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
<style>
.select-wrapper {
width: 100%;
display: inline-flex;
align-items: center;
padding: 6px 10px;
border: 1px solid #ccc;
border-radius: 6px;
background-color: #fff;
cursor: pointer;
font-size: 14px;
}

.select-wrapper:hover {
background-color: #f9f9f9;
box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.select-wrapper img {
width: 20px;
height: 20px;
}

.select-custom {
width: 100%;
padding: 4px 10px;
border: none;
/* border-radius: 4px; */
font-size: 13.8px;
font-weight: 600;
color: #333;
outline: none;
transition: border-color 0.2s, box-shadow 0.2s;
/* Remove default arrow for most browsers */
appearance: none;
-webkit-appearance: none;
-moz-appearance: none;
background: none;
}

.select-custom:focus {
border-color: none;
box-shadow: none;
}

.btn-add-user {
text-decoration: none;
color: #333;
padding: 4px 10px;
border-radius: 4px;
font-size: 14px;
font-weight: 600;
}

.input-daterange {
width: 100%;
padding: 4px 10px;
border: none;
/* border-radius: 4px; */
font-size: 13.8px;
font-weight: 600;
color: #333;
outline: none;
transition: border-color 0.2s, box-shadow 0.2s;
/* Remove default arrow for most browsers */
appearance: none;
-webkit-appearance: none;
-moz-appearance: none;
background: none;
}

.custom-input {
width: 100%;
padding: 10px 40px 10px 15px;
border: none;
/* border-radius: 4px; */
font-size: 13.8px;
font-weight: 600;
color: #333;
outline: none;
transition: border-color 0.2s, box-shadow 0.2s;
/* Remove default arrow for most browsers */
appearance: none;
-webkit-appearance: none;
-moz-appearance: none;
background: none;
}
</style>
<style>
    .input-container {
        position: relative;


    }

    .btn-clear {
        cursor: pointer;
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 30px;
        color: #888;
        display: none;
    }

    .btn-clear:hover {
        color: #000;
    }

    .input-custom {

        padding: 20px 12px;
    }
</style>
@stop
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1>Zipcode</h1> {{ session()->get( 'info' ) }}
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="/">Home</a></li>
<li class="breadcrumb-item active">Zipcode</li>
</ol>
</div>
</div>
</div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
{{-- <a class="btn btn-xs btn-primary" href="{{ url('admin/subscriptions/create') }} ">
Add new zipcode
</a> --}}

<!-- Default box -->
<div class="card">
<div class="card-header">
    <div class="row mt-2 px-2">

        <div class="col-md-2 mb-2">
            <div class="select-wrapper">
                <img src="{{ asset('images/check-all-square.png') }}" alt="Bulk Action Icon">
                <select name="bulk_action" id="sel_bulk_action" class="select-custom">
                    <option value="">Bulk Action</option>
                    <option value="activate">Activate</option>
                    <option value="inactivate">Inactivate</option>

                </select>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="input-conatiner">
                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                    class="form-control input-custom" placeholder="Search by city, state">
                <span class="btn-clear" id="btnClear">×</span>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="select-wrapper">
                <img src="{{ asset('images/clear-filter.png') }}" alt="status icon">
                <a href="javascript:void(0)" class="btn-add-user btn-reset-all">Reset filters</a>
            </div>
        </div>

    </div>
    <div id="user-data">
        @include('admin.zipcodes.index-new-table')
    </div>
<!-- /.card-body -->
</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')

<script>
     $('#btnClear').hide();

function fetchData(page = 1) {

    let search = $('input[name="search"]').val();


    $.ajax({
        url: "{{ url()->current() }}" + "?" + window.location.search.replace(/^\?/, '') + "&search=" +
            search + "&page=" + page,
        success: function(data) {
            $('#user-data').html(data);

            // restoreSelectedData();

            // Set #selectAll checked only if all visible checkboxes are checked (current page)
            // let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
            //     '.row-checkbox').length;
            // $('#selectAll').prop('checked', allChecked);
            // console.log($('.row-checkbox:checked').length);


        }
    });



}



$(document).on('keyup', 'input[name="search"]', function() {
    let search = $('input[name="search"]').val();
    if (search.length > 0) {
        $('#btnClear').show();

    } else {
        $('#btnClear').hide();
        // loadSelected();

    }
    fetchData();

});
$('#btnClear').click(function() {

    $('input[name="search"]').val('');
    $('input[name="search"]').focus();
    fetchData();
    $('#btnClear').hide();

    // loadSelected();
})

$(document).on('click', '#btn-show-selected', function() {
    // loadSelected();
})


</script>

<script>
$(document).on('change', '#sel_limit', function(e) {
let url = new URL(window.location.href);
let params = url.searchParams;
let page = parseInt(params.get("page")) || 1;


if (page > 1) {
// reset URL (remove params except page)
url.search = "";
url.searchParams.delete("page");
url.searchParams.set("limit", $(this).val());
} else if ($(this).val() == "") {
url.searchParams.delete("limit");
} else {

url.searchParams.set("limit", $(this).val());
}

window.location.href = url.toString();
});

$(document).on('click', '.btn-reset-all', function(e) {
            e.preventDefault();
            // removeAll();
            location.href = "{{ url()->current() }}"
})

</script>
@stop
