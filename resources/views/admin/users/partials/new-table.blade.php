 @if ($users->count() > 0)
     <div class="table-responsive">
         <table class="table table-striped projects">
             <thead>
                 <tr>
                     <th>
                         <input type="checkbox" id="selectAll">
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
                         User Type
                     </th>
                     <th>
                         Status
                     </th>
                     <th>
                         Active <br> Devices
                     </th>
                     <th>
                         Created At
                         <a href="{{ request()->get('page', 1) > 1
                             ? request()->url() . '?sort_date=' . (request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc')
                             : request()->fullUrlWithQuery([
                                 'sort_date' => request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc',
                             ]) }}"
                             class="text-primary ms-1">↑↓</a>
                     </th>
                     <th>Action</th>


                 </tr>
             </thead>
             <tbody>
                 @foreach ($users as $key => $user)
                     <tr>
                         <td>
                             <input type="checkbox" class="row-checkbox" name="user_ids[]" value="{{ $user->id }}">
                         </td>
                         <td>
                             <a href="{{ route('admin.users.view', $user->id) }}">
                                 <div class="mb-2"
                                     style="width:40px; height:40px;
                                            background:{{ $key % 2 == 0 ? '#3498db' : '#e67e22' }};
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                     {{ getAvatarName($user->name) }}
                                 </div>


                                 {{ ucwords(Str::limit($user->name, 10, '...')) }}
                             </a>
                         </td>
                         <td>

                             {{ limitEmailName($user->email, 10, '...') }}
                         </td>
                         <td>
                             {{ $user->roles->pluck('name')->join(', ') }}
                         </td>
                         <td>
                             @if ($user->mode == 'Live')
                                 Live
                             @else
                                 Testing
                             @endif

                         </td>
                         <td>
                             @if ($user->is_active)
                                 <label class="custom-switch">
                                     <input type="checkbox" checked>
                                     <span class="custom-slider"></span>
                                 </label>
                             @else
                                 <label class="custom-switch">
                                     <input type="checkbox">
                                     <span class="custom-slider"></span>
                                 </label>
                             @endif

                         </td>
                         <td>
                             <a href="{{ route('admin.users.connected.devices', $user->id) }}">
                                 @if ($user->rooms_count > 0)
                                     {{ $user->sessions_count ?? 0 }} / {{ $user->rooms_count ?? 0 }}
                                 @else
                                     {{ $user->sessions_count ?? 0 }} / 5
                                 @endif
                             </a>

                         </td>
                         <td>
                             {{ $user->created_at->format('d/m/Y') }}
                         </td>
                         <td>
                             <a class="btn btn-secondary btn-sm" title="Edit User"
                                 href="{{ route('admin.users.edit', $user->id) }}">
                                 <i class="fas fa-pencil-alt">
                                 </i>

                             </a>
                             <a href="{{ route('admin.users.delete', $user->id) }}" title="Delete User"
                                 data-url="{{ route('admin.users.delete', $user->id) }}"
                                 class="btn btn-danger btn-sm btn-user-delete">
                                 <i class="fas fa-trash">
                                 </i>

                             </a>
                         </td>
                     </tr>
                 @endforeach
         </table>
     </div>
 @else
     <div class="table-responsive">
         <table class="table table-striped projects">
             <thead>
                 <tr>
                     <th>
                         <input type="checkbox" id="selectAll">
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
                         User Type
                     </th>
                     <th>
                         Status
                     </th>
                     <th>
                         Active <br> Devices
                     </th>
                     <th>
                         Created At
                         <a href="{{ request()->get('page', 1) > 1
                             ? request()->url() . '?sort_date=' . (request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc')
                             : request()->fullUrlWithQuery([
                                 'sort_date' => request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc',
                             ]) }}"
                             class="text-primary ms-1">↑↓</a>
                     </th>
                     <th>Action</th>


                 </tr>
             </thead>
             <tbody>
                 <tr>
                     <td colspan="9" class="text-center">No records found</td>
                 </tr>
         </table>
     </div>
 @endif
