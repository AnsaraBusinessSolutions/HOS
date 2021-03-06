@extends('custodian.layouts.app')
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
                      <th class="text-nowrap px-3">Hospital</th>
                      <th class="text-nowrap px-3">Supplying Plant</th>
                      <th class="text-nowrap px-3">Delivery Date</th>
                      <th class="text-nowrap px-3">Order Items</th>
                      <th class="text-nowrap px-3">Qty Ordered</th>
                      <th class="text-nowrap px-3">Ordered Date</th>
                      <th class="text-nowrap px-3">Status</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($all_order as $key=>$val)
                  <tr onclick="window.location.href='{{url('custodian/request_order_detail/'.$val->order_id)}}'">
                      <td>@if($val->order_type == 'emergency')<i class="fas fa-circle text-danger fs_10"></i>&ensp;@endif{{$val->order_id}}</td>
                      <td>{{$val->hospital_name}}</td>
                      <td>{{$val->supplying_plant}}</td>
                      <td>{{$val->delivery_date}}</td>
                      <td>{{$val->total_item}}</td>
                      <td>{{$val->total_qty}}</td>
                      <td>{{date('Y-m-d', strtotime($val->created_date))}}</td>
                      <td>
                        @if($val->status == 0)
                        <span class="text-warning"><b>NEW</b></span>
                        @elseif($val->status == 1)
                        <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($val->status == 2)
                        <span class="text-success"><b>APPROVED</b></span>
                        @elseif($val->status == 3)
                        <span class="text-primary"><b>DISPATCHED</b></span>
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
    //Datatable of order list in homepage
    $(document).ready(function() {
        $('.example').DataTable( {
            "ordering":false,
            //"order": [[ 1, "desc" ]],
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