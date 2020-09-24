@extends('hos.layouts.app')
<style type="text/css">
    #store_order_form .bg_color{
        background-color: steelblue!important;
        color: #fff!important;
    }
    #store_order_form_wrapper .table .bg_color th {
        background-color: steelblue!important;
        color: #fff!important;
    }
    #store_order_form_wrapper .table tr:nth-child(1) th {
        background-color: white!important;
    }
    #store_order_form_wrapper .table{
       font-size: 13px!important;
    }
    table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before { 
        right: 1px;
    }
    .bg-blue{
      background: #3276b1!important;
    }
    #store_order_form_wrapper .table tr:nth-child(1) th {
    background-color: #3276b1!important;
}
.h_1rem{
  height: calc(1.5rem + 2px)!important;
  font-size: .80rem!important;
}
#store_order_form .head_table .gj-datepicker-bootstrap [role=right-icon] button .gj-icon {
    position: absolute;
    font-size: 21px;
    top: 6px;
    left: 9px;
}
.h_sm{
  height: calc(1.8125rem + 2px)!important;
}
.search_data tbody tr td .dropdown-menu{
  position: absolute!important;
}
</style>
@section('content')
<div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          @if(Session::has('message'))
            {!! Session::get('message') !!}
          @endif

          <form id="store_order_form" class="mb-0" method="POST">
            <table class="table table-borderless head_table text-center mb-0">
              <thead>
                 <tr>
                  <th width="6%" class="p-0">&ensp;</th>
                   <th width="10%" class="p-0">
                    <label for="supplying-plant">Supplying Plant:</label></th>
                    @if(count($delivery_wh) > 0)
                   <th width="20%" class="p-0">
                    <label> <b>{{$delivery_wh[0]->delivery_wh_name}}</b></label>
                    <input type="hidden" name="supplying_plant" value="{{$delivery_wh[0]->delivery_wh_name}}">
                    <input type="hidden" name="hss_master_no" value="{{$delivery_wh[0]->hss_master_no}}">
                    <input type="hidden" name="hospital_name" value="{{$delivery_wh[0]->name1}}">
                    @else
                    <input type="hidden" name="supplying_plant" value="">
                    <input type="hidden" name="hss_master_no" value="">
                    <input type="hidden" name="hospital_name" value="">
                    @endif
                    </th><th width="6%" class="p-0">&ensp;</th>
                   <th width="10%" class="p-0">
                    <label for="delivery-date">Delivery Date:</label></th>
                   <th width="20%" class="p-0">
                    <input type="" class="datepicker form-control h_sm" name="delivery_date" id="delivery_date" required></th>
                     <th width="6%" class="p-0">&ensp;</th>
                 </tr>
              </thead>
            </table>
            <table class="table table-borderless head_table text-center mb-0">
              <thead>
                 <tr>
                  <th width="6%" class="p-0">&ensp;</th>
                   <th width="14%" class="p-0">
                    <label for="supplying-plant">Supplying Plant: </label>
                    </th>
                    @if(count($delivery_wh) > 0)
                    <th><label class="border"><b>{{$delivery_wh[0]->delivery_wh_name}}</b></label></th>
                    <input type="hidden" name="supplying_plant" value="{{$delivery_wh[0]->delivery_wh_name}}">
                    <input type="hidden" name="hss_master_no" value="{{$delivery_wh[0]->hss_master_no}}">
                    <input type="hidden" name="hospital_name" value="{{$delivery_wh[0]->name1}}">
                    @else
                    <input type="hidden" name="supplying_plant" value="">
                    <input type="hidden" name="hss_master_no" value="">
                    <input type="hidden" name="hospital_name" value="">
                    @endif
                    <th width="6%" class="p-0">&ensp;</th>
                   <th width="10%" class="p-0">
                    <label for="delivery-date">Delivery Date:</label></th>
                   <th width="20%" class="p-0">
                    <input type="" class="datepicker form-control h_sm" name="delivery_date" id="delivery_date" require></th>
                     <th width="6%" class="p-0">&ensp;</th>
                 </tr>
              </thead>
            </table>
              <div class="col-12 text-center">
                  <table id="store_order" class="table table-striped table-bordered text-center search_data">
                    <thead>
                        <tr class="bg_color ">
                            <!-- <th width="3%" class="px-3">All <input type="checkbox" id="check_all"></th> -->
                            <th width="3%" class="text-nowrap px-3">Item #</th>
                            <th width="15%" class="text-nowrap px-3">NUPCO Material</th>
                            <th width="5%" class="text-nowrap px-3">Customer Code</th>
                            <th width="5%" class="text-nowrap px-3">Category</th>
                            <th width="23%" class="text-nowrap px-3">Description</th>
                            <th width="3%" class="text-nowrap px-3">UOM</th>
                            <th width="5%" class="text-nowrap px-3">Qty</th>
                            <th width="8%" class="text-nowrap px-3">Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                  </table>
                </div>
                <div class="col-12 text-center">
                  <!-- <button id="store_order_submit" class="btn btn-info my-2">Create Order</button> -->
                  <button id="store_order_submit" class="btn btn-info btn-sm my-0 mt-2">Create Order</button>
                </div>
          </form>
        </div>
      </div>
      @stop
  <div class="modal" id="conformation_modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          Create Order?
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success" id="save_order">Yes</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>
  
@push('scripts')
<script>
    var t;
    var counter = 1;
$(function() {
    table = $('#store_order').DataTable({
      "searching": false,
      "paging": false,
      "scrollY": "54vh",
      "ordering": false
    });
   
    $('#addRow').on('click', function (e) { 
      //e.preventDefault();
      table.row.add( [
            // '<td width="3%" class="text-nowrap px-3"><input type="checkbox" data-row_id ="'+counter+'"></td>',
            '<td class="p-0"><input type="hidden" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_trade_code" id="nupco_trade_code_'+counter+'" name="nupco_trade_code[]">'+counter+'</td>',
            '<td class="p-0"><input type="text" class="material_data form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_generic_code" id="nupco_generic_code_'+counter+'" name="nupco_generic_code[]" maxlength="20" autocomplete="off"><div id="nupco_generic_code_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text"  class="material_data form-control h_1rem"  data-row_id ="'+counter+'" data-name="customer_code" id="customer_code_'+counter+'" name="customer_code[]" maxlength="20" autocomplete="off"><div id="customer_code_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text"  class="form-control h_1rem"  data-row_id ="'+counter+'" data-name="customer_code_cat" id="customer_code_cat_'+counter+'" name="customer_code_cat[]" readonly><div id="customer_code_cat_list_'+counter+'"></div></td>',
            '<td class="p-0"><input type="text"  class="material_data form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_desc" id="nupco_desc_'+counter+'" name="nupco_desc[]" autocomplete="off"><div id="nupco_desc_list_'+counter+'" class="position-relative"></div></td>',
            '<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="uom" id="uom_'+counter+'" name="uom[]" readonly></td>',
            '<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="qty" id="qty_'+counter+'" name="qty[]" onkeypress="return onlyNumberKey(event)" maxlength="15" autocomplete="off"></td>',
            '<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="available" id="available_'+counter+'" name="available[]" readonly></td>',
           ]).draw( false );
        counter++;
        autoSearchMaterial();
    } );
    // Automatically add a first row of data
    for (var add_i = 0; add_i < 10; add_i++) {
       $('#addRow').click(); 
    }

  $('#store_order_submit').click(function(e){
    e.preventDefault();
    if($('#delivery_date').val() == ''){
      alert('Please select Delivery Date');
    }else{
      $('#conformation_modal').modal('show');
    }
    
  });

  $('#save_order').click(function (e) { 
    //         event.preventDefault();
            var formData = new FormData(document.getElementById("store_order_form"));
            formData.append( '_token',"{{ csrf_token() }}");
            $.ajax({
                 url:"{{ route('hos.add.order') }}",
                 method:"POST",
                 data:formData,
                 dataType:'JSON',
                 contentType: false,
                 cache: false,
                 processData: false,
                 success:function(data)
                 {
                    if(data == 0){
                      location.reload(true);
                    }else{
                        window.location.href='{{route('hos.home')}}';
                    }
                    
                 }
            })
    }); 

    // $('#delete_row').click(function(e){
    //   e.preventDefault();
    //     $("#store_order input[type=checkbox]:checked").each(function(){
    //         $(this).closest("tr").remove();
    //         //return false;  
    //     });
    // }); 

    // $("#check_all").click(function () {
    //   $('input:checkbox').not(this).prop('checked', this.checked);
    // });
});
function onlyNumberKey(evt) { 
  var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){ 
      return false; 
  }
  return true; 
} 

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
          method:"POST",
          data:{input_data:input_data,input_name:input_name,_token:token},
          success:function(data){
            $('#'+input_name+'_list_'+row_id).html('');
            if(data != ''){
              $('#'+input_name+'_list_'+row_id).fadeIn();
              $('#'+input_name+'_list_'+row_id).html(data);
              $("#store_order li").bind("click",function(){
                  $('#'+input_name+'_list_'+row_id).fadeOut();  
                  var li_data = $(this).text();
                  setMaterialData(this,input_name,row_id);
              });
            }
          }
        }),1000;
      }
    });
}

function setMaterialData(element,input_name,row_id){
    var input_data = $(element).text();
   // alert('dddd');
    $.ajax({
          url: '{!! route('hos.material.data') !!}',
          method: 'post',
          dataType: "json",
          data: {
              "_token": "{{ csrf_token() }}",
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
              }else{
                  $('#'+input_name+'_'+row_id).val('');
              }
          }
    });
 }

</script>
@endpush
