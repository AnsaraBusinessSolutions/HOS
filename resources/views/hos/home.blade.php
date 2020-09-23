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
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Exist Order list</b> </h5>
          </div>
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Store Order #</th>
                      <th class="text-nowrap px-3">Supplying Plant</th>
                      <th class="text-nowrap px-3">Delivery date</th>
                      <th class="text-nowrap px-3">Order Item</th>
                      <th class="text-nowrap px-3">Qty Ordered</th>
                      <th class="text-nowrap px-3">Ordered Date</th>
                      <th class="text-nowrap px-3">Status</th>
                  </tr>
              </thead>
              <tbody>
              @foreach($all_order as $key=>$val)
                  <tr onclick="window.location.href='{{url('store/order_detail/'.$val->order_id)}}'">
                      <td>{{$val->order_id}}</td>
                      <td>{{$val->supplying_plant}}</td>
                      <td>{{$val->delivery_date}}</td>
                      <td>{{$val->total_item}}</td>
                      <td>{{$val->total_qty}}</td>
                      <td>{{date('Y-m-d', strtotime($val->created_date))}}</td>
                      <td>
                      @if($val->status == 0)
                          <span class="text-warning"><b>NEW</b></span>
                        @elseif($val->status == 1)
                          <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($val->status == 2)
                          <span class="text-success"><b>APPROVED</b></span>
                        @elseif($val->status == 3)
                          <span class="text-danger"><b>DISPATCHED</b></span>
                        @elseif($val->status == 4)
                          <span class="text-info"><b>DELIVERED</b></span>
                        @elseif($val->status == 5)
                          <span class="text-danger"><b>CANCELLED</b></span>
                        @endif
                      </td>
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
            "ordering": false,
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