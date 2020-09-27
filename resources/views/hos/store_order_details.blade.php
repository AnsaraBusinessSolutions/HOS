<style>
 .dis_row_input td input{
    pointer-events:none;
    color:#AAA;
    background:#F5F5F5;
 }
</style>
@extends('hos.layouts.app')
@section('content')
<div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          @if(Session::has('message'))
            {!! Session::get('message') !!}
          @endif

          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Order {{$order_id}} Details</b></h5>
            <h6>@if(!empty($pgi_details))PGI No. {{$pgi_details->pgi_id}}@endif</h6>
          </div>
          </div>
          <form action="{{route('hos.order.update')}}" method="POST">
          @csrf
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
                <input type="hidden" name="delivery_date" value="{{$order_detail[0]->delivery_date}}">
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
            </div>
          </div>
          @if($order_detail[0]->status == 0 || $order_detail[0]->status == 1)
          <div class="row mx-0 pt-1 pr-3">
             <div class="form-group w-100 text-right mb-0">
               <button class="btn btn-success btn-sm" id="addRow">Add Item</button>
             </div>
          </div>
          @endif
          <input type="hidden" name="supplying_plant" value="{{$order_detail[0]->delivery_wh_name}}">
          <input type="hidden" name="hss_master_no" value="{{$order_detail[0]->hss_master_no}}">
          <input type="hidden" name="hospital_name" value="{{$order_detail[0]->hospital_name}}">
          <input type="hidden" name="order_id" value="{{$order_id}}">
          <div class="col-12 text-center">
            <table id="order_detail" class="table table-striped table-bordered example search_data text-center">
              <thead>
                  <tr class="bg_color">
                      @if($order_detail[0]->status == 0 || $order_detail[0]->status == 1)
                      <th>Delete</th>
                      @endif
                      <th class="text-nowrap px-3">Item #</th>
                      <th class="text-nowrap px-3">NUPCO Material</th>
                      <th class="text-nowrap px-3">NUPCO Trade Code</th>
                      <th class="text-nowrap px-3">Customer Code</th>
                      <th class="text-nowrap px-3">Category</th>
                      <th class="text-nowrap px-3">Description</th>
                      <th class="text-nowrap px-3">UOM</th>
                      <th class="text-nowrap px-3">Order Qty</th>
                      <th class="text-nowrap px-3">Availability</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($order_detail as $key=>$val)
                  @if($val->is_deleted == 1)
                  <tr class="dis_row_input">
                  @else
                  <tr>
                  @endif
                      @if($val->status == 0 || $val->status == 1)
                      <td><input type="checkbox" class="delete_row" name="delete_row[]" data-delete_id = "{{$val->id}}" /></td>
                      <td>{{$key+1}}</td>
                      <td><input type="text" class="material_data form-control h_1rem" data-row_id="{{$key}}" data-name="nupco_generic_code"  id="nupco_generic_code_{{$key}}" name="nupco_generic_code[]" value="{{$val->nupco_generic_code}}" autocomplete="off"><div id="nupco_generic_code_list_{{$key}}" class="position-relative"></div></td>
                      <td><input type="text" class="form-control h_1rem" data-row_id="{{$key}}" data-name="nupco_trade_code_" id="nupco_trade_code_{{$key}}" name="nupco_trade_code[]" value="{{$val->nupco_trade_code}}" readonly></td>
                      <td><input type="text" class="material_data form-control h_1rem" data-row_id="{{$key}}" data-name="customer_code" id="customer_code_{{$key}}" name="customer_code[]" value="{{$val->customer_trade_code}}" autocomplete="off"><div id="customer_code_list_{{$key}}"></div></td>
                      <td><input type="text" class="form-control h_1rem" data-row_id="{{$key}}" data-name="customer_code_cat" id="customer_code_cat_{{$key}}" name="customer_code_cat[]" value="{{$val->category}}" readonly></td>
                      <td><input type="text" class="material_data form-control h_1rem" data-row_id="{{$key}}" data-name="nupco_desc" id="nupco_desc_{{$key}}" name="nupco_desc[]" value="{{$val->material_desc}}" autocomplete="off"><div id="nupco_desc_list_{{$key}}" class="position-relative"></div></td>
                      <td><input type="text" class="form-control h_1rem" data-row_id="{{$key}}" data-name="uom" id="uom_{{$key}}" name="uom[]" value="{{$val->uom}}" readonly></td>
                      <td><input type="hidden" name="order_primary_id[]" value="{{$val->id}}"><input type="text" class="form-control h_1rem" data-row_id="{{$key}}" data-name="qty" id="qty_{{$key}}" name="qty[]" value="{{$val->qty_ordered}}"></td>
                      <td><input type="text" class="form-control h_1rem" data-row_id="{{$key}}" data-name="available" id="available_{{$key}}" value="available" name="available[]" readonly></td>
                      @else
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_generic_code}}</td>
                      <td>{{$val->nupco_trade_code}}</td>
                      <td>{{$val->customer_trade_code}}</td>
                      <td>{{$val->category}}</td>
                      <td>{{$val->material_desc}}</td>
                      <td>{{$val->uom}}</td>
                      <td>{{$val->qty_ordered}}</td>
                      <td>Available</td>
                      @endif
                      
                  </tr>
                 @endforeach
              </tbody>
            </table>
          </div>
          @if(count($order_detail) > 0 && ($order_detail[0]->status == 0 || $order_detail[0]->status == 1))
          <div class="col-12 text-center">
              <button id="store_order_submit" class="btn btn-info my-2">Update and Resubmit</button>
          </div>
          @endif
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
var table;
  $(document).ready(function() {
    table = $('#order_detail').DataTable({
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

    autoSearchMaterial();
    deleteRow();

    var counter = {{count($order_detail)}};
    $('#addRow').on('click', function (e) { 
      e.preventDefault();
      table.row.add( [
            // '<td width="3%" class="text-nowrap px-3"><input type="checkbox" data-row_id ="'+counter+'"></td>',
            '<td><input type="checkbox" class="delete_row"/></td>',
            '<td class="p-0">'+(counter+1)+'</td>',
            '<td class="p-0"><input type="text" class="material_data form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_generic_code" id="nupco_generic_code_'+counter+'" name="new_nupco_generic_code[]" maxlength="20" autocomplete="off"><div id="nupco_generic_code_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_trade_code" id="nupco_trade_code_'+counter+'" name="new_nupco_trade_code[]" maxlength="20" autocomplete="off" readonly></td>',
            '<td class="p-0"><input type="text"  class="material_data form-control h_1rem"  data-row_id ="'+counter+'" data-name="customer_code" id="customer_code_'+counter+'" name="new_customer_code[]" maxlength="20" autocomplete="off"><div id="customer_code_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text"  class="form-control h_1rem"  data-row_id ="'+counter+'" data-name="customer_code_cat" id="customer_code_cat_'+counter+'" name="new_customer_code_cat[]" readonly><div id="customer_code_cat_list_'+counter+'"></div></td>',
            '<td class="p-0"><input type="text"  class="material_data form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_desc" id="nupco_desc_'+counter+'" name="new_nupco_desc[]" autocomplete="off"><div id="nupco_desc_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="uom" id="uom_'+counter+'" name="new_uom[]" readonly></td>',
            '<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="qty" id="qty_'+counter+'" name="new_qty[]" onkeypress="return onlyNumberKey(event)" maxlength="15" autocomplete="off"></td>',
            '<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="available" id="available_'+counter+'" name="new_available[]" readonly></td>',
           ]).draw( false );
        counter++;
        autoSearchMaterial();
        deleteRow();
    });

      $('.btn_batch').click(function(e){
        e.preventDefault();
        var order_id = $(this).data('order_id');
        $.ajax({
              url: "{{route('hos.order.batch.data')}}",
              headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              type: 'POST',
              data: {"order_id":order_id},
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
  });

  

  function autoSearchMaterial(){
  $('input.material_data').keyup(function(){ 
      var row_id = $(this).data('row_id');
      var input_data = $(this).val();
      var input_name = $(this).data('name');
      if(input_data != '')
      {
        var token = "{{ csrf_token() }}";
        $.ajax({
          url:"{{ route('hos.search.data') }}",
          headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method:"POST",
          data:{input_data:input_data,input_name:input_name},
          success:function(data){
            $('#'+input_name+'_list_'+row_id).html('');
            if(data != ''){
              $('#'+input_name+'_list_'+row_id).fadeIn();
              $('#'+input_name+'_list_'+row_id).html(data);
              $("#order_detail li").bind("click",function(){
                  $('#'+input_name+'_list_'+row_id).fadeOut();  
                  var li_data = $(this).text();
                  setMaterialData(this,input_name,row_id);
              });
            }
          }
        });
      }
    });
}

function setMaterialData(element,input_name,row_id){
    var input_data = $(element).text();
    $.ajax({
          url: '{!! route('hos.material.data') !!}',
          headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'post',
          dataType: "json",
          data: {
              input_data:input_data,
              input_name:input_name
          }, 
          success: function(response)
          {
              if(response.data.length > 0){
                  $('#nupco_trade_code_'+row_id).val(response.data[0].nupco_trade_code);
                  $('#nupco_generic_code_'+row_id).val(response.data[0].nupco_generic_code);
                  $('#customer_code_'+row_id).val(response.data[0].customer_code);
                  $('#customer_code_cat_'+row_id).val(response.data[0].customer_code_cat);
                  $('#nupco_desc_'+row_id).val(response.data[0].nupco_desc);
                  $('#uom_'+row_id).val(response.data[0].uom);
                  $('#available_'+row_id).val('available');
                  $('#qty_'+row_id).val('');
              }else{
                  $('#'+input_name+'_'+row_id).val('');
              }
          }
    });
 }

 function deleteRow(){
  $('#order_detail tr td input[type="checkbox"]').click( function() {
        $(this).closest('tr').find(":input:not(:first)").attr('disabled', this.checked);
        var delete_id = $(this).data('delete_id');
        if($(this).prop("checked") == true){
          $(this).val(delete_id);
        }else{
           $(this).val('');
        }
    });
 }
</script>
@endpush