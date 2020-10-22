@extends('hos_3pl.layouts.app')
@section('content')
      <div class="container-fluid main_content bg-white p-2">
        <div class="row mx-0">
          <div class="col-12 text-center">
            <h5 style="color: steelblue"> <b>Open Order list</b> </h5>
          </div>
          <div class="col-12 text-center">
            <table id="example" class="table table-striped table-bordered example">
              <thead>
                  <tr class="bg_color">
                      <th class="text-nowrap px-3">Store Order #</th>
                      <th class="text-nowrap px-3">Hospital</th>
                      <th class="text-nowrap px-3">Delivery date</th>
                      <th class="text-nowrap px-3">Order Items</th>
                      <th class="text-nowrap px-3">Qty Ordered</th>
                      <th class="text-nowrap px-3">Ordered Date</th>
                      <th class="text-nowrap px-3">Status</th>
                  </tr>
              </thead>
              <tbody>
                 

                  @foreach($all_order as $key=>$val)
                  @php
                  $order_data = DB::table('order_details as od')
                                ->select(DB::raw('group_concat(distinct od.status) as status'))
                                ->selectRaw('sum(od.qty_ordered) as total_qty')
                                ->selectRaw('count(od.order_id) as total_item')
                                ->where('od.order_id',$val->order_id)
                                ->where('od.is_deleted', 0)
                                ->orderBy('od.status','ASC')
                                ->first();
                  @endphp
                  <tr onclick="window.location.href='{{url('hos3pl/open_order_detail/'.$val->order_id)}}'">
                      <td>{{$val->order_id}}</td>
                      <td>{{$val->hospital_name}}</td>
                      <td>{{$val->delivery_date}}</td>
                      <td>{{$order_data->total_item}}</td>
                      <td>{{$order_data->total_qty}}</td>
                      <td>{{date('Y-m-d', strtotime($val->created_date))}}</td>
                      <td>
                        
                        @if($order_data->status == '0')
                            <span class="text-warning"><b>NEW</b></span>
                        @elseif($order_data->status == '1')
                            <span class="text-danger"><b>REJECTED</b></span>
                        @elseif($order_data->status == '2')
                        <span class="text-success"><b>APPROVED</b></span>
                        @elseif($order_data->status == '3')
                        <span class="text-primary"><b>DISPATCHED</b></span>
                        @elseif($order_data->status == '4')
                        <span class="text-danger"><b>DELIVERED</b></span>
                        @else
                        <span class="text-primary"><b>PARTIALLY DISPATCHED</b></span>
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
    //Chnage ordering sequence using datatable
    $.fn.dataTable.ext.type.order['order-status-pre'] = function ( d ) {
        switch ( d ) {
            case '<span class="text-primary"><b>APPROVED</b></span>':    return 1;
            case '<span class="text-primary"><b>PARTIALLY DISPATCHED</b></span>': return 2;
            case '<span class="text-primary"><b>DISPATCHED</b></span>':   return 3;
        }
        return 0;
    };

    $(document).ready(function() {
      //Datatable for open order list
        $('.example').DataTable( {
           "order": [6,'asc'],
              columnDefs: [
                { orderable: false, targets: 0},
                { orderable: true, className: 'reorder', targets: 6 , type: "order-status"},
                { orderable: false, targets: '_all' }
            ],
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