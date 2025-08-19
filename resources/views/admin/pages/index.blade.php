@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.page.manage') }}" class="btn btn-primary pull-right"><i
                                    class="fa fa-plus"></i> Add New Page</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="3%">#</th>
                                    <th width="20%">Title</th>
                                    <th width="20%">Updated At</th>
                                    <th width="15%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th width="3%">#</th>
                                    <th width="20%">Title</th>
                                    <th width="20%">Updated At</th>
                                    <th width="15%">Status</th>
                                    <th width="15%">Action</th>
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
            ssakDataTable('table-data', "{{ route('admin.page.getPageList') }}", true, false);

            $(document).on('click', '.btn_delete', function() {
                if (confirm('Are you delete this Page')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{ route('admin.page.delete') }}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! Page deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.page.getPageList') }}", true, false);
                        }
                    });
                }
            });


        });

        function updateStatus(id, status) {
            $.ajax({
                url: '{{ route('admin.page.status') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status

                },
                success: function(data) {
                    toastr.success('Success!', 'Status Updated');
                    ssakDataTable('table-data', "{{ route('admin.page.getPageList') }}", true, false);
                }
            });
        }
    </script>
@endpush
