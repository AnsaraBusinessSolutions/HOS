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
.search_data thead tr:nth-child(1) th{
  position: sticky;
  top: 0px;
  z-index: 999;
  padding: 4px 4px;
  /* outline: 1px solid #4682b5; */
  font-size: 13px;
  box-shadow: 0px 0px 1px 1px #ffffff, 0px 0px 1px 1px #ffffff; 
  background: #4682b5;
}
.fixed_height{
  height: 55vh;
  overflow-y: auto;
}
#preloader {
  background: transparent!important;
}
</style>
@section('content')
<div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          @if(Session::has('message'))
            {!! Session::get('message') !!}
          @endif

          <form id="store_order_form" class="mb-0 w-100" method="POST">
            <table class="table table-borderless head_table text-center mb-0 mb-1">
              <thead>
                 <tr>
                  <th width="1%" class="p-0">&ensp;</th>
                   <th width="13%" class="p-0">
                    <label for="supplying-plant">Supplying Plant :<br>
                     @if(count($delivery_wh) > 0)
                     <label class="mb-0 py-2" ><b>{{$delivery_wh[0]->delivery_wh_name}}</b></label>
                    <input type="hidden" name="supplying_plant_code" value="{{$delivery_wh[0]->delivery_warehouse}}">
                    <input type="hidden" name="supplying_plant" value="{{$delivery_wh[0]->delivery_wh_name}}">
                    <input type="hidden" name="sloc_id" value="{{$delivery_wh[0]->sloc_id}}">
                    <input type="hidden" name="hss_master_no" value="{{$delivery_wh[0]->hss_master_no}}">
                    <input type="hidden" name="hospital_name" value="{{$delivery_wh[0]->name1}}">
                    @else
                    <input type="hidden" name="supplying_plant_code" value="">
                    <input type="hidden" name="supplying_plant" value="">
                    <input type="hidden" name="sloc_id" value="">
                    <input type="hidden" name="hss_master_no" value="">
                    <input type="hidden" name="hospital_name" value="">
                    @endif
                    </label></th>
                    
                   <th width="20%" class="p-0 border">
                    
                    </th>
                    <th width="1%" class="p-0">&ensp;</th>
                    <th width="10%" class="p-0">
                    <label for="delivery-date">Order Type:</label></th>
                   <th width="20%" class="p-0">
                    <select class="form-control" name="order_type" id="order_type">
                      <option value="normal">Normal</option>
                      <option value="emergency">Emergency</option>
                    </select>
                     <th width="2%" class="p-0">&ensp;</th>
                   <th width="10%" class="p-0">
                    <label for="delivery-date">Delivery Date:</label></th>
                   <th width="16%" class="p-0">
                    <input type="" class="datepicker form-control h_sm" name="delivery_date" id="delivery_date" required autocomplete="off"></th>
                  <th width="6%" class="p-0">
                    <input type="hidden" class="form-control h_1rem" data-row_id="ht1" data-name="header_text" id="text_ht1" name="header_text">
                    <i class="fas fa-file-alt text_icon" aria-hidden="true" data-row_id="ht1"></i>
                  </th>
                 </tr>
              </thead>
            </table>
              <div class="col-12 text-center fixed_height">
                  <table id="store_order" class="table table-striped table-bordered text-center search_data">
                    <thead>
                        <tr class="bg_color ">
                            <th width="3%" class="text-nowrap px-3"><input type="checkbox" id="check_all"></th>
                            <th class="text-nowrap px-3 w_4">Item #</th>
                            <th class="text-nowrap px-3 w_12">NUPCO Material</th>
                            <th class="text-nowrap px-3 w_7">Customer Code</th>
                            <th class="text-nowrap px-3 w_10">Category</th>
                            <th class="text-nowrap px-3 w_45">Description</th>
                            <th class="text-nowrap px-3 w_4">UOM</th>
                            <th class="text-nowrap px-3 w_8">Qty</th>
                            <th class="text-nowrap px-3 w_8">Available</th>
                            <th class="text-nowrap px-3 w_2">Item Text</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                  </table>
                  <div id="preloader" style="display:none"></div>
                </div>
                <div class="col-12 text-center">
                  <!-- <button id="store_order_submit" class="btn btn-info my-2">Create Order</button> -->
                  <button id="store_order_submit" class="btn btn-info btn-sm my-0 mt-2" type="button">Create Order</button>
                </div>
          </form>
        </div>
      </div>
  @push('modal_content')
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
    var t;
    var counter = 1;
    
$(function() {
    //Hide drop down when user search the data and then click outside the dropdown
    $('#store_order tbody').empty();
    $(document).mouseup(function(e) {
        var container = $(".search_data ul");
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.hide();
        }
    });
 
    //Date disabled according order type
    //Normal/Return : Display tomorrow and future date,Disable friday and saturday //Emergency : Display today and future date,Disable friday and saturday
    $('#order_type').on('change', function (e) {
        var selected_order_type = $(this).val();
        var date_disable_min; 
        if(selected_order_type == 'normal' || selected_order_type == 'return'){
          date_disable_min = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1);
        }else{  
          date_disable_min = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        }
        $("#delivery_date").datepicker("destroy");
        $('#delivery_date').datepicker({
          uiLibrary: 'bootstrap4',
          minDate: date_disable_min,
          disableDaysOfWeek: [5, 6],
        });
    });

    //when add qty in qty-input at that time compare qty and available qty and according that change text color
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
   
   //Click on add row and add new row in table
    $('#addRow').on('click', function (e) { 
      //e.preventDefault();
      var tr = $(
            '<tr><td width="3%" class="p-0"><input type="checkbox" data-row_id ="'+counter+'"></td>'
            +'<td class="p-0"><input type="hidden" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_trade_code" id="nupco_trade_code_'+counter+'" name="nupco_trade_code[]">'+counter+'</td>'
            +'<td class="p-0"><input type="text" class="material_data form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_generic_code" id="nupco_generic_code_'+counter+'" name="nupco_generic_code[]" maxlength="20" autocomplete="off"><div id="nupco_generic_code_list_'+counter+'" class="position-relative"></div></td>'
            +'<td class="p-0"><input type="text"  class="material_data form-control h_1rem"  data-row_id ="'+counter+'" data-name="customer_code" id="customer_code_'+counter+'" name="customer_code[]" maxlength="20" autocomplete="off"><div id="customer_code_list_'+counter+'" class="position-relative"></div></td>'
            +'<td class="p-0"><input type="text"  class="form-control h_1rem"  data-row_id ="'+counter+'" data-name="customer_code_cat" id="customer_code_cat_'+counter+'" name="customer_code_cat[]" readonly><div id="customer_code_cat_list_'+counter+'"></div></td>'
            +'<td class="p-0"><input type="text"  class="material_data form-control h_1rem" data-row_id ="'+counter+'" data-name="nupco_desc" id="nupco_desc_'+counter+'" name="nupco_desc[]" autocomplete="off"><div id="nupco_desc_list_'+counter+'" class="position-relative"></div></td>'
            +'<td class="p-0"><input type="text" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="uom" id="uom_'+counter+'" name="uom[]" readonly></td>'
            +'<td class="p-0"><input type="text" class="form-control h_1rem qty_input" data-row_id ="'+counter+'" data-name="qty" id="qty_'+counter+'" name="qty[]" onkeypress="return onlyNumberKey(event)" maxlength="15" autocomplete="off" readonly></td>'
            +'<td class="p-0"><input type="text" class="form-control h_1rem text-success" data-row_id ="'+counter+'" data-name="available" id="available_'+counter+'" name="available[]" readonly></td>'
            +'<td class="p-0"><input type="hidden" class="form-control h_1rem" data-row_id ="'+counter+'" data-name="item_text" id="text_'+counter+'" name="item_text[]"><i class="fas fa-file-alt text_icon" aria-hidden="true" data-row_id ="'+counter+'"></i></td></tr>');
        
      $('#store_order tbody').append(tr);
      counter++;
      // autoSearchMaterial();
      
    });

    autoSearchMaterial();
    // Automatically add a first row of data
    for (var add_i = 0; add_i < 10; add_i++) {
       $('#addRow').click(); 
    }

    //Click on create order button and check all validatioon and then open confirmation popup 
    $('#store_order_submit').click(function(e){
      e.preventDefault();
      var check_qty = 1;
      $("#store_order .qty_input").each(function() {
          var qty_val_submit = $(this).val();
          var row_no_submit = $(this).data('row_id');
          var available_val_submit = $('#available_'+row_no_submit).val();
          if(parseInt(qty_val_submit) > parseInt(available_val_submit)){
            check_qty = 0;
          }
      });
      if(check_qty == 0){
        alert('Please enter valid qty');
      }
      else if($('#store_order tbody').children().length == 0) {
        alert('Please add atleast one order');
      }else if($('#delivery_date').val() == ''){
        alert('Please select Delivery Date');
      }else{
        $('#conformation_modal').modal('show');
      }
    
    });

    //Click on yes button and add order in database
  $('#save_order').click(function (e) { 
    // event.preventDefault();
      var formData = new FormData(document.getElementById("store_order_form"));
      $.ajax({
          url:"{{ route('hos.add.order') }}",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          method:"POST",
          data:formData,
          dataType:'JSON',
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function() { 
            $("#save_order").prop('disabled', true); 
          },
          success:function(data)
          {
            $("#save_order").prop('disabled', false);
            if(data == 0){
              location.reload(true);
            }else{
                window.location.href='{{route('hos.home')}}';
            }
            
          }
      });
    }); 

    //Click on delete button and selected row deleted
    $('#delete_row').click(function(e){
      e.preventDefault();
        $("#store_order input[type=checkbox]:checked").each(function(){
            $(this).closest("tbody tr").remove();
            //return false;  
        });
    }); 

    //All checkbox check and uncheck
    $("#check_all").click(function () {
      $('input:checkbox').not(this).prop('checked', this.checked);
    });

    //Item and header text display in popup
    $(".text_icon").click(function(){
        var icon_row_id = $(this).data('row_id');
        $('#text_input').val($('#text_'+icon_row_id).val());
        $('#text_save').data('row_id',icon_row_id);
        $('#text_modal').modal('show');
    });

    //Save item and header text
    $("#text_save").click(function(){
      var row_id_save = $(this).data('row_id');
      $('#text_'+row_id_save).val($('#text_input').val());
      $('#text_modal').modal('hide');
    });
    
});

//Only type number in input box
function onlyNumberKey(evt) { 
  var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){ 
      return false; 
  }
  return true; 
} 

//Type material code,customer code or description and accroding that getting available data from database.(Search data)
function autoSearchMaterial(){
  //$('input.material_data').keyup(function(){ 
    $(document).on('keyup','input.material_data',function (e) {
    var row_id = $(this).data('row_id');
    var input_data = $(this).val();
    var input_name = $(this).data('name');

    if(e.which == 13){
          setMaterialData(this,input_name,row_id,input_data);
          $('#'+input_name+'_list_'+row_id).fadeOut();  
          return false;
        }else{
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
            $("#store_order li").bind("click",function(){
                $('#'+input_name+'_list_'+row_id).fadeOut();  
                var li_data = $(this).text();
                setMaterialData(this,input_name,row_id,li_data);
            });
          }
        }
      });
    }
        }
  });
}

//Set all data in table row when click on perticular one item from the search dropdown
function setMaterialData(element,input_name,row_id,input_data){
    //var input_data = $(element).text();
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
            $('#qty_'+row_id).attr("readonly", false); 
        }else{
            alert('This material is not available');
            $('#'+input_name+'_'+row_id).val('');
        }
      }
    });
 }
 importExcel();
//Import 
 function importExcel(){
    $("#import_excel").on('click', function(e){
      e.preventDefault();
      $("#upload_excel:hidden").trigger('click');
    });

    $('#upload_excel').on('change', function() {
    var file_data = $('#upload_excel').prop('files')[0];   
    //console.log(file_data);
    var form_data = new FormData();                  
    form_data.append('import_file', file_data);
    //alert(form_data);                             
      $.ajax({
          url:"{{ route('hos.import.excel') }}",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          dataType:'JSON',
          contentType: false,
          processData: false,
          data: form_data,                         
          type: 'POST',
          success: function(response){
              alert(response); // display response from the PHP script, if any
          }
      });
    });
 }

</script>
@endpush
