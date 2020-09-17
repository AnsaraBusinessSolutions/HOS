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
          <form action="{{route('hos3pl.order.status.update')}}" method="POST">
              @csrf
              <input type="hidden" value="{{$order_code}}" name="order_code">
              <div class="col-12 text-center">
              @if($order_detail[0]->status == 1)
                <input type="hidden" value="3" name="order_status">
                <button class="btn btn-success" type="submit" >Dispatch</button>
              @endif
              </div>
          </form>
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

