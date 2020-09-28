<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ config('app.name', 'HOS') }}</title>
  <meta name="theme-color" content="#557eb0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0 shrink-to-fit=no, user-scalable=0">
  <meta http-equiv='cache-control' content='no-cache'> 
  <meta http-equiv='expires' content='0'> 
  <meta http-equiv='pragma' content='no-cache'>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ asset('public/hos/img/favicon.png') }}" sizes="194x194">
  <link rel="stylesheet" href="{{ asset('public/hos/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Titillium+Web|Changa|Montserrat|Ubuntu&display=swap" rel="stylesheet">
  <link rel="manifest" href="{{ asset('public/hos/manifest.json') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/style2.css') }}">
  <!--------table style lib------------------------>
  <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/hos/css/dataTables.bootstrap4.min.css') }}">
  
</head>
<body class="bg-light">
  <nav class="navbar fixed-top navbar-light bg-white topnav px-2 py-0" style="height: 108px;">
   <div class="col-3">
      
   </div>
    <div class="col-6 text-center px-0">
      <h4 class="text-danger ff_mon">
      <img class="" src="{{ asset('public/hos/img/HOS-logo.png') }}" style="width:auto;height: 60px"><br>
      <b class="text_shadow">{{auth()->guard('inventory')->user()->name}}</b></h4>
    </div>
    <div class="col-3 text-right px-0" style="font-size: 11px;font-weight: 600;"> 
      <div class="row">
      <div class="col-5 text-center"></div>
         <div class="col-7 text-center">
           <img class="" src="{{ asset('public/hos/img/logo2.png') }}" height="50px" width="auto" style="margin:16px 10px">
         </div>
         
      </div>
        <!-- <span class="dropdown">
          <span class="dropdown-toggle" data-toggle="dropdown" style="color:#197c89">White Pharmacy</span> |
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Logout</a>
          </div>
        </span> -->
        <span style="color:#197c89;"><?php echo date("Y/m/d"); ?></span> |
        <span style="color:#8a8c8d;"><?php date_default_timezone_set('Asia/Kolkata');
$currentTime = date( 'h:i:s A', time () );
echo $currentTime; ?></span> |
        <span style="color:#8a8c8d;">Help</span>
    </div>
  </nav><br><br><br><br>
<div class="container-fluid px-0">
  <div class="row mx-0">
    <div class="sidenav px-0 mt-2 bg-grey">
      <ul class="list-group">
        <li  class="nav-item bg-white" style="padding: 18px 20px;font-size: 16px">
        &ensp;</li>
      </ul>
      <ul class="navbar-nav">
        @if(Request::path() == 'inventory/home' || Request::is('inventory/order_detail/*'))
        <li class="nav-item my-1 active">
        @else
        <li class="nav-item my-1 ">
        @endif
          <a class="nav-link" href="{{ route('inventory.home') }}">
          <i class="fas fa-tachometer-alt fs_18"></i>&ensp; Dashboard</a>
        </li>
      
      </ul>
    </div>
    <div class="main px-3 py-5">
      <nav class="navbar navbar-expand navbar-light bg-white shadow topsecnav py-0 px-0">
        <a class="navbar-brand mr-0" href="#" style=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="height: 57px">
        <ul class="navbar-nav mr-auto">
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item px-2">
            <a>
              <form name="lang_switch" id="lang_switch">
              <select name="lang" id="lang" class="form-control" style="box-shadow: none!important; border: 2px solid #45CBD3;">
                <option value="english">English</option>
                <option value="arabic">Arabic</option>
              </select>
            </form>
            </a>
          </li>
          <li class="nav-item px-2 py-1">
            <a href="{{ route('inventory.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="badge badge-dark p-2" title="logout"><i class="fas fa-power-off"></i></span>
            </a>
            <form id="logout-form" action="{{ route('inventory.logout') }}" method="POST" style="display: none;">@csrf</form>
          </li>
          <li class="nav-item px-2 py-1">
            <a>
            </a>
          </li>
        </ul>
        </div>
      </nav>
      @yield('content')
      <br>
    </div>
  </div>
  
  </div>
</div>


<!-- script files -->
  <script src="{{ asset('public/hos/js/jquery.min.js') }}"></script>
  <script src="{{ asset('public/hos/js/jquery-3.3.1.js') }}"></script>
  <script src="{{ asset('public/hos/js/popper.min.js') }}"></script>
  <script src="{{ asset('public/hos/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('public/hos/js/crs.min.js') }}"></script>
  <script src="{{ asset('public/hos/js/script.js') }}"></script>
  <!--------table style lib------------------------>
  
  <script src="{{ asset('public/hos/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/hos/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  
  @stack('scripts')
  <script>
    $( '.topsecnav .navbar-nav .nav-link' ).on( 'click', function () {
      $( '.topsecnav .navbar-nav' ).find( '.nav-item.active' ).removeClass( 'active' );
      $( this ).parent( '.nav-item' ).addClass( 'active' );
    });
  </script>
  <script type="text/javascript">
    $( '.sidenav .navbar-nav .nav-link' ).on( 'click', function () {
      $( '.sidenav .navbar-nav' ).find( '.nav-item.active' ).removeClass( 'active' );
      $( this ).parent( '.nav-item' ).addClass( 'active' );
    });
  </script>
  <script type="text/javascript">
    $(".selectAll").click(function(){
        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));

   });
  </script>
  <script>
        $('.datepicker').datepicker({
            uiLibrary: 'bootstrap4'
        });
    </script>
</body>
</html>
