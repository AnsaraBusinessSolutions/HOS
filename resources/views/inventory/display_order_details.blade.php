@extends('inventory.layouts.app')
<style>
.dataTables_scrollBody{
  position: relative;
    overflow: hidden!important;
    width: 130%!important;
    max-height: 41vh;
}
.dataTables_scrollHead{
  overflow: hidden;
    position: relative;
    border: 0px;
    width: 130%!important;
}
.dataTables_scroll{
  overflow-x:auto;
}
</style>
@section('content')
<div class="container-fluid main_content bg-white p-2">
        @if(Session::has('message'))
          {!! Session::get('message') !!}
        @endif
          <div class="row mx-0 border">
            <div class="col-md-5 col-sm-6 col-12">
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Order ID</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">{{$order_id}}
                </label>
              </div>
              <div class="form-row py-1">
                <label class="col-md-4 col-sm-4 col-4"><b>Supply WH</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">{{$order_detail[0]->delivery_wh_name}}
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Delivery Address</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">{{$order_detail[0]->address}} </label>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-12">
             
            </div>
            <div class="col-md-5 col-sm-6 col-12 order-3 order-sm-3">
              <div class="form-row">
                <label class="col-md-6 col-sm-4 col-4"><b>Delivery Date</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-5 col-sm-7 col-7" >{{$order_detail[0]->delivery_date}}
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-6 col-sm-4 col-4"><b>Order Date</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-5 col-sm-7 col-7">{{date('Y-m-d',strtotime($order_detail[0]->created_date))}}
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-6 col-sm-4 col-4"><b>Status</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-5 col-sm-7 col-7">
                        @if($status_data->status == '0')
                          <span class="text-warning"><b>NEW</b></span>
                        @elseif($status_data->status == '1')
                          <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($status_data->status == '2')
                          <span class="text-success"><b>APPROVED</b></span>
                        @elseif($status_data->status == '3')
                          <span class="text-primary"><b>DISPATCHED</b></span>
                        @elseif($status_data->status == '4')
                          <span class="text-info"><b>DELIVERED</b></span>
                        @else
                          <span class="text-primary" style="font-size: 14px"><b>PARTIALLY DELIVERED</b></span>
                        @endif
                </label>
              </div>
            </div>
          </div>
          <div class="col-12 text-center">
            <table id="display_order" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Item #</th>
                      <th class="text-nowrap px-3">PGI No</th>
                      <th class="text-nowrap px-3">GRN No</th>
                      <th class="text-nowrap px-3">NUPCO Material</th>
                      <th class="text-nowrap px-3">NUPCO Trade Code</th>
                      <th class="text-nowrap px-3">Customer Code</th>
                      <th class="text-nowrap px-3">Category</th>
                      <th class="text-nowrap px-3">Description</th>
                      <th class="text-nowrap px-3">UOM</th>
                      <th class="text-nowrap px-3">Qty Order</th>
                      <th class="text-nowrap px-3">Qty Dispatch</th>
                      <th class="text-nowrap px-3">Qty Received</th>
                      <th class="text-nowrap px-3">Manufacture Date</th>
                      <th class="text-nowrap px-3">Expiry Date</th>
                      <th class="text-nowrap px-3">Dispatch Date</th>
                  </tr>
              </thead>
              <tbody>
              
              @foreach($order_detail as $key=>$val)
                  <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$val->pgi_id}}</td>
                      <td>{{$val->grn_id}}</td>
                      <td>{{$val->nupco_generic_code}}</td>
                      <td>{{$val->nupco_trade_code}}</td>
                      <td>{{$val->customer_trade_code}}</td>
                      <td>{{$val->category}}</td>
                      <td>{{$val->material_desc}}</td>
                      <td>{{$val->uom}}</td>
                      <td>{{$val->qty_ordered}}</td>
                      <td>{{$val->batch_qty}}</td>
                      <td>{{$val->received_qty}}</td>
                      <td>{{$val->manufacture_date}}</td>
                      <td>{{$val->expiry_date}}</td>
                      <td>{{date('Y-m-d',strtotime($val->created_at))}}</td>
                  </tr>
                 @endforeach
              </tbody>
            </table>
          </div> 
        </div>
    </div>
@stop
@push('scripts')
<script>
$(function() {
  //Datatable for display delivered order items
  var counter = 1;
    $('#display_order').DataTable( {
        "ordering": false,
        "scrollY":        "55vh",
        "scrollCollapse": true,
        "paging":         false,
        "searching": false,
        "lengthMenu": [ [15, 30, 50, 100, 250, 500, 1000, 1500], [15, 20, 50, 100, 250, 500, 1000, 1500] ],
        "iDisplayLength": 1000,
    });
});
</script>
@endpush

