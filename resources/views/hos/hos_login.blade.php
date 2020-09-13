<!DOCTYPE html>
<html lang="en">

  <title>HOS</title>
  <meta name="theme-color" content="#557eb0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0 shrink-to-fit=no, user-scalable=0">
  <link rel="icon" type="image/png" href="{{ asset('hos/img/HOS-logo.png') }}" sizes="194x194">
  <link rel="stylesheet" href="{{ asset('hos/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Titillium+Web|Changa|Montserrat|Ubuntu&display=swap" rel="stylesheet">
  <link rel="manifest" href="assets/lib/manifest.json">
  <link rel="stylesheet" type="text/css" href="{{ asset('hos/css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('hos/css/style2.css') }}">
  <!--------table style lib------------------------>
  <link rel="stylesheet" type="text/css" href="{{ asset('hos/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('hos/css/dataTables.bootstrap4.min.css') }}">
  <style type="text/css">
.bg-gradient-primary {
  background-image: linear-gradient(90deg,#BAD0D9 10%,#92a5ac 100%);
  background-size: cover;
} 
.form-control {
  display: block;
  width: 100%;
  height: calc(1.5em + .75rem + 2px);
  padding: .375rem .75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #6e707e;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #d1d3e2;
  border-radius: .35rem;
  transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
form.user .form-control-user {
  font-size: .8rem;
  border-radius: 10rem;
  padding: 1.5rem 1rem;
}
form.user .btn-user {
  font-size: .8rem;
  border-radius: 10rem;
  padding: .75rem 1rem;
}
.sd_none{
  box-shadow: none;
}
</style>

</head>

<body class="bg-gradient-primary">

  <div class="container sd_none">
    <div class="row justify-content-center my-5">
      <div class="col-xl-5 col-lg-5 col-md-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12 ff_mon">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <form class="user" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                    @csrf
                    <div class="form-group">
                      <!-- <input type="" class="form-control form-control-user" id="username" aria-describedby="usernameHelp" placeholder="Enter username..." required/> -->
                      <input id="email" type="text" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" aria-describedby="usernameHelp" placeholder="Email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="form-group">
                      <!-- <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" required/> -->
                      <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="Password"  required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <!-- <input type="checkbox" class="custom-control-input" id="customCheck"/> -->
                        <input class="custom-control-input" type="checkbox" name="remember" id="customCheck" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    <button class="btn btn-primary btn-user btn-block" type="submit">
                      Login
                    </button>
                    <hr>
                  </form>
                  <div class="text-center">
                    <img class="" src="{{ asset('hos/img/HOS-logo.png') }}" style="width:auto;height: 30px">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- script files -->
  <script src="{{ asset('hos/js/jquery.min.js') }}"></script>
  <script src="{{ asset('hos/js/popper.min.js') }}"></script>
  <script src="{{ asset('hos/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('hos/js/crs.min.js') }}"></script>
  <script src="{{ asset('hos/js/script.js') }}"></script>

</body>

</html>
