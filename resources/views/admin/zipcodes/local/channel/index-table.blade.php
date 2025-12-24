  <div class="row mt-2 px-2">
      <div class="col-md-2 mb-3">
          <div class="select-wrapper">
              <img src="{{ asset('images/user-mode.png') }}" alt="status icon">
              <select name="limit" id="sel_limit" class="select-custom">
                  <option value="10" {{ request()->limit == 10 ? 'selected' : '' }}>Show 10</option>
                  <option value="30" {{ request()->limit == 30 ? 'selected' : '' }}>Show 30</option>
                  <option value="50" {{ request()->limit == 50 ? 'selected' : '' }}>Show 50</option>
                  <option value="100" {{ request()->limit == 100 ? 'selected' : '' }}>Show 100</option>
              </select>
          </div>
      </div>
      <div class="col-md-2 mb-3">

          <h3 class="card-title mt-2">Total Zipcode : {{ $zipcodes->total() }}</h3>

      </div>
      <div class="col-md-2 mb-3">
          <a href="javascript:void(0)" id="btn-show-selected">
              <h3 id="selected" class="card-title mt-2"></h3>
          </a>

      </div>

  </div>
  <form id="tbl-form" method="POST" action="{{ route('admin.channel.bulk.action') }}">
      @csrf
      <input hidden type="text" name="action">

      @if ($zipcodes->count() > 0)
          <div class="table-responsive">
              <table class="table">
                  <thead>
                      <tr>

                          <th>#</th>
                          <th>Channel Id</th>
                          <th>Channel name</th>
                          <th>Zipcode</th>
                          <th>City</th>
                          <th>State</th>
                          <th>Status
                              <a href="{{ request()->get('page', 1) > 1
                                  ? request()->url() . '?status=' . (request()->get('status', '1') === '1' ? '0' : '1')
                                  : request()->fullUrlWithQuery([
                                      'status' => request()->get('status', '1') === '1' ? '0' : '1',
                                  ]) }}"
                                  class="text-primary ms-1">↑↓</a>

                          </th>

                          <th>Created At
                              <a href="{{ request()->get('page', 1) > 1
                                  ? request()->url() . '?created=' . (request()->get('sort_created', 'asc') === 'desc' ? 'asc' : 'desc')
                                  : request()->fullUrlWithQuery([
                                      'created' => request()->get('created', 'asc') === 'desc' ? 'asc' : 'desc',
                                  ]) }}"
                                  class="text-primary ms-1">↑↓</a>
                          </th>
                          {{-- <th>Action</th> --}}
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($zipcodes as $key => $item)
                          <tr>

                              <td style="vertical-align: middle;">{{ $key + 1 }}</td>
                              <td style="vertical-align: middle;">{{ $item->channel_id }}</td>
                              <td style="vertical-align: middle;">
                                  {{ $item->channel->name ?? ($item->channel->callSign ?? $item->channel->stationName) }}
                              </td>
                              <td style="vertical-align: middle;">{{ $item->zipcode }}</td>

                              <td style="vertical-align: middle;">{{ $item->city }}</td>
                              <td style="vertical-align: middle;">{{ $item->state }}</td>

                              <td style="vertical-align: middle;">
                                  <a href="{{ route('admin.zipcode.local.channel.status', $item->id) }}"
                                      data-url="{{ route('admin.zipcode.local.channel.status', $item->id) }}"
                                      title="Change channel status" class="btn-user-status">
                                      @if ($item->status == 1)
                                          <span class="custom-badge badge-active">Active</span>
                                      @else
                                          <span class="custom-badge badge-inactive">Inactive</span>
                                      @endif
                                  </a>
                              </td>

                              <td style="vertical-align: middle;">{{ date('d/m/Y', strtotime($item->created_at)) }}
                              </td>


                              {{-- <td>
                                  <a class="btn btn-xs btn-danger fs-bold" id="btn-delete-zipcode"
                                      href="{{ route('admin.zipcode.local.channel.delete', $item->id) }}"
                                      data-url="{{ route('admin.zipcode.local.channel.delete', $item->id) }}">Delete</a>
                              </td> --}}


                          </tr>
                      @endforeach
                  </tbody>
              </table>

              <div class="d-flex justify-content-end mt-5 mr-1">
                  {{ $zipcodes->withQueryString()->links() }}
              </div>

          </div>
      @else
          <div class="table-responsive">
              <table class="table">
                  <thead>
                      <tr>

                          <th>#</th>
                          <th>Channel Id</th>
                          <th>Channel name</th>
                          <th>Zipcode</th>
                          <th>City</th>
                          <th>State</th>
                          <th>Status
                              <a href="{{ request()->get('page', 1) > 1
                                  ? request()->url() . '?status=' . (request()->get('status', '1') === '1' ? '0' : '1')
                                  : request()->fullUrlWithQuery([
                                      'status' => request()->get('status', '1') === '1' ? '0' : '1',
                                  ]) }}"
                                  class="text-primary ms-1">↑↓</a>

                          </th>

                          <th>Created At
                              <a href="{{ request()->get('page', 1) > 1
                                  ? request()->url() . '?created=' . (request()->get('sort_created', 'asc') === 'desc' ? 'asc' : 'desc')
                                  : request()->fullUrlWithQuery([
                                      'created' => request()->get('created', 'asc') === 'desc' ? 'asc' : 'desc',
                                  ]) }}"
                                  class="text-primary ms-1">↑↓</a>
                          </th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td colspan="9" class="text-center">
                              Data not found !
                          </td>
                      </tr>
                  </tbody>
              </table>



          </div>
      @endif
  </form>
