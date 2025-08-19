@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.coupons.add') }}" class="btn btn-info pull-right">
                                <i class="fa fa-plus"></i> Add Coupon
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="coupon-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Coupon Code</th>
                                    <th>Discount Type</th>
                                    <th>Discount Value</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Coupon Code</th>
                                    <th>Discount Type</th>
                                    <th>Discount Value</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="statusForm" action="{{ route('admin.coupons.status') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Change Coupon Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="couponId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                <option value="2">Expired</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('sub-script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize DataTable
            ssakDataTable('coupon-table', "{{ route('admin.coupons.getList') }}", true, false);

            // Open Status Modal
            $(document).on('click', '.status-btn', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');

                $('#couponId').val(id);
                $('#status').val(status);
                $('#statusModal').modal('show'); // Show the modal
            });

            // Handle Status Update
            $('#statusForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#statusModal').modal('hide');
                        toastr.success('Success!', 'Status updated successfully.');
                        $('#coupon-table').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Error!', 'Unable to update status.');
                    }
                });
            });
        });
    </script>
@endpush
