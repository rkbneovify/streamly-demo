
    <style>
    @media only screen and (max-width: 450px){
    .g-recaptcha-bubble-arrow + div
    {
    position: fixed !important;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);
    }
    }
    </style>
    <div id="mySidenav" class="sidenav">

      <div style="width: 100%;height: 5%;">
          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      </div>

      <div class="container" style="padding: 0px 50px;">
          <div class="row mt-4">
              @if($errors->has('email') && !$errors->has('passcode'))
      <div class="alert alert-danger alert-dismissible ">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Error!</strong> {{$errors->first()}}
      </div>
  @endif
              <h4 class="login_txt">Log In</h4>    
              <form action="login" method="post" class="login-form mt-3">
                  <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                      <label for="email" class="control-label">Email</label>
                      <input type="email" id="email" name="email" class="form-control" required>
                  </div>
                  <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                      <label for="password" class="control-label">Password</label>
                      <input type="password" id="password" name="password" class="form-control" required>
                      <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password2"></span>
                  </div>

                  <div class="form-group">
                      <!-- <input type="checkbox" class="form-check-input" id="remember-me">
                      <label class="no-font-s form-check-label" for="remember-me">Remember me</label> -->
                        <label class="no-font-s float-right"><a href="{{ url('/password/reset') }}" class="forgot-password hoverSidenav">Forgot password?</a></label>
                    </div>
                    <div>&nbsp;</div>
                    @if(env('GOOGLE_RECAPTCHA_KEY'))
                    <div class="form-group">
                    <div class="col-md-12 text-right">
                    <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                    </div>
            </div>
            @endif
                <div>&nbsp;</div>
                  <div class="form-group mt-3">
                      <button type="submit" class="btn btn-lg login-btn">LOG IN</button>
                  </div>

                  <span>Don't have an account?</span> <a href="javascript:void(0)" class="hoverSidenav" onclick="openNavReg();"style="padding: 0;">Start Your Trial Now!</a>

                  <div class="mt-4">
                      <div style="border: 0.5px solid #C4C4C4;display: inline-block;width: 40%;"></div>
                      <span style="padding: 0 4%;">or</span>
                      <div style="border: 0.5px solid #C4C4C4;display: inline-block;width: 40%;"></div>
                  </div>

                  <div class="form-group mt-4 text-center">
                      <a class="btn btn-sm google-btn" href="javascript:void(0)" onclick="openNavPasscode(event)">  <span class="hoverSidenav">LOG IN WITH PASSCODE<span></a>
                      <a class="btn btn-sm google-btn" href="{{ route('google.login') }}"> <img class="lazy" src="../uploads/channellogo/notfound.png" data-src="{{ asset('frontend/img/google-login.svg') }}"> <span class="hoverSidenav">LOG IN WITH GOOGLE<span></a>
                      <br>
                      <a class="btn btn-sm google-btn" href="{{ route('qrtesting2') }}">  <span class="hoverSidenav">LOG IN WITH QR<span></a>
                       {{-- <button type="submit" class="btn btn-lg login-btn">LOG IN</button> --}}
                  </div>
                  {!! csrf_field() !!}
              </form>
          </div>
          
      </div>

  </div>
    <div id="mySidenavRegister" class="sidenav">

      <div style="width: 100%;height: 5%;">
          <a href="javascript:void(0)" class="closebtn" onclick="closeNavReg()">&times;</a>
      </div>

      <div class="container" style="padding: 0px 50px; padding-bottom: 25px;">
          <div class="row mt-4">
              @if($errors->any()&& !$errors->has('passcode') && !$errors->has('email'))
       <div class="alert alert-danger alert-dismissible ">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Error!</strong> @foreach ($errors->all() as $message) 
              {{$message}}
          
          @endforeach
      </div>
  @endif
  @if(session()->has('message'))
       <div class="alert alert-success alert-dismissible ">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          {{ session()->get('message') }}
      </div>
  @endif 

  @if(session()->has('reseterror'))
       <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          {{ session()->get('reseterror') }}
      </div>
  @endif 

              <h4 class="login_txt">Create Your Account</h4>    
              <form action="user/register" method="post" class="login-form mt-3">
                  <div class="form-group">
                      <label for="name" class="control-label">Name</label>
                      <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" required>
                  </div>

                  <div class="form-group">
                      <label for="user-email" class="control-label">Email</label>
                      <input type="email" id="user-email" name="user-email" class="form-control" value="{{old('user-email')}}" required>
                  </div>

                  <div class="form-group">
                      <label for="user-password" class="control-label">Password</label>
                      <input type="password" id="user-password" name="user-password" class="form-control" minlength="6" value="{{old('user-password')}}" required>
                      <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password1"></span>
                    </div>


                  <div class="form-group">
                      <label for="zip-code" class="control-label">Home zip code</label>
                      <input type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" id="zip-code" name="zip_code" class="form-control" value="{{old('zip_code')}}" required>
                  </div>

                  <span class="remove-p-a-r">By clicking “REGISTER” you agree to the Streamly <br />
                      <a href="{{ url('termsofuse') }}">Terms of Use</a>
                      <span>and</span>
                      <a href="{{ url('privacy') }}">Privacy Policy</a>
                      <span>.</span>
                  </span>

                  @if(env('GOOGLE_RECAPTCHA_KEY'))
                  <div class="form-group">
                  <div class="col-md-12 text-right">
                  <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                  </div>
          </div>
          @endif
         
                  <div class="form-group mt-3">
                      <button type="submit" class="btn btn-lg login-btn">REGISTER</button>
                  </div>

                  <span>Already have an account?</span> <a href="javascript:void(0)" onclick="openNav(event);"style="padding: 0;">Log in</a>

                  {!! csrf_field() !!}
              </form>
          </div>
          
      </div>

  </div>
  <div id="mySidenavPasscode" class="sidenav">

    <div style="width: 100%;height: 5%;">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNavPasscode()">&times;</a>
    </div>

    <div class="container" style="padding: 0px 50px; padding-bottom: 25px;">
        <div class="row mt-4">
              @if($errors->has('passcode') && !$errors->has('email'))
       <div class="alert alert-danger alert-dismissible ">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Error!</strong> @foreach ($errors->all() as $message) 
              {{$message}}
          
          @endforeach
      </div>
  @endif
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session()->get('message') }}
    </div>
    @endif 
        <h4 class="login_txt">Log In</h4>    
        <form action="user/login/passcode" method="post" class="login-form mt-3">
            <div class="form-group">
                <label for="user-password" class="control-label">Passcode</label>
                <input type="password" id="passcode" name="passcode" class="form-control" value="{{old('user-password')}}" required>
                <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-passcode"></span>
              </div>

            @if(env('GOOGLE_RECAPTCHA_KEY'))
            <div class="form-group">
            <div class="col-md-12 text-right">
            <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
            </div>
            </div>
            @endif
   
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-lg login-btn">LOG IN</button>
            </div>

            <span>Don't have an account?</span> <a href="javascript:void(0)" class="hoverSidenav" onclick="openNavReg();"style="padding: 0;">Start Your Trial Now!</a>

            <div class="mt-4">
                <div style="border: 0.5px solid #C4C4C4;display: inline-block;width: 40%;"></div>
                <span style="padding: 0 4%;">or</span>
                <div style="border: 0.5px solid #C4C4C4;display: inline-block;width: 40%;"></div>
            </div>

            <div class="form-group mt-4 text-center">
                <a class="btn btn-sm google-btn" href="javascript:void(0)" onclick="openNav(event)">  <span class="hoverSidenav">LOG IN WITH PASSWORD<span></a>
                <a class="btn btn-sm google-btn" href="{{ route('google.login') }}"> <img class="lazy" src="../uploads/channellogo/notfound.png" data-src="{{ asset('frontend/img/google-login.svg') }}"> <span class="hoverSidenav">LOG IN WITH GOOGLE<span></a>
                <br>
                <a class="btn btn-sm google-btn" href="{{ route('qrtesting2') }}">  <span class="hoverSidenav">LOG IN WITH QR<span></a>
                {{-- <button type="submit" class="btn btn-lg login-btn">LOG IN</button> --}}
            </div>
            {!! csrf_field() !!}

        </form>
    </div>
    
</div>

</div>