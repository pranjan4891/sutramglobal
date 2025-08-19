@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                    </div>

                    <div class="card-body">
                        <div id="dataTableButtons"></div>
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Customer Name</th>

                                    <th>Order Status</th>
                                    <th>Payment Status</th>
                                    <th>Order Total</th>
                                    <th>Order Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data will be populated by DataTables --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Customer Name</th>

                                    <th>Order Status</th>
                                    <th>Payment Status</th>
                                    <th>Order Total</th>
                                    <th>Order Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('sub-script')
<script type="text/javascript">
    $(document).ready(function() {
        ssakaDataTable('table-data', "{{ route('admin.order.getOderList') }}", true, false);

        // Handle delete button
        $(document).on('click', '.btn_delete', function() {
            if (confirm('Are you sure you want to delete this order?')) {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route("admin.order.delete") }}',
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data) {
                        toastr.success('Order deleted successfully');
                        ssakaDataTable('table-data', "{{ route('admin.order.getOderList') }}", true, false);
                    }
                });
            }
        });
    });
</script>

<script type="text/javascript">
function ssakaDataTable(table_id, url = null, custom_filter = false, export_button = false) {
    $('#' + table_id).DataTable().destroy();

    if (url != null) {
        var filter = {};
        filter['_token'] = '{{ csrf_token() }}';  // Add CSRF token for security

        if (custom_filter) {
            $('.data-filter').each(function(index) {
                if ($(this).val().trim().length > 0) {
                    var key = $(this).attr('name');
                    var val = $(this).val();
                    filter[key] = val;  // Add each filter field to the request data
                }
            });
        }

        console.log(filter);  // Optional: You can remove this debug line

        var table = $('#' + table_id).DataTable({
            lengthMenu: [10, 25, 50, 100, 200, 500],  // Set pagination options
            processing: true,  // Show processing indicator
            serverSide: true,  // Enable server-side processing
            ajax: {
                url: url,  // The URL for the server-side data request
                type: 'POST',  // Use POST method to send request
                data: filter,  // Send filter data
            },
            columns: [
                { data: 'id', name: 'id' },  // For row numbering
                { data: 'unique_order_id', name: 'unique_order_id' },
                { data: 'name', name: 'name' },

                { data: 'order_status', name: 'order_status' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'gtotal', name: 'gtotal' },
                { data: 'date', name: 'date' },
                { data: 'action', name: 'action', orderable: false, searchable: false }  // For Action buttons
            ],
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                // Additional customizations can be added here
            },
            order: [[1, 'desc']],  // Default order by the 'order_id' column in descending order
            // drawCallback: function() {
            //     $('.btn_delete').on('click', function() {
            //         if (confirm('Are you sure you want to delete this order?')) {
            //             var id = $(this).data('id');
            //             $.ajax({
            //                 url: '{{ route("admin.order.delete") }}',  // Delete route
            //                 type: 'POST',
            //                 data: {
            //                     _token: '{{ csrf_token() }}',
            //                     id: id
            //                 },
            //                 success: function(response) {
            //                     toastr.success('Order deleted successfully');
            //                     table.ajax.reload();  // Reload the table after deletion
            //                 }
            //             });
            //         }
            //     });
            // }
        });

        if (export_button) {
            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [
                    { extend: 'copy', text: 'Copy' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'csv', text: 'CSV' },
                    { extend: 'pdf', text: 'PDF' },
                    { extend: 'print', text: 'Print' }
                ]
            }).container().appendTo($('#dataTableButtons'));
        }
    } else {
        var table = $('#' + table_id).DataTable();
    }
}


</script>
    <script type="text/javascript">
        // $(document).ready(function() {
        //     ssakaDataTable('table-data', "{{ route('admin.order.getOderList') }}", true, false);

        //     // Handle delete button
        //     $(document).on('click', '.btn_delete', function() {
        //         if (confirm('Are you sure you want to delete this order?')) {
        //             var id = $(this).data('id');
        //             $.ajax({
        //                 url: '{{ route("admin.order.delete") }}',
        //                 method: 'post',
        //                 data: {
        //                     _token: "{{ csrf_token() }}",
        //                     'id': id
        //                 },
        //                 success: function(data) {
        //                     toastr.success('Order deleted successfully');
        //                     ssakaDataTable('table-data', "{{ route('admin.order.getOderList') }}", true, false);
        //                 }
        //             });
        //         }
        //     });

        // });

        // Function to change status (can be customized as needed)
        function changeStatus(id, status) {
            $.ajax({
                url: '{{ route('admin.order.changeStatus') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status
                },
                success: function(data) {
                    toastr.success('Status Updated');
                    ssakaDataTable('table-data', "{{ route('admin.order.getOderList') }}", true, false);
                }
            });
        }
    </script>
@endpush
