<!DOCTYPE html>
<html lang="en">
<head>
  <title>HOS</title>
  <meta name="theme-color" content="#557eb0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0 shrink-to-fit=no, user-scalable=0">
  <link rel="icon" type="image/png" href="../assets/images/favicon.png" sizes="194x194">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Titillium+Web|Changa|Montserrat|Ubuntu&display=swap" rel="stylesheet">
  <link rel="manifest" href="../assets/lib/manifest.json">
  <link rel="stylesheet" type="text/css" href="../assets/lib/style.css">
  <link rel="stylesheet" type="text/css" href="../assets/lib/style2.css">
  <link rel="stylesheet" type="text/css" href="../assets/lib/all.min.css">
  <!--------table style lib------------------------>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
  <style type="text/css">
    #example .bg_color{
        background-color: steelblue!important;
        color: #fff!important;
    }
    #example_wrapper .table .bg_color th {
        background-color: steelblue!important;
        color: #fff!important;
    }
    #example_wrapper .table tr:nth-child(1) th {
        background-color: white!important;
    }
    #example_wrapper .table{
       font-size: 11px;
    }
    table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before { 
        right: 1px;
    }
    .bg-blue{
      background: #3276b1!important;
    }
    #example_wrapper .table tr:nth-child(1) th {
    background-color: #3276b1!important;
}
  </style>
</head>
<body class="bg-light">
  <nav class="navbar fixed-top navbar-light bg-white topnav px-2 py-0" style="height: 108px;">
   <div class="col-3">
       <a class="navbar-brand py-3" href="store_order.html">
         <img src="../assets/images/king.jpg" height="70px" width="auto">
       </a>
   </div>
    <div class="col-6 text-center px-0">
      <h4 class="text-danger ff_mon">
        <img class="" src="../assets/images/HOS-logo.png" style="width:auto;height: 30px"><br>
        <b class="text_shadow">NUPCO HOSPITAL ORDERING PORTAL</b></h4>
    </div>
    <div class="col-3 text-right px-0" style="font-size: 11px;font-weight: 600;"> 
      <div class="row">
         <div class="col-12 text-center">
           <img class="" src="../assets/images/logo2.png" height="50px" width="auto" style="margin:16px 10px">
         </div>
      </div>
        <!-- <span class="dropdown">
          <span class="dropdown-toggle" data-toggle="dropdown" style="color:#197c89">White Pharmacy</span> |
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Logout</a>
          </div>
        </span> -->
        <span style="color:#197c89;">09/11/2020</span> |
        <span style="color:#8a8c8d;">08:09 PM</span> |
        <span style="color:#8a8c8d;">Help</span>
    </div>
  </nav><br><br><br><br>
<div class="container-fluid px-0">
  <div class="row mx-0">
    <div class="sidenav px-0 mt-2 bg-grey">
      <ul class="list-group">
        <li  class="nav-item bg-white" style="padding: 18px 20px;font-size: 16px">
          <i class="fas fa-minus-square"></i></li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item my-1 active">
          <a class="nav-link" href="home.html">
            <i class="fas fa-tachometer-alt fs_18"></i>&ensp;  Dashboard</a>
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
            <a href="index.html"><span class="badge badge-dark p-2" title="logout"><i class="fas fa-power-off"></i></span>
            </a>
          </li>
          <li class="nav-item px-2 py-1">
            <a>
            </a>
          </li>
        </ul>
        </div>
      </nav>
      <div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Requested Order list</b> </h5>
          </div>
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Store Order #</th>
                      <th class="text-nowrap px-3">Supplying Plant</th>
                      <th class="text-nowrap px-3">Status</th>
                      <th class="text-nowrap px-3">Delivery date</th>
                      <th class="text-nowrap px-3">Item Count</th>
                      <th class="text-nowrap px-3">Qty Ordered</th>
                      <th class="text-nowrap px-3">Report</th>
                      <th class="text-nowrap px-3">Status</th>
                      <th class="text-nowrap px-3">Action</th>
                  </tr>
              </thead>
              <tbody>
                  <tr onclick="window.location.href='requested_order_details.html'">
                      <td>1</td>
                      <td>material</td>
                      <td>1234567</td>
                      <td>nupco</td>
                      <td>uom</td>
                      <td>27</td>
                      <td>
                        <a target="_blank" href="" class="btn btn-primary btn-sm fs_10">
                          <i class="fas fa-download" aria-hidden="true"></i> / 
                          <i class="fas fa-print" aria-hidden="true"></i></a>
                      </td>
                      <td class=""><span class="text-warning"><b>NEW</b></span></td>
                      <td><button class="btn btn-primary btn-sm fs_10">Delivery Date</button></td>
                  </tr>
                  <tr onclick="window.location.href='requested_order_details.html'">
                      <td>1</td>
                      <td>material</td>
                      <td>1234567</td>
                      <td>nupco</td>
                      <td>uom</td>
                      <td>27</td>
                      <td>
                        <a target="_blank" href="" class="btn btn-primary btn-sm fs_10">
                          <i class="fas fa-download" aria-hidden="true"></i> / 
                          <i class="fas fa-print" aria-hidden="true"></i></a>
                      </td>
                      <td class=""><span class="text-success"><b>Accepted</b></span></td>
                      <td><button class="btn btn-success btn-sm fs_10">N/A</button></td>
                  </tr>
                  <tr onclick="window.location.href='requested_order_details.html'">
                      <td>1</td>
                      <td>material</td>
                      <td>1234567</td>
                      <td>nupco</td>
                      <td>uom</td>
                      <td>27</td>
                      <td>
                        <a target="_blank" href="" class="btn btn-primary btn-sm fs_10">
                          <i class="fas fa-download" aria-hidden="true"></i> / 
                          <i class="fas fa-print" aria-hidden="true"></i></a>
                      </td>
                      <td class=""><span class="text-danger"><b>Rejected</b></span></td>
                      <td><button class="btn btn-success btn-sm fs_10">N/A</button></td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><br>
    </div>
  </div>
  
  </div>
</div>


<!-- script files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="../assets/lib/crs.min.js"></script>
  <script src="../assets/lib/script.js"></script>
  <!--------table style lib------------------------>
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable( {
            "scrollY":        "55vh",
            "scrollCollapse": true,
            "paging":         false,
            "searching": false,
            "lengthMenu": [ [15, 30, 50, 100, 250, 500, 1000, 1500], [15, 20, 50, 100, 250, 500, 1000, 1500] ],
            "iDisplayLength": 1000,
        } );
    } );
  </script>
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
</body>
</html>
