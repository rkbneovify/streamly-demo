 @if ($allSession->count() > 0)
     <div class="container">
         <div class="table-responsive">
             <table class="table table-striped projects">
                 <thead>
                     <tr>
                         <th style="width: 1%">
                             #
                         </th>
                         <th>
                             Role
                         </th>
                         <th>
                             Name
                         </th>
                         <th>
                             Email
                         </th>
                         <th>
                             Mode
                         </th>
                         <th>
                             Active Devices
                         </th>

                         <th>
                             Created At
                         </th>
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
                                 {{ $user->user->roles->first()->display_name ?? '' }}
                             </td>
                             <td>
                                 {{ $user->user->name ?? '' }}
                             </td>
                             <td>
                                 {{ $user->user->email ?? '' }}
                             </td>
                             <td>
                                 {{ $user->mode_label ?? '' }}
                             </td>


                             <td>
                                 {{ $user->activesession ?? '' }}
                             </td>
                             <td>
                                 {{ date('d/m/Y', strtotime($user->user->created_at)) }}
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

                                 <a class="btn btn-xs btn-primary"
                                     href="{{ url('admin/users/connected', $user->users_id) }}">View</a>
                                 {{-- <a class="btn btn-xs btn-info" href="{{ url('admin/users/connected', $user->users_id) }}">Edit</a>
                          
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
         </div>

         <div class="card-footer text-body-secondary">
             <div class="container px-5 mt-5">
                 <div class="row justify-content-end">
                     {{ $allSession->links() }}
                 </div>
             </div>
         </div>

     </div>
 @else
     {{-- <style>
         .card {
             height: 100%;
         }

         .parent {
             display: flex;
             justify-content: center;
             /* horizontal */
             align-items: center;
             /* vertical */
             height: 45vh;
             /* full viewport height */
         }
     </style>
     <div class="parent">
         <h3>Data not found !</h3>
     </div> --}}

     <div class="container">
         <div class="table-responsive">
             <table class="table table-striped projects">
                 <thead>
                     <tr>
                         <th style="width: 1%">
                             #
                         </th>
                         <th>
                             Role
                         </th>
                         <th>
                             Name
                         </th>
                         <th>
                             Email
                         </th>
                         <th>
                             Active Devices
                         </th>

                         <th>
                             Created At
                         </th>
                         <th>
                             Action
                         </th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td style="height: 200px;" colspan="7" class="text-center">
                             <h3>Data not found !</h3>
                         </td>
                     </tr>
                 </tbody>
             </table>
         </div>


     </div>

 @endif
