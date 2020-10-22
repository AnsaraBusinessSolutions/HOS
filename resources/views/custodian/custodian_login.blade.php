<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="en-us">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0 shrink-to-fit=no, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HOS</title>
    <meta content="" name="descriptison">
    <meta content="" name="keywords">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="theme-color" content="#1C2346">
    <link rel="icon" type="image/png" href="{{ asset('public/hos/img/HOS-logo.png') }}" sizes="194x194">
    <link rel="stylesheet" href="{{ asset('public/hos/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Titillium+Web|Changa|Montserrat|Ubuntu&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('public/hos/manifest.json') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/style2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/login_style.css') }}">
</head>

<body class="">
    <header>
        <nav class="navbar navbar-expand fixed-top">
            <a href="{{route('main_page')}}" class="navbar-brand"><img src="{{ asset('public/hos/img/logo_trans.png') }}" height="60px"></a>
            <ul class="navbar-nav ml-auto group_main">
                <li class="nav-item mr-3">
                    <a class="nav-link btn btn-default btn-sm text-white bubbly-button btn_logins" href="{{ route('admin.login') }}">Admin</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link btn btn-default btn-sm text-white bubbly-button btn_logins" href="{{ route('hos.login') }}">HOS</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link btn btn-default btn-sm text-white bubbly-button btn_logins active" href="{{ route('custodian.login') }}">Custodian</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link btn btn-default btn-sm text-white bubbly-button btn_logins" href="{{ route('hos3pl.login') }}">3PL</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link btn btn-default btn-sm text-white bubbly-button btn_logins" href="{{ route('inventory.login') }}">Inventory</a>
                </li>
            </ul>
        </nav>
    </header>
    <video src="{{ asset('public/hos/img/videoplayback.mp4') }}" autoplay loop playsinline muted></video>
    <div class="overlay">
    </div>
    <div class="container sd_none">
        <!-- Outer Row -->
        <div class="row justify-content-center my-5">
            <div class="col-xl-4 col-lg-4 col-md-5 pl-md-0 px-sm-2">

                <div class="card o-hidden border-0 my-5">
                    <div class="tab-content bg_line position-relative overflow-hidden">
                        <div class="blog-card__square"></div>
                        <div class="blog-card__circle"></div>
                        <div class="container"><br>
                            <div class="card-body p-0">
                                <!-- Nested Row within Card Body -->
                                <div class="row">
                                    <div class="col-lg-12 ff_mon">
                                        <div class="p-5">
                                            <div class="text-center">
                                                <h1 class="h4 text-danger mb-4 text_shadow"><b>HOS CUSTODIAN LOGIN</b></h1>
                                            </div>
                                            <form class="user" method="POST" action="{{ route('custodian.login') }}"
                                                aria-label="{{ __('Login') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <!-- <input type="text" class="form-control form-control-user" id="username" aria-describedby="usernameHelp" placeholder="Username / Email" required/> -->
                                                    <input id="email" type="text" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" aria-describedby="usernameHelp" placeholder="Email" autofocus>
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <!-- <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" required/> -->
                                                    <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
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
                                                <button class="btn btn-primary btn-user btn-block" type="submit"> Login </button>
                                            </form>
                                        </div>
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
    <script src="{{ asset('public/hos/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/hos/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/hos/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/hos/js/crs.min.js') }}"></script>
    <script src="{{ asset('public/hos/js/script.js') }}"></script>

     <!-- Button animation -->
    <script type="text/javascript">
    var animateButton = function(e) {
        e.preventDefault;
        //reset animation
        e.target.classList.remove('animate');
        e.target.classList.add('animate');
        setTimeout(function() {
            e.target.classList.remove('animate');
        }, 700);
    };

    var bubblyButtons = document.getElementsByClassName("bubbly-button");
    for (var i = 0; i < bubblyButtons.length; i++) {
        bubblyButtons[i].addEventListener('click', animateButton, false);
    }
    </script>

</body>
</html>