@extends('custodian.layouts.app')
@section('content')
<style type="text/css">
  .gj-datepicker-bootstrap [role=right-icon] button .gj-icon {
    position: absolute;
    font-size: 21px;
    top: 5px!important;
    left: 9px;
}
</style>
<div class="container-fluid main_content bg-white p-2">
        @if(Session::has('message'))
          {!! Session::get('message') !!}
        @endif
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Order {{$order_id}} Details</b> </h5>
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
                <label class="col-md-7 col-sm-7 col-7">{{$order_detail[0]->address}} </label>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-12">
             
            </div>
            <div class="col-md-5 col-sm-6 col-12 order-3 order-sm-3">
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Delivery Date</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-3 col-sm-7 col-7" >{{$order_detail[0]->delivery_date}}
                </label>
                 <button class="btn btn-warning btn-sm col-md-4" data-delivery_date="{{$order_detail[0]->delivery_date}}" id="date_change_btn">Date Change</button>
              </div>
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Order Date</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">{{date('Y-m-d',strtotime($order_detail[0]->created_date))}}
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Status</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">
                        @if($order_detail[0]->status == 0)
                          <span class="text-warning"><b>NEW</b></span>
                        @elseif($order_detail[0]->status == 1)
                          <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($order_detail[0]->status == 2)
                          <span class="text-success"><b>APPROVED</b></span>
                        @elseif($order_detail[0]->status == 3)
                          <span class="text-primary"><b>DISPATCHED</b></span>
                        @elseif($order_detail[0]->status == 4)
                          <span class="text-info"><b>DELIVERED</b></span>
                        @elseif($order_detail[0]->status == 5)
                          <span class="text-danger"><b>CANCELLED</b></span>
                        @endif
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Header Text</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">
                  <input type="hidden" class="form-control h_1rem" data-row_id="ht1" data-name="header_text" id="text_ht1" name="header_text" value="{{$order_detail[0]->header_text}}">
                  <i class="fas fa-file-alt text_icon" aria-hidden="true" data-row_id="ht1"></i>
                </label>
              </div>
            </div>
        </div>
          <form action="{{route('custodian.order.update')}}" method="POST">
          @csrf
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Item #</th>
                      <th class="text-nowrap px-3">NUPCO Material</th>
                      <th class="text-nowrap px-3">NUPCO Trade Code</th>
                      <th class="text-nowrap px-3">Customer Code</th>
                      <th class="text-nowrap px-3">Category</th>
                      <th class="text-nowrap px-3">Description</th>
                      <th class="text-nowrap px-3">UOM</th>
                      <th class="text-nowrap px-3" >Order Qty</th>
                      <th class="text-nowrap px-3">Item Text</th> 
                  </tr>
              </thead>
              <tbody>
              @foreach($order_detail as $key=>$val)
                  <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_generic_code}}</td>
                      <td>{{$val->nupco_trade_code}}</td>
                      <td>{{$val->customer_trade_code}}</td>
                      <td>{{$val->category}}</td>
                      <td>{{$val->material_desc}}</td>
                      <td>{{$val->uom}}</td>
                      <td>{{$val->qty_ordered}}</td>
                      <td><input type="hidden" class="form-control h_1rem" data-row_id ="{{$key}}" data-name="item_text" id="text_{{$key}}" name="item_text[]" value="{{$val->item_text}}"><i class="fas fa-file-alt text_icon" aria-hidden="true" data-row_id ="{{$key}}"></i></td>
                  </tr>
                 @endforeach
                 
              </tbody>
            </table>
          </div>
          <div class="col-12 text-center">
          @if($order_detail[0]->status == 0)
          <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal2">Approve</button>
          <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal">Reject</button>
          @endif
          </div>
          </form>
    </div>
@push('modal_content')
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header border-0">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <form id="rejection_form" method="POST" action="{{route('custodian.order.reject')}}">
      @csrf
      <input type="hidden" value="{{$order_id}}" name="order_id">
      <div class="modal-body">
        <h5 class="mb-3 text-danger text-center"><b>Reason For Rejection</b></h5>
        <table id="" class="table table-borderless reason_table mb-0">
          <tbody><tr>
            <td class="py-0 px-1" width="20%" style="border:0"><b>Reason</b></td>
            <td class="py-0 px-0" width="1%">:</td>
            <td class="py-0 px-1">
              <textarea class="form-control py-0 mb-1" rows="2" name="rejection_reason" style="width: 80%;" required></textarea>
            </td>
          </tr>
        </tbody>
        </table>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer py-2 my-3 border-0">
        <button name="submit" type="submit" value="submit" class="btn btn-info px-5 mx-auto">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal" id="myModal2">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header border-0">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <form id="approve_form" method="POST" action="{{route('custodian.order.approve')}}">
      @csrf
      <input type="hidden" value="{{$order_id}}" name="order_id">
      <div class="modal-body">
        <h5 class="mb-3 text-danger text-center"><b>Additional Comment</b></h5>
        <table id="" class="table table-borderless reason_table mb-0">
          <tbody><tr>
            <td class="py-0 px-1" width="20%" style="border:0"><b>comment</b></td>
            <td class="py-0 px-0" width="1%">:</td>
            <td class="py-0 px-1">
              <textarea class="form-control py-0 mb-1" rows="2" name="approve_comment" style="width: 80%;"></textarea>
            </td>
          </tr>
        </tbody>
        </table>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer py-2 my-3 border-0">
        <button name="submit" type="submit" value="submit" class="btn btn-info px-5 mx-auto">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>

 <!-- Batch Modal -->
 <div class="modal" id="batch_modal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="text-center w-100"><b>Batch Details</b></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <form id="approve_form">
      @csrf
      <input type="hidden" name="order_id" id="order_id">
      <div class="modal-body">
       <div class="table-responsive">
        <table id="batch_table" class="table table-bordered table-sm text-center">
          <thead>
              <tr class="table-primary">
                  <th>Batch QTY</th>
                  <th>Batch No</th>
                  <th>Manufacture Date</th>
                  <th>Expiry Date</th>
              </tr>
          </thead>
          <tbody>
              
        </tbody>
        </table>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- The Modal -->
<div class="modal" id="date_change">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title"><b>Change Date</b></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{route('custodian.order.date.change')}}" method="POST">
      @csrf
        <!-- Modal body -->
        <div class="modal-body">
        <input type="hidden" name="order_id" value="{{$order_id}}">
          <div class="form-group">
            <!-- <label><b>Date :</b></label> -->
            <input type="" class="form-control form-control-sm pb-1" id="delivery_date_order" name="delivery_date_order">
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer justify-content-center">
          <button class="btn btn-success btn-sm">Submit</button>
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">close</button>
        </div>
      </form>
    </div>
  </div>
</div>

 <!-- The text Modal -->
 <div class="modal" id="text_modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header border-0">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <h5 class="mb-3 text-danger text-center"><b>Order Text</b></h5>
        <table id="" class="table table-borderless reason_table mb-0">
          <tbody><tr>
            <td class="py-0 px-1" width="20%" style="border:0"><b>Text  : </b></td>
            <td class="py-0 px-1">
              <textarea class="form-control py-0 mb-1" rows="2" name="item_text" id="text_input" style="width: 80%;" disabled></textarea>
            </td>
          </tr>
        </tbody>
        </table>
      </div>
       <!-- Modal footer -->
       <div class="modal-footer py-2 my-3 border-0">
      </div>
    </div>
  </div>
</div>

@endpush
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

    $('#batch_table').DataTable({
      "searching": false,
      "paging": false,
      "ordering": false,
      "bInfo": false,
    });

    $('.btn_batch').click(function(e){
      e.preventDefault();
      var order_id = $(this).data('order_id');
      $.ajax({
            url: "{{route('custodian.order.batch.data')}}",
            type: 'POST',
            data: {"order_id":order_id,'_token':"{{ csrf_token() }}"},
            success : function(response) {
              var batch_tr = '';
              $.each(response, function(i, item) {
                batch_tr += '<tr><td>'+item.batch_qty_ordered+'<td>'+item.batch_no+'<td>'+item.manufacture_date+'<td>'+item.expiry_date+'</tr>';
              });
              $('#batch_table tbody').html(batch_tr);
              $('#batch_modal').modal('show');
            }
          });
    });

    $("#delivery_date_order").datepicker({ 
      format: 'yyyy-mm-dd',
      uiLibrary: 'bootstrap4'
    });  

    $('#date_change_btn').click(function(e){
      e.preventDefault();
      var delivery_date = $(this).data('delivery_date');
      $('#delivery_date_order').val(delivery_date);
      $('#date_change').modal('show');
    });

    $(".text_icon").click(function(){
        var icon_row_id = $(this).data('row_id');
        $('#text_input').val($('#text_'+icon_row_id).val());
        $('#text_save').data('row_id',icon_row_id);
        $('#text_modal').modal('show');
    });

  });
  </script>
  @endpush

