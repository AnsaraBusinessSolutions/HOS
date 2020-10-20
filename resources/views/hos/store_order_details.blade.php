<style>
 .dis_row_input td input{
    pointer-events:none;
    color:#AAA;
    background:#F5F5F5;
 }
 #preloader {
  background: transparent!important;
}
</style>
@extends('hos.layouts.app')
@section('content')
<div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          @if(Session::has('message'))
            {!! Session::get('message') !!}
          @endif
          </div>
          <form action="{{route('hos.order.update')}}" method="POST" onsubmit="return checkQtyValidation();">
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
                        @if($order_detail[0]->status == '0')
                          <span class="text-warning"><b>NEW</b></span>
                        @elseif($order_detail[0]->status == '1')
                          <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($order_detail[0]->status == '2')
                          <span class="text-success"><b>APPROVED</b></span>
                        @elseif($order_detail[0]->status == '3')
                          <span class="text-primary"><b>DISPATCHED</b></span>
                        @elseif($order_detail[0]->status == '4' || $order_detail[0]->status == '6')
                          <span class="text-info"><b>DELIVERED</b></span>
                        @elseif(strpos($order_detail[0]->status, '6') !== false || strpos($order_detail[0]->status, '8') !== false)
                          <span class="text-primary"><b>PARTIALLY DELIVERED</b></span>
                        @elseif(strpos($order_detail[0]->status, '5') !== false || strpos($order_detail[0]->status, '7') !== false)
                          <span class="text-primary"><b>PARTIALLY DISPATCHED</b></span>
                        @else
                          <span class="text-primary" style="font-size: 14px"><b></b></span>
                        @endif
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-6 col-sm-4 col-4"><b>Header Text</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-5 col-sm-7 col-7">
                @php
                if($order_detail[0]->status == 0 || $order_detail[0]->status == 1)
                  $class = '';
                else
                  $class = 'only_show';
                @endphp
                  <input type="hidden" class="form-control h_1rem" data-row_id="ht1" data-name="header_text" id="text_ht1" name="header_text" value="{{$order_detail[0]->header_text}}">
                  <i class="fas fa-file-alt text_icon {{$class}}" aria-hidden="true" data-row_id="ht1"></i>
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
          <input type="hidden" name="supplying_plant_code" value="{{$order_detail[0]->delivery_warehouse}}">
          <input type="hidden" name="supplying_plant" value="{{$order_detail[0]->delivery_wh_name}}">
          <input type="hidden" name="sloc_id" value="{{$order_detail[0]->sloc_id}}">
          <input type="hidden" name="hss_master_no" value="{{$order_detail[0]->hss_master_no}}">
          <input type="hidden" name="hospital_name" value="{{$order_detail[0]->hospital_name}}">
          <input type="hidden" name="order_type" value="{{$order_detail[0]->order_type}}">
          <input type="hidden" name="order_id" value="{{$order_id}}">
          <div class="col-12 text-center">
            <table id="order_detail" class="table table-striped table-bordered example search_data text-center">
              <thead>
                  <tr class="bg_color">
                      <th class="w_3">Delete</th>
                      <th class="text-nowrap px-3 w_3">Item #</th>
                      <th class="text-nowrap px-3 w_12">NUPCO Material</th>
                      <th class="text-nowrap px-3 w_9">NUPCO Trade Code</th>
                      <th class="text-nowrap px-3 w_8">Customer Code</th>
                      <th class="text-nowrap px-3 w_7">Category</th>
                      <th class="text-nowrap px-3 w_36">Description</th>
                      <th class="text-nowrap px-3 w_4">UOM</th>
                      <th class="text-nowrap px-3 w-14">Order Qty</th>
                      <th class="text-nowrap px-3 w_7">Availability</th>
                      <th class="text-nowrap px-3 w_2">Item Text</th> 
                  </tr>
              </thead>
              <tbody>
                  @foreach($order_detail as $key=>$val)
                  @php
                      $availability = 0;
                      $open_qty_data = DB::table('order_details')
                                ->where('supplying_plant_code',$plant)
                                ->where('sloc_id',$storage_location)
                                ->where('nupco_generic_code',$val->nupco_generic_code)
                                ->where('is_deleted',0)
                                ->whereIn('status',[0,2])
                                ->groupBy('nupco_generic_code')
                                ->selectRaw('sum(qty_ordered) as open_qty')
                                ->first();
                      $total_qty = 0;
                      $open_qty = 0;
                      if(!empty($val->unrestricted_stock_qty)){
                          $total_qty = $val->unrestricted_stock_qty;
                      }
                      if(!empty($open_qty_data)){
                          $open_qty =  $open_qty_data->open_qty;
                      }
                      if($total_qty > $open_qty){
                          $availability =  $total_qty - $open_qty;
                      }
                  @endphp
                  @if($val->is_deleted == 1)
                  <tr class="dis_row_input">
                  @else
                  <tr>
                  @endif
                      @if($val->status == 0 || $val->status == 1)
                        @if($val->is_deleted == 1)
                        <td><i class="fa fa-trash" aria-hidden="true"></i></td>
                        @else
                        <td><input type="checkbox" class="delete_row" name="delete_row[]" data-delete_id = "{{$val->id}}" /></td>
                        @endif
                        <td>{{$key+1}}</td>
                        <td><input type="hidden" name="old_nupco_generic_code[]" value="{{$val->nupco_generic_code}}"><input type="text" class="material_data form-control form-control-sm h_1rem" data-row_id="{{$key}}" data-name="nupco_generic_code"  id="nupco_generic_code_{{$key}}" name="nupco_generic_code[]" value="{{$val->nupco_generic_code}}" autocomplete="off" required><div id="nupco_generic_code_list_{{$key}}" class="position-relative"></div></td>
                        <td><input type="text" class="form-control form-control-sm" data-row_id="{{$key}}" data-name="nupco_trade_code_" id="nupco_trade_code_{{$key}}" name="nupco_trade_code[]" value="{{$val->nupco_trade_code}}" readonly></td>
                        <td><input type="text" class="material_data form-control form-control-sm" data-row_id="{{$key}}" data-name="customer_code" id="customer_code_{{$key}}" name="customer_code[]" value="{{$val->customer_trade_code}}" autocomplete="off" required></td>
                        <td><input type="text" class="form-control form-control-sm" data-row_id="{{$key}}" data-name="customer_code_cat" id="customer_code_cat_{{$key}}" name="customer_code_cat[]" value="{{$val->category}}" readonly></td>
                        <td><input type="text" class="material_data form-control form-control-sm" data-row_id="{{$key}}" data-name="nupco_desc" id="nupco_desc_{{$key}}" name="nupco_desc[]" value="{{$val->material_desc}}" autocomplete="off"><div id="nupco_desc_list_{{$key}}" class="position-relative" required></div></td>
                        <td><input type="text" class="form-control form-control-sm" data-row_id="{{$key}}" data-name="uom" id="uom_{{$key}}" name="uom[]" value="{{$val->uom}}" readonly></td>
                        <td><input type="hidden" name="order_primary_id[]" value="{{$val->id}}"><input type="hidden" name="old_qty[]" value="{{$val->qty_ordered}}"><input type="text" class="form-control form-control-sm qty_input_update" data-row_id="{{$key}}" data-name="qty" id="qty_{{$key}}" name="qty[]" data-old_qty_update="{{$val->qty_ordered}}" value="{{$val->qty_ordered}}"></td>
                        <td><input type="text" class="form-control form-control-sm text-success" data-row_id="{{$key}}" data-name="available" id="available_{{$key}}" value="{{$availability}}" name="available[]" readonly></td>
                        <td><input type="hidden" class="form-control h_1rem" data-row_id ="{{$key}}" data-name="item_text" id="text_{{$key}}" name="item_text[]" value="{{$val->item_text}}"><i class="fas fa-file-alt text_icon" aria-hidden="true" data-row_id ="{{$key}}"></i></td>
                      @else
                        @if($val->is_deleted == 1)
                        <td><i class="fa fa-trash" aria-hidden="true"></i></td>
                        @else
                        <td></td>
                        @endif
                        <td>{{$key+1}}</td>
                        <td>{{$val->nupco_generic_code}}</td>
                        <td>{{$val->nupco_trade_code}}</td>
                        <td>{{$val->customer_trade_code}}</td>
                        <td>{{$val->category}}</td>
                        <td>{{$val->material_desc}}</td>
                        <td>{{$val->uom}}</td>
                        <td>{{$val->qty_ordered}}</td>
                        <td>Available</td>
                        <td><input type="hidden" class="form-control h_1rem" data-row_id ="{{$key}}" data-name="item_text" id="text_{{$key}}" name="item_text[]" value="{{$val->item_text}}"><i class="fas fa-file-alt text_icon only_show" aria-hidden="true" data-row_id ="{{$key}}"></i></td>
                      @endif
                      
                  </tr>
                 @endforeach
              </tbody>
            </table>
            <div id="preloader" style="display:none"></div>
          </div>
          @if(count($order_detail) > 0 && ($order_detail[0]->status == 0 || $order_detail[0]->status == 1))
          <div class="col-12 text-center">
              <button id="store_order_submit" class="btn btn-info my-2">Update and Resubmit</button>
          </div>
          @endif
          </form>
    </div>
 @push('modal_content')      
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
        <h5 class="mb-3 text-danger text-center"><b>Add Text</b></h5>
        <table id="" class="table table-borderless reason_table mb-0">
          <tbody><tr>
            <td class="py-0 px-1" width="20%" style="border:0"><b>Text  : </b></td>
            <td class="py-0 px-1">
              <textarea class="form-control py-0 mb-1" rows="2" name="item_text" id="text_input" style="width: 80%;"></textarea>
            </td>
          </tr>
        </tbody>
        </table>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer py-2 my-3 border-0">
        <button type="button" class="btn btn-info px-5 mx-auto" id="text_save">Save</button>
      </div>
    </div>
  </div>
</div>

@endpush
@stop
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

    $(document).mouseup(function(e) {
        var container = $(".search_data ul");
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.hide();
        }
    });

    $(document).on('keyup',".qty_input",function(){
        var qty_val = $(this).val();
        var row_no = $(this).data('row_id');
        var available_val = $('#available_'+row_no).val();
        if(parseInt(qty_val) > parseInt(available_val)){
          $('#available_'+row_no).removeClass('text-success');
          $('#available_'+row_no).addClass('text-danger');
        }else{
          $('#available_'+row_no).removeClass('text-danger');
          $('#available_'+row_no).addClass('text-success');
        }
    });

    autoSearchMaterial();
    deleteRow();
    textAddAndDisplay();

    var counter = {{count($order_detail)}};
    $('#addRow').on('click', function (e) { 
      e.preventDefault();
      table.row.add( [
            // '<td width="3%" class="text-nowrap px-3"><input type="checkbox" data-row_id ="'+counter+'"></td>',
            '<td><input type="checkbox" class="delete_row"/></td>',
            '<td class="p-0">'+(counter+1)+'</td>',
            '<td class="p-0"><input type="text" class="material_data form-control form-control-sm" data-row_id ="'+counter+'" data-name="nupco_generic_code" id="nupco_generic_code_'+counter+'" name="new_nupco_generic_code[]" maxlength="20" autocomplete="off"><div id="nupco_generic_code_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text" class="form-control form-control-sm" data-row_id ="'+counter+'" data-name="nupco_trade_code" id="nupco_trade_code_'+counter+'" name="new_nupco_trade_code[]" maxlength="20" autocomplete="off" readonly></td>',
            '<td class="p-0"><input type="text"  class="material_data form-control form-control-sm"  data-row_id ="'+counter+'" data-name="customer_code" id="customer_code_'+counter+'" name="new_customer_code[]" maxlength="20" autocomplete="off"><div id="customer_code_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text"  class="form-control form-control-sm"  data-row_id ="'+counter+'" data-name="customer_code_cat" id="customer_code_cat_'+counter+'" name="new_customer_code_cat[]" readonly><div id="customer_code_cat_list_'+counter+'"></div></td>',
            '<td class="p-0"><input type="text"  class="material_data form-control form-control-sm" data-row_id ="'+counter+'" data-name="nupco_desc" id="nupco_desc_'+counter+'" name="new_nupco_desc[]" autocomplete="off"><div id="nupco_desc_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text" class="form-control form-control-sm" data-row_id ="'+counter+'" data-name="uom" id="uom_'+counter+'" name="new_uom[]" readonly></td>',
            '<td class="p-0"><input type="text" class="form-control form-control-sm qty_input" data-row_id ="'+counter+'" data-name="qty" id="qty_'+counter+'" name="new_qty[]" onkeypress="return onlyNumberKey(event)" maxlength="15" autocomplete="off" readonly></td>',
            '<td class="p-0"><input type="text" class="form-control form-control-sm" data-row_id ="'+counter+'" data-name="available" id="available_'+counter+'" name="new_available[]" readonly></td>',
            '<td class="p-0"><input type="hidden" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="item_text" id="text_'+counter+'" name="new_item_text[]"><i class="fas fa-file-alt text_icon" aria-hidden="true" data-row_id ="'+counter+'"></i></td>',
           ]).draw( false );
        counter++;
        autoSearchMaterial();
        deleteRow();
        textAddAndDisplay();
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
          beforeSend: function() { 
              $("#preloader").css('display','block'); 
          },
          success: function(response)
          {
              $("#preloader").css('display','none'); 
              if(response.data.length > 0){
                  $('#nupco_trade_code_'+row_id).val(response.data[0].nupco_trade_code);
                  $('#nupco_generic_code_'+row_id).val(response.data[0].nupco_generic_code);
                  $('#customer_code_'+row_id).val(response.data[0].customer_code);
                  $('#customer_code_cat_'+row_id).val(response.data[0].customer_code_cat);
                  $('#nupco_desc_'+row_id).val(response.data[0].nupco_desc);
                  $('#uom_'+row_id).val(response.data[0].uom);
                  $('#available_'+row_id).val(response.availability);
                  $('#qty_'+row_id).val('');
                  $('#qty_'+row_id).attr("readonly", false); 
                  $('#qty_'+row_id).removeClass('qty_input_update');
                  $('#qty_'+row_id).addClass('qty_input');
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

 function checkQtyValidation(){
  var check_qty = 1;
      $("#order_detail .qty_input").each(function() {
          var qty_val_submit = $(this).val();
          var row_no_submit = $(this).data('row_id');
          var available_val_submit = $('#available_'+row_no_submit).val();
          if(parseInt(qty_val_submit) > parseInt(available_val_submit)){
            check_qty = 0;
          }
      });

      $("#order_detail .qty_input_update").each(function() {
          var qty_val_submit = $(this).val();
          var row_no_submit = $(this).data('row_id');
          var old_qty_update = $(this).data('old_qty_update');
          var available_val_submit = $('#available_'+row_no_submit).val();
          if(old_qty_update < qty_val_submit){
            var diff_qty = parseInt(qty_val_submit) - parseInt(old_qty_update);
            if(parseInt(diff_qty) > parseInt(available_val_submit)){
              check_qty = 0;
            }
          }
      });

      if(check_qty == 0){
        alert('Please enter valid qty');
        return false;
      }
      else if($('#order_detail tbody').children().length == 0) {
        alert('Please add atleast one order');
        return false;
      }else{
        return true;
      }
 }

 function textAddAndDisplay(){
  $(".text_icon").click(function(){
        var icon_row_id = $(this).data('row_id');
        $('#text_input').val($('#text_'+icon_row_id).val());
        $('#text_save').data('row_id',icon_row_id);
      
        if($(this).hasClass("only_show")){
          $('#text_save').hide();
          $('#text_input').prop('disabled',true);
        }else{
          $('#text_save').show();
          $('#text_input').prop('disabled',false);
        }
        $('#text_modal').modal('show');
    });

    $("#text_save").click(function(){
      var row_id_save = $(this).data('row_id');
      $('#text_'+row_id_save).val($('#text_input').val());
      $('#text_modal').modal('hide');
    });
 }
</script>
@endpush