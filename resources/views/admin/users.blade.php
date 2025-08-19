@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary pull-right"><i
                                class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="3%" >#</th>
                                    <th width="20">User Photo</th>
                                    <th width="17%" >User Name</th>
                                    <th width="10%" >Mobile </th>
                                    <th width="15%" >Email</th>
                                    <th width="10%" >Join At</th>
                                    <th width="10%">Status</th>
                                    <th width="15%" >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th width="3%" >#</th>
                                    <th width="20">User Photo</th>
                                    <th width="17%" >User Name</th>
                                    <th width="10%" >Mobile </th>
                                    <th width="15%" >Email</th>
                                    <th width="10%" >Join At</th>
                                    <th width="10%">Status</th>
                                    <th width="15%" >Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
@endsection
@push('sub-script')
    <script type="text/javascript">
        $(document).ready(function() {
            ssakDataTable('table-data', "{{ route('admin.users.getUsersLists') }}", true, false);
            $(document).on('click', '.btn_delete', function() {
                let id = $(this).data('id'); // Get the ID from the clicked button
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: '{{ route("admin.user.delete") }}', // Correct route for soft delete
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF token
                            id: id
                        },
                        success: function(response) {
                            // Show success message
                            toastr.success(response.message);

                            setTimeout(function() {
                                window.location.reload(); // Refresh the page after a delay
                            }, 2000); // Adjust the delay time as needed (2 seconds here)
                        },
                        error: function(err) {
                            // Handle error and show an error message
                            toastr.error(err.responseJSON.error || 'Error! Something went wrong.');
                        }
                    });
                }
            });


            $(document).on('click', '.change-status', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: '{{ route("admin.user.changeStatus") }}',  // Correct route for status change
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload(); // Refresh the page after a delay
                        }, 2000); // Adjust the delay time as needed (2 seconds here)
                    },
                    error: function(err) {
                        toastr.error('Error! Something went wrong.');
                    }
                });
            });


        });

    </script>
@endpush
