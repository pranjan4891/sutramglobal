@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.company.add') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Company</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="3%" >#</th>
                                    <th width="15%" >Logo</th>
                                    <th width="30%" >Name</th>
                                    <th width="15%" >Typo</th>
                                    <th width="15%" >Created At</th>
                                    <th width="15%" >Status</th>
                                    <th width="10%" >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
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
            ssakDataTable('table-data', "{{ route('admin.company.getList') }}", true, false);

            $(document).on('click', '.delete', function() {
                if (confirm('Are you delete this company')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.company.delete")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! Company deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.company.getList') }}", true, false);
                        }
                    });
                }
            });
        });
        function change_status(id, status) {
            $.ajax({
                url: '{{ route('admin.company.status') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status

                },
                success: function(data) {
                    toastr.success('Success!', 'Status Updated');
                    ssakDataTable('table-data', "{{ route('admin.company.getList') }}", true, false);
                }
            });
        }
    </script>
@endpush
