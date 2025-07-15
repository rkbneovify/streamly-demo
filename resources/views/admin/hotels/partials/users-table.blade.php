@if ($allSession->count() > 0)
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Name
                </th>
                <th>
                    Email
                </th>
                <th>
                    No. hotel rooms
                </th>
                <th>
                    Created At
                </th>
                {{-- <th style="width: 30%">
                          Email
                      </th> --}}

                {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                <th>
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allSession as $key => $user)
                <tr data-entry-id="">
                    <td>
                        {{ $key + $allSession->firstItem() }}
                    </td>
                    <td>
                        {{ $user->user->name ?? '' }}
                    </td>
                    <td>
                        {{ $user->user->email ?? '' }}
                    </td>


                    <td>
                        {{ $user->activesession ?? '' }}
                    </td>
                    <td>
                        {{ $user->user->created_at ?? '' }}
                    </td>
                    {{-- <td class="project-state">
                            @if ($user->is_active === 1)
                            <span class="badge badge-success">Active</span>
@elseif ($user->is_active===0)
<span class="badge badge-danger">Inactive</span>
@else
    unknown
@endif
                          
                            
                          
                      </td> --}}
                    <td class="project-actions text-right">

                        <a class="btn btn-xs btn-primary" href="{{ url('admin/hotelroomsess', $user->user_id) }}">View</a>
                        {{-- <a class="btn btn-xs btn-info" href="{{ url('admin/hotelroomsess', $user->user_id) }}">Edit</a>
                          
                                    <form action="{{ url('admin/users/destroy', $user->users_id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form> --}}




                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="card-footer text-body-secondary">
        <div class="container px-5 mt-5">
            <div class="row justify-content-end">
                {{ $allSession->links() }}
            </div>
        </div>
    </div>
@else
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th style="width: 1%">
                    #
                </th>
                <th style="width: 20%">
                    Name
                </th>
                <th style="width: 20%">
                    Email
                </th>
                <th style="width: 20%">
                    No. hotel rooms
                </th>
                <th style="width: 20%">
                    Created At
                </th>
                {{-- <th style="width: 30%">
                          Email
                      </th> --}}

                {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                <th style="width: 20%">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height: 200px;" colspan="6" class="text-center">
                    <h3>Data not found !</h3>
                </td>
            </tr>
        </tbody>
    </table>
@endif
