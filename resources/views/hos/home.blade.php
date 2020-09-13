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
       font-size: 11px;
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
  </style>
@section('content')
<div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          @if(Session::has('message'))
            {!! Session::get('message') !!}
          @endif

          <form id="store_order_form" method="POST">
          <div class="col-12 text-right">
              <button id="store_order_submit">Submit form</button>
          </div>
                <div class="col-12 text-center">
                  <table id="store_order" class="table table-striped table-bordered">
                    <thead>
                        <tr class="bg_color">
                            <th class="text-nowrap px-3">Item #</th>
                            <th class="text-nowrap px-3">NUPCO Material</th>
                            <th class="text-nowrap px-3">Customer Code</th>
                            <th class="text-nowrap px-3">Description</th>
                            <th class="text-nowrap px-3">UOM</th>
                            <th class="text-nowrap px-3">Qty</th>
                            <th class="text-nowrap px-3">Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="7"><button id="addRow" class="btn btn-info w-100">Add New Row</button></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
          </form>
        </div>
      </div>
      @stop
@push('scripts')
<script>
    var t;
$(function() {
    table = $('#store_order').DataTable({
      "searching": false,
      "paging": false,
    });
    var counter = 1;
    $('#addRow').on( 'click', function (e) {
      e.preventDefault();
      table.row.add( [
            '<td><input type="hidden" data-row_id ="'+counter+'" data-name="material_master_id" id="material_master_id_'+counter+'" name="material_master_id[]">'+counter+'</td>',
            '<td ><input type="text" class="material_data" data-row_id ="'+counter+'" data-name="nupco_material_generic_code" id="nupco_material_generic_code_'+counter+'" name="nupco_material_generic_code[]"><div id="nupco_material_generic_code_list_'+counter+'"></div></td>',
            '<td><input type="text"  class="material_data"  data-row_id ="'+counter+'" data-name="customer_bp" id="customer_bp_'+counter+'" name="customer_bp[]"><div id="customer_bp_list_'+counter+'"></div></td>',
            '<td><input type="text"  class="material_data" data-row_id ="'+counter+'" data-name="material_description" id="material_description_'+counter+'" name="material_description[]"><div id="material_description_list_'+counter+'"></div></td>',
            '<td><input type="text" data-row_id ="'+counter+'" data-name="buom" id="buom_'+counter+'" name="buom[]" readonly></td>',
            '<td><input type="text" data-row_id ="'+counter+'" data-name="qty" id="qty_'+counter+'" name="qty[]" onkeypress="return onlyNumberKey(event)"></td>',
            '<td><input type="text" data-row_id ="'+counter+'" data-name="available" id="available_'+counter+'" name="available[]" readonly></td>',
           ] ).draw( false );
        counter++;
        autoSearchMaterial();
    } );
    // Automatically add a first row of data
    $('#addRow').click(); 

    $('#store_order_form').on('submit', function(event){
            event.preventDefault();
            var formData = new FormData(this);
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
                   console.log(data);
                  location.reload(true);
                 }
            })
       });

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
        var _token = $('input[name="_token"]').val();
        $.ajax({
          url:"{{ route('hos.search.data') }}",
          method:"POST",
          data:{input_data:input_data,input_name:input_name,_token:_token},
          success:function(data){
            $('#'+input_name+'_list_'+row_id).html('');
            if(data != ''){
              $('#'+input_name+'_list_'+row_id).fadeIn();
              $('#'+input_name+'_list_'+row_id).html(data);
              $("li").bind("click",function(){
                  $('#'+input_name+'_list_'+row_id).fadeOut();  
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
                  $('#material_master_id_'+row_id).val(response.data[0].id);
                  $('#nupco_material_generic_code_'+row_id).val(response.data[0].nupco_material_generic_code);
                  $('#customer_bp_'+row_id).val(response.data[0].customer_bp);
                  $('#material_description_'+row_id).val(response.data[0].material_description);
                  $('#buom_'+row_id).val(response.data[0].buom);
                  $('#available_'+row_id).val('available');
              }else{
                  $('#'+input_name+'_'+row_id).val('');
              }
          }
    });
 }

</script>
@endpush
