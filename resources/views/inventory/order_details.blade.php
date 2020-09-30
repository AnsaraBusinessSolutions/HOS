@extends('inventory.layouts.app')
@section('content')
<div class="container-fluid main_content bg-white p-2">
        @if(Session::has('message'))
          {!! Session::get('message') !!}
        @endif
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Order {{$order_id}} Details</b> </h5>
            @if(!empty($order_detail))<h6>PGI No. {{$order_detail[0]->pgi_id}}</h6>@endif
          </div>
          </div>
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
                <label class="col-md-7 col-sm-7 col-7 text-truncate">{{$order_detail[0]->address}} </label>
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
                <label class="col-md-5 col-sm-7 col-7">{{date('Y-m-d',strtotime($order_detail[0]->created_at))}}
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-6 col-sm-4 col-4"><b>Status</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-5 col-sm-7 col-7">
                        @if($status_data->status == '2,3' || $status_data->status == '3,2')
                          <span class="text-primary" style="font-size: 14px"><b>PARTIALLY DISPATCHED</b></span>
                        @elseif($status_data->status == '2,3,4' || $status_data->status == '3,4')
                          <span class="text-primary" style="font-size: 14px"><b>PARTIALLY DELIVERED</b></span>
                        @elseif($status_data->status == 0)
                          <span class="text-warning"><b>NEW</b></span>
                        @elseif($status_data->status == 1)
                          <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($status_data->status == 2)
                          <span class="text-success"><b>APPROVED</b></span>
                        @elseif($status_data->status == 3)
                          <span class="text-primary"><b>DISPATCHED</b></span>
                        @elseif($status_data->status == 4)
                          <span class="text-info"><b>DELIVERED</b></span>
                        @elseif($status_data->status == 5)
                          <span class="text-danger"><b>CANCELLED</b></span>
                        @endif
                </label>
              </div>
            </div>
        </div>
          <form action="{{route('inventory.create.grn')}}" method="POST">
          @csrf
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">#</th>
                      <th class="text-nowrap px-3">Item #</th>
                      <th class="text-nowrap px-3">NUPCO Material</th>
                      <th class="text-nowrap px-3">NUPCO Trade Code</th>
                      <th class="text-nowrap px-3">Customer Code</th>
                      <th class="text-nowrap px-3">Category</th>
                      <th class="text-nowrap px-3">Description</th>
                      <th class="text-nowrap px-3">UOM</th>
                      <th class="text-nowrap px-3" >Order Qty</th>
                      <th class="text-nowrap px-3" >Batch Qty</th>
                      <th class="text-nowrap px-3" >Batch No</th>
                      <th class="text-nowrap px-3" >Received Qty</th>
                  </tr>
              </thead>
              <tbody>
              @foreach($order_detail as $key=>$val)
                  <tr>
                      <td><input type="checkbox" class="select_item" data-row_id="{{$key}}"></td>
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_generic_code}}</td>
                      <td>{{$val->nupco_trade_code}}</td>
                      <td>{{$val->customer_trade_code}}</td>
                      <td>{{$val->category}}</td>
                      <td>{{$val->material_desc}}</td>
                      <td>{{$val->uom}}</td>
                      <td>{{$val->qty_ordered}}</td>
                      <td>{{$val->batch_qty}}</td>
                      <td>{{$val->batch_no}}</td>
                      @if($status_data->status == 4)
                      <td>{{$val->received_qty}}</td>
                      @else
                      <td><input type="hidden" name="pgi_main_id[]" id="pgi_main_id_{{$key}}" value="{{$val->id}}" disabled><input class="form-control" id="rec_qty_{{$key}}" type="text" name="received_qty[]" required autocomplete="off" disabled></td>
                      @endif  
                  </tr>
                 @endforeach
                 
              </tbody>
            </table>
          </div>
          @if($status_data->status != 4)
          <div class="col-12 text-center">
            <button class="btn btn-success" type="submit">Update</button>
          </div>
          @endif
          </form>
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
    });

    $(".select_item").click(function () {
      var row_id = $(this).data('row_id');
      if ($(this).is(":checked")) {
        $("#rec_qty_"+row_id).removeAttr("disabled");
        $("#pgi_main_id_"+row_id).removeAttr("disabled");
      }else{
        $("#rec_qty_"+row_id).attr("disabled", "disabled");
        $("#pgi_main_id_"+row_id).attr("disabled", "disabled");
      }
    });

  });
  </script>
  @endpush

