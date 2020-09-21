@extends('hos.layouts.app')
@section('content')
<div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          @if(Session::has('message'))
            {!! Session::get('message') !!}
          @endif
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Order Details {{$order_code}}</b> </h5>
          </div>
          </div>
          <form action="{{route('hos.order.update')}}" method="POST">
          @csrf
          <div class="col-12 text-center">
            <table id="order_detail" class="table table-striped table-bordered example text-center">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Item #</th>
                      <th class="text-nowrap px-3">NUPCO Material</th>
                      <th class="text-nowrap px-3">Customer Code</th>
                      <th class="text-nowrap px-3">Description</th>
                      <th class="text-nowrap px-3">UOM</th>
                      <th class="text-nowrap px-3">Qty</th>
                      <th class="text-nowrap px-3">Batch</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($order_detail as $key=>$val)
                  <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_material_generic_code}}</td>
                      <td>{{$val->customer_trade_code}}</td>
                      <td>{{$val->material_description}}</td>
                      <td>{{$val->buom}}</td>
                      <td><input type="hidden" name="order_id[]" value="{{$val->id}}"><input type="text" class="form-control h_sm" name="qty[]" value="{{$val->qty}}"></td>
                      <td>
                      @if($val->batch_count > 0)
                      <button class="btn btn-warning btn_batch btn-sm" data-order_id="{{$val->id}}">Batch</button>
                      @endif
                      </td>
                  </tr>
                 @endforeach
              </tbody>
            </table>
          </div>
          <div class="col-12 text-center">
                  <button id="store_order_submit" class="btn btn-info my-2">Update</button>
          </div>
          </form>
        </div>
        @stop
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
              <tr class="table-primary text-white">
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
<script>
  $(document).ready(function() {
      $('#order_detail').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false
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
              url: "{{route('hos.order.batch.data')}}",
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