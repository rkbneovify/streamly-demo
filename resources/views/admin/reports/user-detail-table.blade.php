 <div class="table-responsive">
     <table class="table table-striped projects">
         <thead>
             <tr>
                 <th style="width: 1%">
                     S:No
                 </th>
                 <th style="width: 15%">
                     User Name
                 </th>
                 <th style="width: 5%">
                     Email
                 </th>
                 <th style="width: 20%">
                     No. connected device
                 </th>
                 <th style="width: 15%">
                     Suscription Status
                 </th>
                 <th style="width: 15%">
                     Register date
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
                         {{ $user->subscriptionStatus }}
                     </td>
                     <td>
                         {{ $user->user->email_verified_at ?? '' }}
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


 </div>
