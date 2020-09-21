@extends('inbound.layouts.app')
@section('content')
<div class="container-fluid main_content bg-white p-2">
        @if(Session::has('message'))
          {!! Session::get('message') !!}
        @endif
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Order Details</b> </h5>
          </div>
           
          </div>
          <form action="{{route('inbound.order.update')}}" method="POST">
          @csrf
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Item #</th>
                      <th class="text-nowrap px-3">NUPCO Material</th>
                      <th class="text-nowrap px-3">NUPCO Trade Code</th>
                      <th class="text-nowrap px-3">Customer Code</th>
                      <th class="text-nowrap px-3">Description</th>
                      <th class="text-nowrap px-3">UOM</th>
                      <th class="text-nowrap px-3">Qty</th>
                      <th class="text-nowrap px-3">Delivery Date</th>
                  </tr>
              </thead>
              <tbody>
              @foreach($order_detail as $key=>$val)
                  <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_generic_code}}</td>
                      <td>{{$val->nupco_trade_code}}</td>
                      <td>{{$val->customer_code}}</td>
                      <td>{{$val->nupco_desc}}</td>
                      <td>{{$val->uom}}</td>
                      @if($order_detail[0]->status == 1)
                      <td><input type="hidden" name="order_id[]" value="{{$val->id}}"><input type="text" name="qty[]" value="{{$val->qty}}"></td>
                      @else
                      <td>{{$val->qty}}</td>
                      @endif
                      <td>{{$val->delivery_date}}</td>
                  </tr>
                 @endforeach
                 
              </tbody>
            </table>
          </div>
          <div class="col-12 text-center">
          @if($order_detail[0]->status == 0)
          <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal2">Approve</button>
          <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal">Reject</button>
          @elseif($order_detail[0]->status == 1)
            <button class="btn btn-success">Resubmit</button>
          @endif
          </div>
          </form>
        </div>
@stop
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header border-0">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <form id="rejection_form" method="POST" action="{{route('inbound.order.reject')}}">
      @csrf
      <input type="hidden" value="{{$order_code}}" name="order_code">
      <div class="modal-body">
        <h5 class="mb-3 text-danger text-center"><b>Reason For Rejection</b></h5>
        <table id="" class="table table-borderless reason_table mb-0">
          <tbody><tr>
            <td class="py-0 px-1" width="20%" style="border:0"><b>Reason</b></td>
            <td class="py-0 px-0" width="1%">:</td>
            <td class="py-0 px-1">
              <textarea class="form-control py-0 mb-1" rows="2" name="rejection_reason" style="width: 80%;"></textarea>
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
      <form id="approve_form" method="POST" action="{{route('inbound.order.approve')}}">
      @csrf
      <input type="hidden" value="{{$order_code}}" name="order_code">
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
@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $('.example').DataTable( {
        "order": [[ 1, "desc" ]],
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
            url: "{{route('inbound.order.batch.data')}}",
            type: 'POST',
            data: {"order_id":order_id,'_token':"{{ csrf_token() }}"},
            success : function(response) {
              var batch_tr = '';
              $.each(response, function(i, item) {
                batch_tr += '<tr><td>'+item.batch_qty+'<td>'+item.batch_no+'<td>'+item.manufacture_date+'<td>'+item.expiry_date+'</tr>';
              });
              $('#batch_table tbody').html(batch_tr);
              $('#batch_modal').modal('show');
            }
          });
    });
  });
  </script>
  @endpush

