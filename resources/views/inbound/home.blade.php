@extends('inbound.layouts.app')
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
                      <th class="text-nowrap px-3">Status</th>
                      <th class="text-nowrap px-3">Delivery date</th>
                      <th class="text-nowrap px-3">Item Count</th>
                      <th class="text-nowrap px-3">Qty Ordered</th>
                      <th class="text-nowrap px-3">Report</th>
                      <th class="text-nowrap px-3">Status</th>
                      <th class="text-nowrap px-3">Action</th>
                  </tr>
              </thead>
              <tbody>
                  <tr onclick="window.location.href='requested_order_details.html'">
                      <td>1</td>
                      <td>material</td>
                      <td>1234567</td>
                      <td>nupco</td>
                      <td>uom</td>
                      <td>27</td>
                      <td>
                        <a target="_blank" href="" class="btn btn-primary btn-sm fs_10">
                          <i class="fas fa-download" aria-hidden="true"></i> / 
                          <i class="fas fa-print" aria-hidden="true"></i></a>
                      </td>
                      <td class=""><span class="text-warning"><b>NEW</b></span></td>
                      <td><button class="btn btn-primary btn-sm fs_10">Delivery Date</button></td>
                  </tr>
                  <tr onclick="window.location.href='requested_order_details.html'">
                      <td>1</td>
                      <td>material</td>
                      <td>1234567</td>
                      <td>nupco</td>
                      <td>uom</td>
                      <td>27</td>
                      <td>
                        <a target="_blank" href="" class="btn btn-primary btn-sm fs_10">
                          <i class="fas fa-download" aria-hidden="true"></i> / 
                          <i class="fas fa-print" aria-hidden="true"></i></a>
                      </td>
                      <td class=""><span class="text-success"><b>Accepted</b></span></td>
                      <td><button class="btn btn-success btn-sm fs_10">N/A</button></td>
                  </tr>
                  <tr onclick="window.location.href='requested_order_details.html'">
                      <td>1</td>
                      <td>material</td>
                      <td>1234567</td>
                      <td>nupco</td>
                      <td>uom</td>
                      <td>27</td>
                      <td>
                        <a target="_blank" href="" class="btn btn-primary btn-sm fs_10">
                          <i class="fas fa-download" aria-hidden="true"></i> / 
                          <i class="fas fa-print" aria-hidden="true"></i></a>
                      </td>
                      <td class=""><span class="text-danger"><b>Rejected</b></span></td>
                      <td><button class="btn btn-success btn-sm fs_10">N/A</button></td>
                  </tr>
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