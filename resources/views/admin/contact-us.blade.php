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
                                    <th width="23%" >User Info</th>
                                    <th width="23%" >Order No</th>
                                    <th>Message</th>
                                    <th width="10%" >Created At</th>
                                    <th width="10%" >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th width="3%" >#</th>
                                    <th width="23%" >User Info</th>
                                    <th width="23%" >Order No</th>
                                    <th>Message</th>
                                    <th width="10%" >Created At</th>
                                    <th width="10%" >Action</th>
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
            ssakDataTable('table-data', "{{ route('admin.dashboard.getcontactUsList') }}", true, false);

            $(document).on('click', '.btn_delete', function() {
                if (confirm('Are you delete this Contact Us Request')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.dashboard.deleteContactUs")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! Contact Us Request deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.dashboard.getcontactUsList') }}", true, false);
                        }
                    });
                }
            });


        });

    </script>
@endpush
