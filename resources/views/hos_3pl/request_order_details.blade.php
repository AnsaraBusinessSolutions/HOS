@extends('hos_3pl.layouts.app')
@section('content')
<div class="container-fluid main_content bg-white p-2">
        @if(Session::has('message'))
          {!! Session::get('message') !!}
        @endif
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
                      <td>{{$val->qty}}</td>
                      <td>available</td>
                  </tr>
                 @endforeach
              </tbody>
            </table>
          </div>
              <div class="col-12 text-center">
              @if($order_detail[0]->status == 1)
                <input type="hidden" value="3" name="order_status">
                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#dipatch_modal">Dispatch</button>
              @endif
              </div>
        </div>
    </div>
@stop
<!-- The Modal -->
<div class="modal" id="dipatch_modal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="text-center w-100"><b>Dispatch Details</b></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <form id="approve_form" method="POST" action="{{route('hos3pl.order.status.update')}}">
      @csrf
      <input type="hidden" value="{{$order_code}}" name="order_code">
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
                  <td><input class="form-control" type="text" name="vehical_number" id="vehical_number" required maxlength="10"></td>
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

