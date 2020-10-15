@extends('hos.layouts.app')
<style type="text/css">

  </style>
@section('content')
<div class="container-fluid main_content bg-white p-2">
    <div class="row mx-0">
        <div class="col-10 text-center">
            <h5 style="color: steelblue"><b>Stock Report</b></h5>
        </div>
        <div class="col-2 py-1 text-center">
        </div>
        
        <table class="table table-borderless head_table text-center mb-0 mb-3">
            <thead>
            <form id="search_stock_form">
                <tr>
                    <th width="1%" class="p-0"><label></label></th>
                    <th width="14%" class="p-0"> <input type="text" class="form-control form-control-sm" name="plant" placeholder="Supplying Warehouse"></th>
                    <th width="2%" class="p-0">&ensp;</th>
                    <th width="20%" class="p-0"><input type="text" class="form-control form-control-sm" name="nupco_generic_code" placeholder="NUPCO Material"></th>
                    <th width="2%" class="p-0">&ensp;</th>
                    <th width="14%" class="p-0"><input type="text" class="form-control form-control-sm" name="nupco_desc" placeholder="Description"></th>
                    <th width="10%" class="p-0"><button type="button" class="btn btn-success btn-sm" id="search_stock_btn">Search</button></th>
                    <th width="6%" class="p-0">&ensp;</th>
                </tr>
            </thead>
            </form>
        </table>
        <div class="col-12 text-center">
            <table id="stock_report_table" class="table table-striped table-bordered example">
                <thead>
                    <tr class="bg_color">
                        <th width="3%" class="text-nowrap px-3">Plant</th>
                        <th width="10%" class="text-nowrap px-3">Storage Location</th>
                        <th width="10%" class="text-nowrap px-3">NUPCO Material</th>
                        <th width="10%" class="text-nowrap px-3">NUPCO Trade Code</th>
                        <th width="5%" class="text-nowrap px-3">Customer Code</th>
                        <th width="25%" class="text-nowrap px-3">Description</th>
                        <th width="6%" class="text-nowrap px-3">Stock Qty</th>
                        <th width="8%" class="text-nowrap px-3">Vendor Batch</th>
                        <th width="5%" class="text-nowrap px-3">UOM</th>
                        <th width="10%" class="text-nowrap px-3">Mfg Date</th>
                        <th width="8%" class="text-nowrap px-3">Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- <div class="col-12 text-center">
            <button class="btn btn-info">Create Order</button>
          </div> -->
    </div>
</div><br>
@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#stock_report_table').DataTable( {
            "scrollY":        "47vh",
            "scrollCollapse": true,
            "paging":         false,
            "searching": false,
            "ordering": false  
        });
    });

    $('#search_stock_btn').click(function(e){
        e.preventDefault();
        var formData = new FormData(document.getElementById("search_stock_form"));
        $.ajax({
            url:"{{ route('hos.search.stock') }}",
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
              //  $("#search_stock_btn").prop('disabled', true); 
            },
            success:function(response)
            {
                $('#stock_report_table').DataTable().destroy();
                $("#stock_report_table tbody").html(response.data);
                $('#stock_report_table').DataTable( {
                    "scrollY":        "47vh",
                    "scrollCollapse": true,
                    "paging":         false,
                    "searching": false,
                    "ordering": false  
                });
            }
        });
    });
  </script>
@endpush