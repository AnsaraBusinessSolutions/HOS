@extends('inbound.layouts.app')
@section('content')
<div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Order Details</b> </h5>
          </div>
           
          </div>
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
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
              @foreach($order_detail as $key=>$val)
                  <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$val->nupco_material_generic_code}}</td>
                      <td>{{$val->customer_bp}}</td>
                      <td>{{$val->material_description}}</td>
                      <td>{{$val->buom}}</td>
                      <td><input type="hidden" name="order_id[]" value="{{$val->id}}"><input type="text" name="qty[]" value="{{$val->qty}}"></td>
                      <td>available</td>
                  </tr>
                 @endforeach
                 
              </tbody>
            </table>
          </div>
          <div class="col-12 text-center">
            <button class="btn btn-success">Accept</button>
            <button class="btn btn-danger" data-toggle="modal" data-target="#rejected_reason">Reject</button>
          </div>
        </div>

        @stop
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
        } );
    } );
  </script>
  @endpush
<!-- Modal -->
<div class="modal fade show" id="rejected_reason" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header py-0" style="border-bottom: none;">
              <button type="button" class="close" data-dismiss="modal" style="outline: none; margin: 0rem -1rem -1rem auto;">×</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body text-center">
              <form name="" method="post" action="">
              <h5 class="mb-3 text-danger"><b>Reason For Rejection</b></h5>
              <table id="example" class="table table-borderless mb-0">
                <tbody><tr>
                  <td class="py-0 px-1" width="20%"><b>Reason</b></td>
                  <td class="py-0 px-0" width="1%">:</td>
                  <td class="py-0 px-1">
                    <textarea class="form-control py-0 mb-1" rows="2" name="rejection_reason" style="width: 80%;"></textarea>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <p><b>New Suggested Date</b></p>
                  </td>
                </tr>
                <tr>
                  <td class="py-0 px-1" width="20%"><b>Date</b></td>
                  <td class="py-0 px-0" width="1%">:</td>
                  <td class="py-0 px-1">

                    <div role="wrapper" class="gj-datepicker gj-datepicker-bootstrap gj-unselectable input-group"><input type="text" class="form-control py-0 mb-0 startDa" name="date_change" id="" onkeydown="return false" required="" style="height: calc(1.5em + .55rem + 1px); width: 80%;" data-type="datepicker" data-guid="dd07c8f9-1302-a447-b3ed-8bd07a3c25a3" data-datepicker="true" role="input"><span class="input-group-append" role="right-icon"><button class="btn btn-outline-secondary border-left-0" type="button"><i class="fa fa-calendar" aria-hidden="true"></i></button></span></div>
                    
                  </td>
                </tr>
              </tbody></table>
            </form></div>
            
                <input type="hidden" name="rgr_id" value="000-000-009">
            
            <!-- Modal footer -->
            <div class="modal-footer py-2 mt-3 mb-3" style="border-top: none;">
              <button name="submit" type="submit" value="submit" class="btn btn-info px-5 mx-auto">Submit</button>
            </div>
            
          </div>
        </div>
      </div>
