@extends('hos.layouts.app')
@section('content')
<style type="text/css">
    .example .bg_color{
        background-color: steelblue!important;
        color: #fff!important;
    }
    .example_wrapper .table .bg_color th {
        background-color: steelblue!important;
        color: #fff!important;
    }
    .example_wrapper .table tr:nth-child(1) th {
        background-color: white!important;
    }
    .example_wrapper .table{
       font-size: 13px;
    }
    table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before { 
        right: 1px;
    }
    .bg-blue{
      background: #3276b1!important;
    }
    .example_wrapper .table tr:nth-child(1) th {
    background-color: #3276b1!important;
}
  </style>
   <div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          <div class="col-10 text-center">
            <h5 style="color: steelblue"></h5>
          </div>
          <div class="col-2 py-1 text-center">
           
          </div>
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Store Order #</th>
                      <th class="text-nowrap px-3">Supplying Plant</th>
                      <th class="text-nowrap px-3">Delivery date</th>
                      <th class="text-nowrap px-3">Item Count</th>
                      <th class="text-nowrap px-3">Qty Ordered</th>
                      <th class="text-nowrap px-3">Status</th>
                      <th class="text-nowrap px-3">Report</th>
                  </tr>
              </thead>
              <tbody>
              @foreach($all_order as $key=>$val)
                  <tr onclick="window.location.href='{{url('store/order_detail/'.$val->order_code)}}'">
                      <td>{{$val->order_code}}</td>
                      <td>{{$val->wh_name}}</td>
                      <td>{{$val->delivery_date}}</td>
                      <td>{{$val->buom}}</td>
                      <td>{{$val->qty}}</td>
                      <td>
                        @if($val->status == 0)
                           New
                        @elseif($val->status == 1)
                           Approved
                        @else
                            Rejected
                        @endif
                      </td>
                      <td>available</td>
                  </tr>
               @endforeach   
                 
              </tbody>
            </table>
          </div>
        </div>
      </div><br>
    </div>
  </div>
  @stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.example').DataTable( {
            "scrollY":        "55vh",
            "scrollCollapse": true,
            "paging":         false,
            "searching": false,
            "lengthMenu": [ [15, 30, 50, 100, 250, 500, 1000, 1500], [15, 20, 50, 100, 250, 500, 1000, 1500] ],
            "iDisplayLength": 1000,
        } );
    } );
  </script>
  @endpush