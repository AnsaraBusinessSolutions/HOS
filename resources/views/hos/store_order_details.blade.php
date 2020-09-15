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
                  <tr>
                      <td>1</td>
                      <td>material</td>
                      <td>1234567</td>
                      <td>nupco</td>
                      <td>uom</td>
                      <td>27</td>
                      <td>available</td>
                  </tr>
                 
              </tbody>
            </table>
          </div>
        </div>
        <script type="text/javascript">
    $(document).ready(function() {
        $('.example').DataTable( {
            "scrollY":        "55vh",
            "scrollCollapse": true,
            "paging":         false,
            "searching": false,
            "lengthMenu": [ [15, 30, 50, 100, 250, 500, 1000, 1500], [15, 20, 50, 100, 250, 500, 1000, 1500] ],
            "iDisplayLength": 1000,
        } );
    } );
  </script>