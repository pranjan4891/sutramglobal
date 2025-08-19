@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.sizes.add') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add size</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" >#</th>
                                    <th width="15%" >Size</th>
                                    <th width="15%" >Category</th>
                                    <th width="15%" >Type</th>
                                    <th width="13%" >Chest</th>
                                    <th width="13%" >Waist</th>
                                    <th width="13%" >Length</th>
                                    <th width="11%" >Action</th>
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
            ssakDataTable('table-data', "{{ route('admin.sizes.getList') }}", true, false);

            $(document).on('click', '.delete', function() {
                if (confirm('Are you delete this size')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.sizes.delete")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! size deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.sizes.getList') }}", true, false);
                        }
                    });
                }
            });
        });
        function change_status(id, status) {
            $.ajax({
                url: '{{ route('admin.sizes.status') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status

                },
                success: function(data) {
                    toastr.success('Success!', 'Status Updated');
                    ssakDataTable('table-data', "{{ route('admin.sizes.getList') }}", true, false);
                }
            });
        }
    </script>
@endpush
