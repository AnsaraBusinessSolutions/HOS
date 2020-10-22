@extends('hos.layouts.app')
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
            <h5 style="color: steelblue"> <b>Order {{$department_order_id}} Details</b> </h5>
          </div>
          </div>
          <div class="row mx-0 border">
            <div class="col-md-5 col-sm-6 col-12">
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Order ID</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">{{$department_order_id}}
                </label>
              </div>
              <div class="form-row py-1">
                <label class="col-md-4 col-sm-4 col-4"><b>Supply WH</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">{{$order_detail[0]->supplying_plant}}
                </label>
              </div>
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Order Date</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">{{date('Y-m-d',strtotime($order_detail[0]->created_date))}}
                </label>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-12">
             
            </div>
            <div class="col-md-5 col-sm-6 col-12 order-3 order-sm-3">
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Department</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-3 col-sm-7 col-7" >{{$order_detail[0]->department_name}}
                </label>
                               </div>
            
              <div class="form-row">
                <label class="col-md-4 col-sm-4 col-4"><b>Status</b></label>
                <label class="col-md-1 col-sm-1 col-1 px-0">:</label>
                <label class="col-md-7 col-sm-7 col-7">
                        @if($order_detail[0]->status == 0)
                          <span class="text-warning"><b>NEW</b></span>
                        @elseif($order_detail[0]->status == 1)
                          <span class="text-success"><b>RECEIVED</b></span>
                        @else
                          <span class="text-primary" style="font-size: 14px"><b></b></span>
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
          <form action="{{route('hos.add.stock.consumption')}}" method="POST" onsubmit="return checkQtyValidation();">
          @csrf
          <div class="col-12 text-center">
            <table id="department_order_detail" class="table table-striped table-bordered example">
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
                      <th class="text-nowrap px-3" >Received Qty</th>
                      <th class="text-nowrap px-3" >Batch</th>
                      <th class="text-nowrap px-3" >MFG Date</th>
                      <th class="text-nowrap px-3" >Expiry Date</th>
                      <th class="text-nowrap px-3">Item Text</th> 
                  </tr>
              </thead>
              <tbody>
              @foreach($order_detail as $key=>$val)
              @php
              $stock_batch_data = DB::table('stock as s')->where('s.plant',$val->hss_master_no)
                                        ->where('s.storage_location',$val->hss_master_no)
                                        ->where('s.nupco_generic_code',$val->nupco_generic_code)
                                        ->select('s.vendor_batch','s.mfg_date','s.expiry_date')
                                        ->get();
              @endphp
                  <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_generic_code}}</td>
                      <td>{{$val->nupco_trade_code}}</td>
                      <td>{{$val->customer_trade_code}}</td>
                      <td>{{$val->category}}</td>
                      <td>{{$val->material_desc}}</td>
                      <td>{{$val->uom}}</td>
                      <td>{{$val->qty_ordered}}</td>
                      @if($val->status == '1')
                        <td>{{$val->qty}}</td>
                        <td>{{$val->batch}}</td>
                        <td>{{$val->mfg_date}}</td>
                        <td>{{$val->expiry_date}}</td>
                      @else
                        <td><input type="hidden" name="department_order_main_id[]" id="department_order_main_id_{{$key}}" value="{{$val->id}}"><input class="form-control received_qty" data-qty_ordered="{{$val->qty_ordered}}" id="rec_qty_{{$key}}" type="text" name="received_qty[]" required onkeypress="return onlyNumberKey(event)" maxlength="15" autocomplete="off"></td>
                        <td>
                        <select class="form-control batch_select" name="batch[]" id="batch" data-row_id="{{$key}}" required>
                        <option value="" data-mfg_date="" data-expiry_date=""></option>
                        @foreach($stock_batch_data as $skey=>$sval)
                        <option value="{{$sval->vendor_batch}}" data-mfg_date="{{$sval->mfg_date}}" data-expiry_date="{{$sval->expiry_date}}">{{$sval->vendor_batch}}</option>
                        @endforeach
                        </select>
                        <input type="hidden" name="mfg_date[]" id="mfg_date_{{$key}}" value="">
                        <input type="hidden" name="expiry_date[]" id="expiry_date_{{$key}}" value="">
                        </td>
                        <td id="mfg_date_tr_{{$key}}"></td>
                        <td id="expiry_date_tr_{{$key}}"></td>
                      @endif
                      <td><input type="hidden" class="form-control h_1rem" data-row_id ="{{$key}}" data-name="item_text" id="text_{{$key}}" value="{{$val->item_text}}"><i class="fas fa-file-alt text_icon" aria-hidden="true" data-row_id ="{{$key}}"></i></td>
                  </tr>
                 @endforeach
                 
              </tbody>
            </table>
          </div>
          <div class="col-12 text-center">
          @if($order_detail[0]->status == 0)
          <button class="btn btn-success" type="submit">Update</button>
          @endif
          </div>
          </form>
    </div>
@push('modal_content')

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
    //Datatable for display order items
    $('.example').DataTable( {
        "ordering": false,
        "scrollY":        "55vh",
        "scrollCollapse": true,
        "paging":         false,
        "searching": false,
        "lengthMenu": [ [15, 30, 50, 100, 250, 500, 1000, 1500], [15, 20, 50, 100, 250, 500, 1000, 1500] ],
        "iDisplayLength": 1000,
    });

    //Display item and header text in popup
    $(".text_icon").click(function(){
        var icon_row_id = $(this).data('row_id');
        $('#text_input').val($('#text_'+icon_row_id).val());
        $('#text_save').data('row_id',icon_row_id);
        $('#text_modal').modal('show');
    });

    $('.batch_select').on('change', function (e) {
        var row_id = $(this).data('row_id');
        var mfg_date = $(this).find(':selected').data('mfg_date');
        var expiry_date = $(this).find(':selected').data('expiry_date');
        
        $('#mfg_date_'+row_id).val(mfg_date);
        $('#mfg_date_tr_'+row_id).html(mfg_date);
        $('#expiry_date_'+row_id).val(expiry_date);
        $('#expiry_date_tr_'+row_id).html(expiry_date);
    });

  });

  //Check all received qty validation
  function checkQtyValidation(){
  var check_qty = 1;
      $("#department_order_detail .received_qty").each(function() {
          var received_qty = $(this).val();
          var qty_ordered = $(this).data('qty_ordered');
          if($(this).prop('disabled') == false){
            if(parseInt(qty_ordered) < parseInt(received_qty)){
              check_qty = 0;
            }  
          }
      });
      
      if(check_qty == 0){
        alert('Please enter valid qty');
        return false;
      }else{
        return true;
      }
 }

 //Only type number in input box
function onlyNumberKey(evt) { 
  var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){ 
      return false; 
  }
  return true; 
} 
  </script>
  @endpush

