@extends('inventory.layouts.app')
@section('content')
      <div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Delivered Order list</b> </h5>
          </div>
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Store Order #</th>
                      <th class="text-nowrap px-3">Hospital</th>
                      <th class="text-nowrap px-3">Delivery date</th>
                      <th class="text-nowrap px-3">Delivered Items</th>
                      <th class="text-nowrap px-3">Qty Delivered</th>
                      <th class="text-nowrap px-3">Delivered Date</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($all_order as $key=>$val)
                  <tr onclick="window.location.href='{{url('inventory/display_order_detail/'.$val->order_id)}}'">
                      <td>{{$val->order_id}}</td>
                      <td>{{$val->hospital_name}}</td>
                      <td>{{$val->delivery_date}}</td>
                      <td>{{$val->total_item}}</td>
                      <td>{{$val->total_batch_qty}}</td>
                      <td>{{date('Y-m-d', strtotime($val->created_at))}}</td>
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
      //Datatable for display delivered orders
        $('.example').DataTable( {
            "ordering": false,
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