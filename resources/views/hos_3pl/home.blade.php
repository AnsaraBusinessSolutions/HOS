@extends('hos_3pl.layouts.app')
@section('content')
      <div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Requested Order list</b> </h5>
          </div>
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Store Order #</th>
                      <th class="text-nowrap px-3">Supplying Plant</th>
                      <th class="text-nowrap px-3">Delivery date</th>
                      <th class="text-nowrap px-3">Uom</th>
                      <th class="text-nowrap px-3">Qty Ordered</th>
                      <th class="text-nowrap px-3">Ordered Date</th>
                      <th class="text-nowrap px-3">Status</th>
                  </tr>
              </thead>
              <tbody>
                 

                  @foreach($all_order as $key=>$val)
                  <tr onclick="window.location.href='{{url('hos3pl/order_detail/'.$val->order_code)}}'">
                      <td>{{$val->order_code}}</td>
                      <td>{{$val->delivery_wh_name}}</td>
                      <td>{{$val->delivery_date}}</td>
                      <td>{{$val->uom}}</td>
                      <td>{{$val->total_qty}}</td>
                      <td>{{date('Y-m-d', strtotime($val->created_at))}}</td>
                      <td>
                        @if($val->status == 0)
                            <span class="text-warning"><b>NEW</b></span>
                        @elseif($val->status == 1)
                            <span class="text-success"><b>APPROVED</b></span>
                        @elseif($val->status == 2)
                        <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($val->status == 3)
                        <span class="text-info"><b>DISPATCHED</b></span>
                        @elseif($val->status == 4)
                        <span class="text-danger"><b>DELIVERED</b></span>
                        @elseif($val->status == 5)
                        <span class="text-danger"><b>CANCELLED</b></span>
                        @endif
                      </td>
                      
                  </tr>
               @endforeach 
                  
              </tbody>
            </table>
          </div>
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