@extends('master')
@section('stylesheets')
    <link href="{{ asset('dist/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <style>
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 25px;
        }

        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            /* Grey when inactive */
            transition: 0.4s;
            border-radius: 25px;
        }

        .custom-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 3.5px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.custom-slider {
            background-color: #28a745;
            /* Green when active */
        }

        input:checked+.custom-slider:before {
            transform: translateX(24px);
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
@endsection
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Blog Categories</h1>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blog Categories</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">


                    <!-- row -->
                    <div class="row  mt-3 mb-0 mb-3">

                        <div class="col-md-2 mb-2">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/check-all-square.png') }}" alt="Bulk Action Icon">
                                <select name="bulk_action" id="sel_bulk_action" class="select-custom">
                                    <option value="">Bulk Action</option>
                                    <option value="active">Active</option>
                                    <option value="deactive">Deactive</option>

                                </select>
                            </div>
                        </div>



                        <div class="col-md-2 mb-3">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/user-mode.png') }}" alt="status icon">
                                <select name="status" id="sel_status" class="select-custom">
                                    <option value="">Select Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                        Deactive</option>

                                </select>
                            </div>
                        </div>





                        <div class="col-md-2 mb-3">
                            <button id="btnResetFilter" type="button" class="btn-with-img">
                                <img src="{{ asset('images/clear-filter.png') }}" alt="status icon">
                                Reset filters
                            </button>
                        </div>

                        <div class="col-md-2 mb-3">
                            <a href="#" class="btn btn-primary shadow" data-toggle="modal" data-target="#addCategoryModal">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="input-conatiner">
                                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                                    class="form-control input-custom" placeholder="Search category">
                                <span class="btn-clear" id="btnClear">×</span>
                            </div>
                        </div>

                    </div>
                    <!-- row -->



                </div>


                <div id="user-data">
                    @include('admin.blog.category.table')
                </div>

                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Edit Category -->
    @include('admin.blog.category.model')
@endsection




@section('scripts')

<script>

    $(document).on('click', '.edit-category', function () {

        let url = $(this).data('url');
        let name = $(this).data('name');

        $('#edit_category_name').val(name);

        $('#frmEditCategory').attr(
            'action',
            url
        );

    });


    $('#btnEditCategoryCancel').click(function (e) {
        e.preventDefault();
        location.reload();
    })
    $('#btnAddCategoryCancel').click(function (e) {
        e.preventDefault();
        location.reload();
    })

    $('#frm_category_store').on('submit', function (e) {
        e.preventDefault();
        showLoader();


        this.submit();
    });
    $('#frmEditCategory').on('submit', function (e) {
        e.preventDefault();
        showLoader();


        this.submit();
    });
</script>

<script>
    let storageKey = "selected_blog_categories";

    // Get selected IDs from localStorage
    function getSelected() {
        return JSON.parse(localStorage.getItem(storageKey)) || [];
    }

    // Save to localStorage
    function saveSelected(ids) {
        localStorage.setItem(storageKey, JSON.stringify(ids));
    }

    function removeAll() {
        localStorage.removeItem(storageKey);
        restoreSelectedData();
    }

    function restoreSelectedData() {
        let selected = getSelected();
        if (selected.length > 0) {
            document.getElementById('selected').textContent = "Selected : " + selected.length;
        } else {
            document.getElementById('selected').textContent = "";
        }

        const userCheckboxes = document.querySelectorAll('.row-checkbox');
        userCheckboxes.forEach(checkbox => {
            selected.includes(checkbox.value) ? checkbox.checked = true : checkbox.checked = false;

        });
        let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
            '.row-checkbox').length;
        $('#selectAll').prop('checked', allChecked);
    }

    function loadSelected() {
        let selected = getSelected();

        // Start with the current URL (preserve existing params)
        const url = new URL(window.location.href);

        // Remove old selected[] params
        url.searchParams.delete("selected[]");

        // Add only currently selected ID
        selected.reverse().forEach(id => {
            url.searchParams.append("selected[]", id);
        });

        // Reload with updated query string (keeping other params intact)
        window.location.href = url.toString();
    }



    $(document).on('change', '.row-checkbox', function () {
        let selected = getSelected();
        if ($(this).is(':checked')) {
            if (!selected.includes($(this).val())) {
                selected.push($(this).val());
                saveSelected(selected);

            }

        } else {


            selected = selected.filter(id => id !== $(this).val());
            saveSelected(selected);

        }

        restoreSelectedData();


    });

    $(document).on('change', '#selectAll', function () {
        let selected = getSelected();
        const userCheckboxes = $('.row-checkbox');
        if ($(this).is(':checked')) {
            userCheckboxes.each(function () {
                this.checked = true;
                if (!selected.includes(this.value)) {
                    selected.push(this.value);
                }
            });
        } else {
            userCheckboxes.each(function () {
                this.checked = false;
                if (selected.includes(this.value)) {
                    selected = selected.filter(id => id !== this.value);
                }

            });

        }
        saveSelected(selected);
        restoreSelectedData();

    });
</script>
<script>
    $(document).ready(function () {


        let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
            '.row-checkbox').length;
        $('#selectAll').prop('checked', allChecked);

        $(document).on('click', '.btn-user-status', function (e) {
            e.preventDefault();

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
                // else {
                //     loadSelected();
                // }
            });


        });
    });

    $(document).on('click', '.btn-user-delete', function (e) {
        e.preventDefault();

        let $btn = $(this); // store the clicked button
        let url = $btn.data('url');

        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this user?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {

                showLoader();
                // Redirect to URL
                window.location.href = url;
            }

            // else {
            //     loadSelected();
            // }
        });
    });
    $(document).on('change', '#sel_bulk_action', function (e) {
        e.preventDefault();
        let selectedIds = $('.row-checkbox:checked').map(function () {
            return $(this).val();
        }).get();
        if (selectedIds.length == 0) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Please select at least one category',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                location.reload(); // reload after toast timeout
            });

        } else {

            let action = $(this).val();
            if (!action) {
                return; // No action selected
            }
            Swal.fire({
                title: "Are you sure?",
                text: `Do you really want to ${action} selected category?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: `Yes, ${action} them!`,
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    let selected = getSelected();

                    // Set action value in hidden input
                    $('input[name="action"]').val(action);

                    $.each(selected, function (index, value) {
                        $("#tbl-form").append(
                            `<input type="hidden" name="categories[]" value="${value}">`
                        );
                    });

                    $('#tbl-form').submit();
                    showLoader();
                } else {
                    loadSelected();
                }
            });
        }
    });




    $(document).on('change', '#sel_status', function (e) {
        let url = new URL(window.location.href);
        let params = url.searchParams;
        let page = parseInt(params.get("page")) || 1;


        if (page > 1) {
            // reset URL (remove params except page)
            url.search = "";
            url.searchParams.delete("page");
            url.searchParams.set("status", $(this).val());
        } else if ($(this).val() == "") {
            url.searchParams.delete("status");
        } else {
            // just add role param
            url.searchParams.set("status", $(this).val());
        }

        window.history.pushState({}, '', url.toString());
        window.location.href = url.toString();
    });

    $(document).on('change', '#sel_priority', function (e) {
        let url = new URL(window.location.href);
        let params = url.searchParams;
        let page = parseInt(params.get("page")) || 1;


        if (page > 1) {
            // reset URL (remove params except page)
            url.search = "";
            url.searchParams.delete("page");
            url.searchParams.set("priority", $(this).val());
        } else if ($(this).val() == "") {
            url.searchParams.delete("priority");
        } else {
            // just add role param
            url.searchParams.set("priority", $(this).val());
        }

        window.history.pushState({}, '', url.toString());
        window.location.href = url.toString();
    });


    $(document).on('change', '#sel_limit', function (e) {
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

        window.history.pushState({}, '', url.toString());
        window.location.href = url.toString();
    });
</script>

<script>
    $('#btnClear').hide();

    function fetchData(page = 1) {

        let search = $('input[name="search"]').val();


        $.ajax({
            url: "{{ url()->current() }}" + "?" + window.location.search.replace(/^\?/, '') + "&search=" +
                search + "&page=" + page,
            success: function (data) {
                $('#user-data').html(data);

                restoreSelectedData();

                // Set #selectAll checked only if all visible checkboxes are checked (current page)
                let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
                    '.row-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
                console.log($('.row-checkbox:checked').length);


            }
        });



    }



    $(document).on('keyup', 'input[name="search"]', function () {
        let search = $('input[name="search"]').val();
        if (search.length > 0) {
            $('#btnClear').show();

        } else {
            $('#btnClear').hide();
            loadSelected();

        }
        fetchData();

    });

    $('#btnClear').click(function () {

        $('input[name="search"]').val('');
        $('input[name="search"]').focus();
        fetchData();
        $('#btnClear').hide();

        loadSelected();
    })

    $(document).on('click', '#btn-show-selected', function () {
        loadSelected();
    })

    $(document).on('click', '#btnResetFilter', function (e) {
        e.preventDefault();
        removeAll();
        let url = "{{ url()->current() }}";
        window.history.pushState({}, '', url);
        window.location.href = url.toString();
    })
</script>

@if (session('remove'))
    <script>
        loadSelected();
    </script>
    {{-- @elseif (session('remove-all'))
    <script>
        removeAll();
    </script> --}}
@elseif (!session('remove') && !session('remove-all'))
    <script>
        window.addEventListener("load", () => {
            const [nav] = performance.getEntriesByType("navigation");
            const hasParams = window.location.search.length > 0;

            if (nav.type !== "reload" && !hasParams) {
                removeAll();

            }
        });
    </script>
@endif
@stop