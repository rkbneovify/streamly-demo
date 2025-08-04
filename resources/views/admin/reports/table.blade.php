 <?php
 use Laravel\Cashier\Cashier;
 ?>
 <div class="table-responsive">

     <!-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> -->
     <table id="myTable" class="table table-striped projects">
         <thead>
             <tr>
                 <th style="width: 2%">
                     Sl. No.
                 </th>

                 <th style="width: 20%">
                     Date
                 </th>

                 <th style="width: 15%">
                     Name
                 </th>
                 <th style="width: 20%">
                     Email
                 </th>
                 <th style="width: 13%">
                     Status
                 </th>
                 <th style="width: 4%">
                     Amount
                 </th>
                 <th style="width: 4%">
                     View
                 </th>

             </tr>
         </thead>
         <tbody>
             @foreach ($alltransactions as $transaction)
                 <tr>
                     <td>{{ request()->input('page') > 1 ? (request()->input('page') - 1) * 10 + $loop->iteration : $loop->iteration }}
                     </td>
                     <td>{{ $transaction['created'] }}</td>
                     <td>{{ $transaction['name'] }}</td>
                     <td>{{ $transaction['email'] }}</td>
                     @if ($transaction['status'] == 'succeeded')
                         <td style="color: green;">
                             <span class="badge badge-success">{{ $transaction['status'] }}</span>
                         </td>
                     @else
                         <td style="color: red;">
                             <span class="badge badge-danger">Failed</span>
                         </td>
                     @endif
                     <td>
                         {{ Cashier::formatAmount($transaction['amount'] ?? '0') }}
                     </td>
                     <td class="zipcodeBox">
                         <a href="/admin/report/transactionsreport/viewtransaction/{{ $transaction['id'] }}">
                             <input type="button" id="zipActive" value=View>
                         </a>
                     </td>
                 </tr>
             @endforeach
         </tbody>
     </table>
 </div>
 <div class="row justify-content-between mb-5 mt-5 px-5">

     <div class="col-md-2 mb-2">
         @if ($previousPageUrl == null)
             <button class="btn btn-primary w-100" disabled>Previous</button>
         @else
             <a href="{{ $previousPageUrl }}" class="btn btn-primary w-100">Previous</a>
         @endif
     </div>
     <div class="col-md-2 text-right">
         @if ($nextPageUrl == null)
             <button class="btn btn-primary w-100" disabled>Next</button>
         @else
             <a href="{{ $nextPageUrl }}" class="btn btn-primary w-100">Next</a>
         @endif
     </div>

 </div>
