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

          <h3 class="card-title mt-2">Total Channels : {{ $record->total() }}</h3>

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

      @if ($record->count() > 0)
          <div class="table-responsive">
              <table class="table">
                  <thead>
                      <tr>
                          <th>
                              <input type="checkbox" id="selectAll">
                          </th>
                          <th>#</th>
                          <th>Name</th>
                          <th>Genres</th>
                          <th>Availability

                              <a href="{{ request()->get('page', 1) > 1
                                  ? request()->url() . '?availability=' . (request()->get('availability', 'all') === 'all' ? 'local' : 'all')
                                  : request()->fullUrlWithQuery([
                                      'availability' => request()->get('availability', 'all') === 'all' ? 'local' : 'all',
                                  ]) }}"
                                  class="text-primary ms-1">↑↓</a>
                          </th>
                          <th>Status
                              <a href="{{ request()->get('page', 1) > 1
                                  ? request()->url() . '?status=' . (request()->get('status', '1') === '1' ? '2' : '1')
                                  : request()->fullUrlWithQuery([
                                      'status' => request()->get('status', '1') === '1' ? '2' : '1',
                                  ]) }}"
                                  class="text-primary ms-1">↑↓</a>

                          </th>
                          <th>Callsign</th>
                          <th>Created At
                              <a href="{{ request()->get('page', 1) > 1
                                  ? request()->url() . '?sort_created=' . (request()->get('sort_created', 'asc') === 'desc' ? 'asc' : 'desc')
                                  : request()->fullUrlWithQuery([
                                      'sort_created' => request()->get('sort_created', 'asc') === 'desc' ? 'asc' : 'desc',
                                  ]) }}"
                                  class="text-primary ms-1">↑↓</a>
                          </th>
                          <th>Url</th>
                          <th>Dash Url</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($record as $item)
                          <tr>
                              <td style="vertical-align: middle;">
                                  <input type="checkbox" class="row-checkbox" value="{{ $item->id }}">
                              </td>
                              <td style="vertical-align: middle;">{{ $item->id }}</td>
                              <td>
                                  <img src="{{ url('/uploads/channellogo') . '/' . $item->image }}" alt="channel logo"
                                      style="background:#f0f0f0; padding:10px; border-radius:8px; max-width:100px;">

                                  <br class="mb-2">
                                  <b>{{ $item->stationName ?? ($item->name ?? $item->callSign) }}</b>
                              </td>
                              <td style="vertical-align: middle;">{!! nl2br($item->genre_list) !!}</td>
                              <td style="vertical-align: middle;">
                                  @if (count($item->zip_code) == 1)
                                      All
                                  @else
                                      <div>
                                          <a href="javascript:void(0)" class="toggle-zipcodes"
                                              data-id="{{ $item->id }}">
                                              {{ count($item->zip_code) }} Zipcodes
                                          </a>
                                          <div id="zipcodes-{{ $item->id }}" class="zipcodes-list d-none mt-2">
                                              <ul class="mb-0">
                                                  @foreach ($item->zip_code as $zip)
                                                      <li>{{ $zip }}</li>
                                                  @endforeach
                                              </ul>
                                          </div>
                                      </div>
                                  @endif
                              </td>

                              <td style="vertical-align: middle;">
                                  <a href="{{ route('admin.channel.status', $item->id) }}"
                                      data-url="{{ route('admin.channel.status', $item->id) }}"
                                      title="Change channel status" class="btn-user-status">
                                      @if ($item->status == 1)
                                          <span class="custom-badge badge-active">Active</span>
                                      @else
                                          <span class="custom-badge badge-inactive">Inactive</span>
                                      @endif
                                  </a>
                              </td>
                              <td style="vertical-align: middle;">{{ $item->callSign }}</td>
                              <td style="vertical-align: middle;">{{ date('d/m/Y', strtotime($item->created_at)) }}
                              </td>

                              {{-- Url with toggle --}}
                              <td style="vertical-align: middle;">
                                  <a href="javascript:void(0)" class="toggle-url">
                                      <span class="short-url">{{ Str::limit($item->url, 10) }}</span>
                                      <span class="full-url d-none">{{ $item->url }}</span>
                                  </a>
                              </td>

                              {{-- Dash Url with toggle --}}
                              <td style="vertical-align: middle;">
                                  <a href="javascript:void(0)" class="toggle-url">
                                      <span class="short-url">{{ Str::limit($item->dash_url, 10) }}</span>
                                      <span class="full-url d-none">{{ $item->dash_url }}</span>
                                  </a>
                              </td>

                              <td style="vertical-align: middle;">
                                  <a class="btn btn-xs btn-outline-primary w-100"
                                      href="{{ url('/admin/channelstatus/edit') . '/' . $item->id }}">Edit</a>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>

              <div class="d-flex justify-content-end mt-5 mr-1">
                  {{ $record->withQueryString()->links() }}
              </div>

          </div>
      @else
          <div class="table-responsive">
              <table class="table">
                  <thead>
                      <tr>
                          <th>
                              <input type="checkbox" id="selectAll">
                          </th>
                          <th>#</th>
                          <th>Name</th>
                          <th>Genres</th>
                          <th>Availability</th>
                          <th>Status</th>
                          <th>Callsign</th>
                          <th>Created At</th>
                          <th>Url</th>
                          <th>Dash Url</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td colspan="11" class="text-center">
                              Data not found !
                          </td>
                      </tr>
                  </tbody>
              </table>



          </div>
      @endif
  </form>
