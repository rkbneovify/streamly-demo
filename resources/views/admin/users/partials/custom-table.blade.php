 @if ($users->count() > 0)
     <form id="tbl-form" method="POST" action="{{ route('admin.subscriptions.users.bulk.action') }}">
         @csrf
         <input type="hidden" name="action">
         <div class="container">
             <div class="table-responsive">
                 <table class="table table-striped projects">
                     <thead>
                         <tr>
                             {{-- <th>
                                 <input type="checkbox" class="form-control" id="selectAll">
                             </th> --}}
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
                                 Role
                             </th>
                             <th>
                                 Subscription <br> Type
                             </th>
                             <th>
                                 Status
                             </th>
                             <th>
                                 Subscription <br> Expires At
                             </th>
                             <th>
                                 Subscription At
                             </th>
                             <th>
                                 Action
                             </th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($users as $key => $item)
                             <tr data-entry-id="">
                                 {{-- <td><input id="cbUserId" type="checkbox" name="user_ids[]"
                                         class="form-control row-checkbox" value="{{ $item->id }}">
                                 </td> --}}
                                 <td>
                                     {{ $key + $users->firstItem() }}
                                 </td>

                                 <td>
                                     <a href="{{ route('admin.users.view', $item->id) }}">
                                         <div class="mb-2"
                                             style="width:40px; height:40px;
                                            background:{{ $key % 2 == 0 ? '#3498db' : '#e67e22' }};
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                             {{ getAvatarName($item->name) }}
                                         </div>


                                         {{ ucwords(Str::limit($item->name, 10, '...')) }}
                                     </a>
                                 </td>
                                 <td>

                                     {{ limitEmailName($item->email, 10, '...') }}
                                 </td>


                                 <td>
                                     {{ ucfirst($item->roles->first()->display_name) ?? '' }}
                                 </td>
                                 <td>
                                     @if ($item->is_live_on_stripe == 1)
                                         Live
                                     @else
                                         Custom
                                     @endif

                                 </td>
                                 <td>
                                     {{ ucfirst($item->customSubscription->stripe_status) }}
                                 </td>
                                 <td>
                                     @if ($item->customSubscription->renews_at != null)
                                         {{ date('d/m/Y', strtotime($item->customSubscription->renews_at)) }}
                                     @elseif ($item->customSubscription->ends_at != null)
                                         {{ date('d/m/Y', strtotime($item->customSubscription->ends_at)) }}
                                     @elseif ($item->customSubscription->trial_ends_at != null)
                                         {{ date('d/m/Y', strtotime($item->customSubscription->trial_ends_at)) }}
                                     @else
                                         <span class="badge badge-success">
                                             Never
                                         </span>
                                     @endif

                                 </td>
                                 <td>
                                     {{ date('d/m/Y', strtotime($item->customSubscription->updated_at)) }}
                                 </td>
                                 <td style="width: 150px;">

                                     @if ($item->is_live_on_stripe != 1)
                                         <a href="{{ route('admin.subscriptions.custom.view', $item->id) }}"
                                             class="btn btn-sm btn-link mb-1 w-75">Update Subscription</a>
                                         <br>
                                         <a href="{{ route('admin.subscriptions.user.mail.upgrade', $item->id) }}"
                                             class="btn btn-sm btn-secondary mb-1 w-75 send-mail-btn"
                                             data-url="{{ route('admin.subscriptions.user.mail.upgrade', $item->id) }}">
                                             <span class="btn-text">Send Mail</span>
                                             <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                                                 aria-hidden="true"></span>
                                         </a>
                                     @elseif ($item->is_live_on_stripe == 1 && $item->customSubscription->stripe_status == 'canceled')
                                         <a href="{{ route('admin.subscriptions.custom.view', $item->id) }}"
                                             class="btn btn-sm btn-link mb-1 w-75">Update Subscription</a>
                                         <br>
                                         <a href="{{ route('admin.subscriptions.user.mail.upgrade', $item->id) }}"
                                             class="btn btn-sm btn-secondary mb-1 w-75 send-mail-btn"
                                             data-url="{{ route('admin.subscriptions.user.mail.upgrade', $item->id) }}">
                                             <span class="btn-text">Send Mail</span>
                                             <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                                                 aria-hidden="true"></span>
                                         </a>
                                     @else
                                         @if ($item->customSubscription->stripe_status == 'active')
                                             <a href="{{ route('admin.report.subscription.detail', $item->id) }}"
                                                 class="btn btn-sm btn-link w-75">View Subscription</a>
                                             {{-- <a href="{{ route('admin.subscriptions.user.cancel', $item->id) }}"
                                                 class="btn btn-sm btn-link  w-75 cancel-subscription-btn"
                                                 data-url="{{ route('admin.subscriptions.user.cancel', $item->id) }}">
                                                 <span class="btn-text">Cancel Subscription</span>
                                                 <span class="spinner-border spinner-border-sm ms-2 d-none"
                                                     role="status" aria-hidden="true"></span>
                                             </a> --}}
                                         @endif
                                     @endif




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
     </form>
 @else
     <div class="container">
         <div class="table-responsive">
             <table class="table table-striped projects">
                 <thead>
                     <tr>
                         {{-- <th>
                                 <input type="checkbox" class="form-control" id="selectAll">
                             </th> --}}
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
                             Role
                         </th>
                         <th>
                             Subscription <br> Type
                         </th>
                         <th>
                             Status
                         </th>
                         <th>
                             Subscription <br> Expires At
                         </th>
                         <th>
                             Subscription At
                         </th>
                         <th>
                             Action
                         </th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td style="height: 200px;" colspan="9" class="text-center">
                             <h3>Data not found !</h3>
                         </td>
                     </tr>
                 </tbody>
             </table>
         </div>


     </div>

 @endif

 <script>
     $(document).on('click', '.send-mail-btn', function(e) {
         e.preventDefault();

         let $btn = $(this); // store the clicked button
         let url = $btn.data('url');

         Swal.fire({
             title: "Are you sure?",
             text: "Do you really want to send this mail?",
             icon: "warning",
             showCancelButton: true,
             confirmButtonText: "Yes, send it!",
             cancelButtonText: "Cancel",
         }).then((result) => {
             if (result.isConfirmed) {

                 showLoader();
                 // Redirect to URL
                 window.location.href = url;
             } else {
                 location.reload();
             }
         });
     });
 </script>

 <script>
     $(document).on('click', '.cancel-subscription-btn', function(e) {
         e.preventDefault();

         let $btn = $(this); // store the clicked button
         let url = $btn.data('url');

         Swal.fire({
             title: "Are you sure?",
             text: "Do you really want to cancel this subscription?",
             icon: "warning",
             showCancelButton: true,
             confirmButtonText: "Yes, send it!",
             cancelButtonText: "Cancel",
         }).then((result) => {
             if (result.isConfirmed) {

                 showLoader();
                 // Redirect to URL
                 window.location.href = url;
             } else {
                 location.reload();
             }
         });
     });
 </script>

 <script>
     document.getElementById('selectAll').addEventListener('change', function() {
         const isChecked = this.checked;
         document.querySelectorAll('.row-checkbox').forEach(cb => {
             cb.checked = isChecked;
         });
     });
 </script>
