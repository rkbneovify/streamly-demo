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
             @foreach ($subscriptions as $key => $subscription)
                 <tr>
                     <td>#</td>

                     <td>


                         {{ $subscription->user->name ?? '' }}


                     </td>
                     <td>{{ $subscription->user->email ?? '' }} </td>



                     <td>

                         {{ $subscription->stripe_plan }}
                         @if ($subscription->billing_scheme == 'tiered')
                             <span class="badge badge-success">Tiers Plan</span>
                         @endif


                     </td>
                     <td>{{ $subscription->quantity }}</td>
                     <td>{{ date('d/m/Y', strtotime($subscription->created_at)) }}</td>
                     <td>{{ date('d/m/Y', strtotime($subscription->renews_at)) }}</td>
                     <td>
                         {{ $subscription->stripe_status ?? '' }}
                     </td>
                     <td>

                         <a class="btn btn-xs btn-primary mb-1  w-100"
                             href="{{ url('admin/subscriptions/view', $subscription->id) }}">
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
