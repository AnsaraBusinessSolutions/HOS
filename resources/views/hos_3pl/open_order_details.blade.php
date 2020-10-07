@extends('hos_3pl.layouts.app')
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
                          <span class="text-primary" style="font-size: 14px"><b>PARTIALLY DISPATCHED</b></span>
                        @endif
                </label>
              </div>
            </div>
          </div>
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
                      <th class="text-nowrap px-3">Order Qty</th>
                      <th class="text-nowrap px-3">Dispatch Qty</th>
                      <th class="text-nowrap px-3">Open Qty</th>
                      <th class="text-nowrap px-3">Batch</th>
                  </tr>
              </thead>
              <tbody>
              
              @foreach($order_detail as $key=>$val)
                 @if($val->is_deleted == 1)
                  <tr class="dis_row_input">
                  @else
                  <tr>
                  @endif
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_generic_code}}</td>
                      <td>{{$val->nupco_trade_code}}</td>
                      <td>{{$val->customer_trade_code}}</td>
                      <td>{{$val->category}}</td>
                      <td>{{$val->material_desc}}</td>
                      <td>{{$val->uom}}</td>
                      <td>{{$val->qty_ordered}}</td>
                      @if(!empty($val->dispatch_batch_count))
                      <td>{{$val->dispatch_batch_count}}</td>
                      <td>{{$val->qty_ordered - $val->dispatch_batch_count}}</td>
                      <td>@if($val->is_deleted == 0)<button class="btn btn-small btn-warning batch_btn" data-open_qty="{{$val->qty_ordered - $val->dispatch_batch_count}}" data-order_id="{{$val->order_id}}" data-order_main_id="{{$val->id}}" data-status="{{$val->status}}">Batch</button>@endif</td>
                      @else
                      <td>@if(!empty($val->added_batch_qty)){{$val->added_batch_qty}}@else 0 @endif</td>
                      <td>{{$val->qty_ordered - 0}}</td>
                      <td>@if($val->is_deleted == 0)<button class="btn btn-small btn-warning batch_btn" data-open_qty="{{$val->qty_ordered - 0}}" data-order_id="{{$val->order_id}}" data-order_main_id="{{$val->id}}" data-status="{{$val->status}}">Batch</button>@endif</td>
                      @endif
                      
                  </tr>
                 @endforeach
              </tbody>
            </table>
          </div>
              <div class="col-12 text-center">
                <input type="hidden" value="3" name="order_status">
                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#dipatch_modal">Dispatch</button>
              </div>
        </div>
    </div>
@push('modal_content')
<!-- Dispatch Modal -->
<div class="modal" id="dipatch_modal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="text-center w-100"><b>Dispatch Details</b></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <form id="approve_form" method="POST" action="{{route('hos3pl.order.dispatch')}}">
      @csrf
      <input type="hidden" value="{{$order_id}}" name="order_id">
      <input type="hidden" name="supplying_plant" value="{{$order_detail[0]->delivery_wh_name}}">
      <input type="hidden" name="hss_master_no" value="{{$order_detail[0]->hss_master_no}}">
      <input type="hidden" name="hospital_name" value="{{$order_detail[0]->hospital_name}}">
      <input type="hidden" name="delivery_date" value="{{$order_detail[0]->delivery_date}}">
      <input type="hidden" name="redirect_page_name" value="open_order">
      <div class="modal-body">
       <div class="table-responsive">
        <table id="dispatch_table" class="table table-bordered table-sm text-center">
          <thead>
              <tr class="table-primary">
                  <th>Vehical NO.</th>
                  <th>Total Item</th>
                  <th>Total QTY</th>
                  <th>Delivery Date</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td><input class="form-control" type="text" name="vehical_number" id="vehical_number" required maxlength="10" autocomplete="off"></td>
                  <td>{{count($order_detail)}}</td>
                  <td>{{$total_qty}}</td>
                  <td>{{$order_detail[0]->delivery_date}}</td>
              </tr>
        </tbody>
        </table>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer py-2 my-3 border-0 justify-content-center">
        <button name="submit" type="submit" value="submit" class="btn btn-success">Submit</button>
        <button name="submit" type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
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
      <form id="batch_add_form" method="POST" action="{{route('hos3pl.order.batch.insert')}}">
      @csrf
      <input type="hidden" name="order_id" id="order_id">
      <input type="hidden" name="order_main_id" id="order_main_id">
      <input type="hidden" name="open_qty" id="open_qty">
      <div class="modal-body">
       <div class="table-responsive">
        <table id="batch_table" class="table table-bordered table-sm text-center">
          <thead>
              <tr class="table-primary">
                  <th>Batch QTY</th>
                  <th>Batch No</th>
                  <th>Manufacture Date</th>
                  <th>Expiry Date</th>
                  <th class="hide_btn">Action</th>
              </tr>
          </thead>
          <tbody>
          <button name="submit" type="button"  class="btn btn-success float-right hide_btn" id="add_batch">Add Batch</button>
              
        </tbody>
        </table>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer py-2 my-3 border-0 justify-content-center">
        <button name="submit" type="submit" id="batch_submit" value="submit" class="btn btn-success hide_btn">Submit</button>
        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endpush
@stop
@push('scripts')
<script>
$(function() {
  var counter = 1;
    $('.example').DataTable( {
        "ordering": false,
        "scrollY":        "55vh",
        "scrollCollapse": true,
        "paging":         false,
        "searching": false,
        "lengthMenu": [ [15, 30, 50, 100, 250, 500, 1000, 1500], [15, 20, 50, 100, 250, 500, 1000, 1500] ],
        "iDisplayLength": 1000,
    });

    
    $('#add_batch').click(function () {
        var tr = $('<tr><td><input class="form-control batch_qty" type="text" name="batch_qty[]" id="batch_qty_'+counter+'" required maxlength="10" autocomplete="off"></td>'
                  +'<td><input class="form-control" type="text" name="batch_no[]" id="batch_no_'+counter+'" required maxlength="10" autocomplete="off"></td>'
                  +'<td><input class="manufacture_date form-control datepicker" type="" name="manufacture_date[]" id="manufacture_date_'+counter+'" required autocomplete="off"></td>'
                  +'<td><input class="expiry_date form-control datepicker" type="" name="expiry_date[]" id="expiry_date_'+counter+'" required autocomplete="off"></td><td><i onclick="deleteRow(this)" class="fas fas fa-times"></i></i></td></tr>');

        $('#batch_table tbody').append(tr);
        $(tr).find('.expiry_date').datepicker({
                autoclose: true,
                uiLibrary: 'bootstrap4'
        });
        $(tr).find('.manufacture_date').datepicker({
                autoclose: true,
                uiLibrary: 'bootstrap4'
        });
        counter++;
    });

    $('.batch_btn').click(function(){
        var order_id = $(this).data('order_id');
        var order_main_id = $(this).data('order_main_id');
        var open_qty = $(this).data('open_qty');
        var status = $(this).data('status');
        $('#order_id').val(order_id);
        $('#order_main_id').val(order_main_id);
        $('#open_qty').val(open_qty);

        if(order_id != ''){
          $.ajax({
            url: "{{route('hos3pl.batch.data')}}",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            type: 'POST',
            data: {"order_id":order_id,"order_main_id":order_main_id,"status":status},
            success : function(response) {
              var batch_tr = '';
              $.each(response, function(i, item) {
                batch_tr += '<tr><td><input class="form-control batch_qty" type="text" name="batch_qty[]" id="batch_qty_'+counter+'" required maxlength="10" value="'+item.batch_qty+'" autocomplete="off"></td>'
                  +'<td><input class="form-control" type="text" name="batch_no[]" id="batch_no_'+counter+'" required maxlength="10" value="'+item.batch_no+'" autocomplete="off"></td>'
                  +'<td><input class="manufacture_date form-control datepicker" type="" name="manufacture_date[]" id="manufacture_date_'+counter+'" required value="'+item.manufacture_date+'" autocomplete="off"></td>'
                  +'<td><input class="expiry_date form-control datepicker" type="" name="expiry_date[]" id="expiry_date_'+counter+'" required value="'+item.expiry_date+'" autocomplete="off"></td><td class="hide_btn"><i onclick="deleteRow(this)" class="fas fas fa-times"></i></i></td></tr>';

                //batch_tr += '<tr><td>'+item.batch_qty_ordered+'<td>'+item.batch_no+'<td>'+item.manufacture_date+'<td>'+item.expiry_date+'</tr>';
              });
              $('#batch_table tbody').html(batch_tr);

              $('.expiry_date').datepicker({
                      autoclose: true,
                      uiLibrary: 'bootstrap4',
                      dateFormat: 'dd/mm/y'
              });
              $('.manufacture_date').datepicker({
                      autoclose: true,
                      uiLibrary: 'bootstrap4'
              });
              if(status == 3){
                $('.hide_btn').hide();
                $('#batch_modal input').prop('disabled',true);
              }else{
                $('.hide_btn').show();
              }
              $('#batch_modal').modal('show');
              counter = $('#batch_table tbody tr').length + 1;
              if(counter == 1){
                $('#add_batch').click();
              } 
            }
          });
         
        
        }
    });

    $('#batch_submit').click(function(e){
      e.preventDefault();
      var total_qty = 0;
     
        $("#batch_table .batch_qty").each(function () {
              total_qty += parseFloat($(this).val());
        });
        
        var item_open_qty = $('#open_qty').val();
        if(total_qty > item_open_qty){
            alert('Open qty is ' +item_open_qty+' So you can not add more than open qty');
        }else{
          var formData = new FormData(document.getElementById("batch_add_form"));
          $.ajax({
                 url:"{{ route('hos3pl.order.batch.insert') }}",
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                 method:"POST",
                 data:formData,
                 dataType:'JSON',
                 contentType: false,
                 cache: false,
                 processData: false,
                 success:function(data)
                 {
                    location.reload(true);
                 }
          })
        }
    });

    

    $('#batch_modal').on('hidden.bs.modal', function () {
      $('#batch_modal').find('tbody tr').remove();
      counter = 1;
    });

});
  function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}
</script>
@endpush

