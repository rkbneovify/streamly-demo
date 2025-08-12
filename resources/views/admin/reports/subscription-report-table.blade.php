  <div class="table-responsive">

      <!-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> -->
      <table id="myTable" class="table table-striped projects">
          <thead>
              <tr>
                  <th style="width: 2%">
                      Sl:No
                  </th>
                  <th style="width: 25%">
                      Subscription <br> Id
                  </th>
                  <th style="width: 25%">
                      Plan
                  </th>


                  <th style="width: 5%">
                      Email
                  </th>
                  <th style="width: 5%">
                      Zipcode
                  </th>
                  <th style="width: 20%">
                      Status
                  </th>
                  <th style="width: 25%">
                      Start Date
                  </th>
                  <th style="width: 25%">
                      Expiration Date
                  </th>

                  {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                  <!-- <th style="width: 20%">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </th> -->
              </tr>
          </thead>
          <tbody>
              @foreach ($transaction as $key => $item)
                  <tr>
                      <td>{{ $key + $transaction->firstItem() }}</td>
                      <td>
                          <a href="{{ route('admin.report.subscription.detail', $item->stripe_id) }}">
                              {{ $item->stripe_id }}
                          </a>
                      </td>
                      <td>{{ $item->plan_name }} <br> {{ $item->plan_desc }} </td>
                      <td>{{ $item->user->email }}</td>
                      <td>{{ $item->user->zipcode }}</td>
                      <td>{{ $item->stripe_status }}</td>
                      <td>{{ $item->created_at }}</td>
                      <td>{{ $item->trial_ends_at ?? ($item->renews_at ?? $item->ends_at) }}</td>
                  </tr>
              @endforeach
          </tbody>
      </table>

      <div class="card-footer text-body-secondary">
          <div class="container px-5 mt-5">
              <div class="row justify-content-end">
                  {{ $transaction->links() }}
              </div>
          </div>
      </div>
      {{--  --}}
  </div>
