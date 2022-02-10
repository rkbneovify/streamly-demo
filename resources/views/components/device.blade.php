
	<div class="modal fade" id="removeDevice">
		<div class="modal-dialog">
		<div class="modal-content">
 
			<div class="modal-body text-center">
				<h4>Remove Device</h4>
				<p><span id="devicename"></span> will be removed. Would you like to continue?</p>
				<div>
					<form  method="POST" id="deleteFormDevice">
						<input type="hidden" name="_method" value="DELETE">
														<input type="hidden" name="_token" value="{{ csrf_token() }}">
														<input type="hidden" name="access_token" id="accesstoken">
					{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a> --}}
					<input style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" type="submit" class="btn btn-sm" value="Delete">
					<a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">No</a></form>
				</div>
			</div>

		</div>
		</div>
</div>
<div class="modal fade" id="changeDeviceName">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					@if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
						<form  id="DeviceNameUpdate" method="POST" >
            @csrf
            @method('PUT')

						<div class="form-group {{ $errors->has('devicename') ? ' has-error' : '' }}">
							<label for="devicename">Device Name</label>
							<input type="text" class="form-control" name="devicename" id="devicename" maxlength="20">
							<input type="hidden" class="form-control" name="device_type" id="device_type" value="">
						</div>


						<div class="text-center">
							<button type="submit" class="btn btn-sm" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">
								Update
							</button>
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						</div>
					</form>
				</div>

			</div>
	    </div>
	</div>
    @foreach($allSession as $key => $device)  
<div data-item-id="{{$key+1}}" id="{{$device->id}}" class="F4S0Db" role="listitem" style="z-index: 0;position: relative;">
                     <div class="device-box-card device-box-card-classb KC1dQ Usd1Ac AaN0Dd  jpoelc" jscontroller="zN4bWc" jsaction="JIbuQc:Hfqgkf;" data-device-id="A8b4a0279f3be6a6c" {!!$device->device['classes']??''!!}>
                        <div class="device-box-card-inside device-box-card-inside-classb" tabindex="0" jscontroller="tKHFxf" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;">
                           <div class="device-details">
                              <div class="device-logo device-logo-div" {!!$device->device['classes']??''!!}></div>
                           </div>
                           <div class="midline"></div>
                           <div class="device-detail-txt">
							<div class="device-name-div">
								<div role="heading" aria-level="3" class="device-name">{{ $device->device['platform']??$device->device['device']??''}} @isset($device->hotel) in
								<strong>{{$device->hotel->hotel_room}}</strong>
								@endisset</div>
								@if($device->device_type=='web')
									<div class="personal-device-name">{!!$device->device['personaldevicename']??''!!}</div>
								@else
									<div class="personal-device-name">{!!$device->pdname->personal_devicename??''!!}</div>
								@endif
                              </div>
                              <div class="device-location">						  
                                 <div>{{$device->user_ip}}</div>
                                 <div jsname="WQnlqf"> {{ \Carbon\Carbon::parse($device->created_on)->diffForHumans() }}</div>
                              
                                 
                                 @if(session()->get('jwttoken')==$device->access_token)
                                 <div class="K45rif">
                                    <svg class="kwfIKc mMF6qc" width="14" height="14" viewBox="0 0 24 24" xmlns="http://www.w4.org/2000/svg">
                                       <path d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                    </svg>
                                    This device
                                 </div>
                                 @endif
                                 @isset($device->device['browser'])
                                 <div class="eIXuRc EGriic">
                                    <div class="NQ9Zpd" role="heading" aria-level="4"><span>Browsers you’re signed in on</span></div>
                                    <div>
                                       <div class="YrUtyd" role="list">
                                          <div class="Qc8ulc" role="listitem">
                                             <span class="CCq0jd">
                                                <div class="aiicHc" {!!$device->device['browserclass']??''!!}></div>
                                             </span>
                                             <span title={{$device->device['browserversion']??''}}>{{$device->device['browser']}}</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 @endisset
                              </div>
                           </div>
                        </div>

						<div class="TDjQOc" data-device-id="Acb514901592fd168" data-source="3">
	<div role="button" class="dotmenu dotmenu1 dotmenu2 "  aria-label="More info" aria-disabled="false" tabindex="0" aria-haspopup="true" aria-expanded="false" data-menu-corner="top-end" data-anchor-corner="bottom-end">
		<div class="NWlf3e MbhUzd" style="top: 21px; left: 23.5625px; width: 48px; height: 48px;"></div> <span jsslot="" class="MhXXcc oJeWuf">
																<span class="Lw7GHd snByac">
																	 <svg width="24" height="24" viewBox="0 0 24 24" focusable="false" class="gnN5i NMm5M">
																			<path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
																	 </svg>
																</span> </span>
	</div>
	<div class="dotmenufull dotmenufull1 dotmenufull2 toggle-content" role="menu" tabindex="0" data-back-to-cancel="false" style="position: absolute; max-width: 256px; max-height: 136px;z-index:1;
	right: 1%; top: 100%;
	">
		<div class="dotmenubody" style="max-width: 256px; max-height: 136px;">
			<div class="dotinnermenubody dotinnermenubody1" style="width: 256px; height: auto; min-width: 256px;"> <span class="dotinnermenuitem" aria-label="Sign out this device" role="menuitem" tabindex="-1">
							<div class="aBBjbd MbhUzd" jsname="ksKsZd"></div>
							<div class="uyYuVb oJeWuf" jsname="cEoysc">
								 <div class="dotinnermenuitemname remove-device" data-id="{{ $device->id }}" data-devicename="{{$device->id}}.{{ $device->personal_devicename }}" data-accesstoken="{{ $device->access_token }}" >Sign out</div>
							</div>
					 </span>
					 <span jsslot="" class="dotinnermenuitem" aria-label="Find phone" role="menuitem" tabindex="-1">
							<div class="aBBjbd MbhUzd" jsname="ksKsZd"></div>
							<div class="uyYuVb oJeWuf" jsname="CUoVIc">
								 <div class="dotinnermenuitemname add-device-name" data-id="{{ $device->pdname?HashUserID($device->pdname->id):HashUserID($device->id) }}" data-type ="{{$device->device_type}}" data-accesstoken="{{ $device->access_token }}">Add a name</div>
							</div>
					 </span>
					 <span style="display: none;" jsslot="" class="dotinnermenuitem" aria-label="I do not recognize this device" role="menuitem" tabindex="-1">
							<div class="aBBjbd MbhUzd" jsname="ksKsZd"></div>
							<div class="uyYuVb oJeWuf" jsname="nJTW3e">
								 <div class="dotinnermenuitemname">Don’t recognize this device?</div>
							</div>
					 </span>
					</div>
		</div>
	</div>
</div>
						<div class="TDjQOc2" data-device-id="Acb514901592fd168" data-source="3">
	<div role="button" class="dotmenu dotmenu1 dotmenu2 "  aria-label="More info" aria-disabled="false" tabindex="0" aria-haspopup="true" aria-expanded="false" data-menu-corner="top-end" data-anchor-corner="bottom-end">
		<div class="NWlf3e MbhUzd" style="top: 21px; left: 23.5625px; width: 48px; height: 48px;"></div> <span jsslot="" class="MhXXcc oJeWuf">
																<span class="Lw7GHd snByac">
																<input type='checkbox' class='update_check' id='delcheck_{{$device->id}}'  value='{{$device->id}}'>
																</span> </span>
	</div>
</div>
                        <span class="VfPpkd-BFbNVe-bF1uUb NZp2ef" aria-hidden="true"></span>
                     </div>
                  </div>
                  @endforeach