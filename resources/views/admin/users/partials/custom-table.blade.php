 @if ($users->count() > 0)
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
                             Status
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
                     @foreach ($users as $key => $item)
                         <tr data-entry-id="">
                             <td>
                                 {{ $key + $users->firstItem() }}
                             </td>

                             <td>
                                 {{ $item->roles->first()->display_name ?? '' }}
                             </td>
                             <td>
                                 {{ $item->name ?? '' }}
                             </td>
                             <td>
                                 {{ $item->email ?? '' }}
                             </td>
                             <td>
                                 {{ $item->is_active == 1 ? 'Active' : 'Inactive' }}
                             </td>
                             <td>
                                 {{ date('d/m/Y', strtotime($item->created_at)) }}
                             </td>

                             <td class="text-start">
                                 @if ($item->is_live_on_stripe != 1)
                                     <a href="{{ route('admin.subscriptions.custom.view', $item->id) }}"
                                         class="btn btn-sm btn-link mb-1 w-75">Live Subscription</a>
                                     <br>
                                 @else
                                     <a href="{{ route('admin.report.subscription.detail', $item->id) }}"
                                         class="btn btn-sm btn-link mb-1 w-75">View Subscription</a>
                                     <br>
                                 @endif

                                 <a href="{{ route('admin.users.view', $item->id) }}"
                                     class="btn btn-sm btn-primary mb-1 w-75">View</a>
                                 <br>
                                 <a href="{{ route('admin.users.edit', $item->id) }}"
                                     class="btn btn-sm btn-info mb-1 w-75">

                                     Edit</a>
                                 <br>
                                 <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST"
                                     style="display:inline;"
                                     onsubmit="return confirm('Are you sure you want to delete this user?');">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="btn btn-sm btn-danger w-75">

                                         Delete</button>
                                 </form>



                             </td>

                         </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>

         <div class="card-footer text-body-secondary">
             <div class="container px-5 mt-5">
                 <div class="row justify-content-end">
                     {{ $users->links() }}
                 </div>
             </div>
         </div>

     </div>
 @else
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
