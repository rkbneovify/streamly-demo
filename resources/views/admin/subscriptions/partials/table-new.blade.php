 @if ($subscriptions->count() > 0)
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
                     Subscription
                     <br>
                     Type
                 </th>
                 <th style="width: 10%">
                     Status
                 </th>
                 <th style="width: 20%">
                     Subscribe At

                     <a href="{{ request()->fullUrlWithQuery([
                         'sort_subscribe_at' => request()->get('sort_subscribe_at', 'desc') === 'desc' ? 'asc' : 'desc',
                     ]) }}"
                         class="text-primary ms-1">↑↓</a>
                 </th>
                 <th style="width: 20%">
                     Subscription
                     <br>
                     Expire At
                 </th>


                 <th style="width: 20%">
                     Action
                 </th>
             </tr>
         </thead>
         <tbody>
             @foreach ($subscriptions as $key => $subscription)
                 <tr>
                     <td>
                         {{ $key + $subscriptions->firstItem() }}
                     </td>

                     <td>
                         <a href="{{ route('admin.users.view', $subscription->user->id) }}">
                             <div class="mb-2"
                                 style="width:40px; height:40px;
                                            background:{{ $key % 2 == 0 ? '#3498db' : '#e67e22' }};
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                 {{ getAvatarName($subscription->user->name) }}
                             </div>


                             {{ ucwords(Str::limit($subscription->user->name, 10, '...')) }}
                         </a>

                     </td>
                     <td>

                         {{ limitEmailName($subscription->user->email, 10, '...') }}
                     </td>

                     <td>
                         @if ($subscription->plan_duration != null)
                             Custom
                         @elseif($subscription->livemode)
                             Live
                         @else
                             Testing
                         @endif

                     </td>
                     <td>
                         {{ Str::ucfirst($subscription->stripe_status ?? '') }}
                     </td>


                     <td>{{ date('d/m/Y', strtotime($subscription->updated_at)) }}
                     </td>
                     <td>

                         @if ($subscription->renews_at != null)
                             {{ date('d/m/Y', strtotime($subscription->renews_at)) }}
                         @elseif ($subscription->ends_at != null)
                             {{ date('d/m/Y', strtotime($subscription->ends_at)) }}
                         @elseif ($subscription->trial_ends_at != null && $subscription->user->is_custom_user != 1)
                             {{ date('d/m/Y', strtotime($subscription->trial_ends_at)) }}
                         @else
                             <span class="badge badge-success">
                                 Never
                             </span>
                         @endif

                     </td>

                     <td>

                         <a class="btn btn-xs btn-outline-primary mb-1  w-100"
                             href="{{ route('admin.report.subscription.detail', $subscription->user->id) }}">
                             {{ trans('View') }}
                         </a>

                         <a class="btn btn-xs btn-info mb-1 w-100"
                             href="{{ url('admin/subscriptions/edit', $subscription->id) }}">
                             {{ trans('Edit') }}
                         </a>

                         <form action="{{ url('admin/subscriptiondestroy', $subscription->user_id) }}" method="POST"
                             onsubmit="return confirm('{{ trans('are You Sure') }}');" style="display: inline-block;">
                             <input type="hidden" name="_method" value="DELETE">
                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                             <input type="submit" class="btn btn-xs btn-danger w-100" value="Cancel">
                         </form>


                     </td>
                 </tr>
             @endforeach

         </tbody>
     </table>
     <div class="container px-5 mt-5">
         <div class="row justify-content-end">
             {{ $subscriptions->links() }}
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
                     Subscription
                 </th>
                 <th style="width: 20%">
                     Quantity
                 </th>
                 <th style="width: 20%">
                     Start Date
                 </th>
                 <th style="width: 20%">
                     Expiry Date
                 </th>

                 <th style="width: 10%">
                     Status
                 </th>
                 <th style="width: 20%">
                     Action
                 </th>
             </tr>
         </thead>
         <tbody>
             <tr>
                 <td colspan="9" class="text-center" style="height: 200px;">
                     <h3>Data not found.</h3>
                 </td>
             </tr>

         </tbody>
     </table>
 @endif
