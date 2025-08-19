@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.product.add') }}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Add Painting</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Style</th>
                                    <th>Artist</th>
                                    <th>Size(inch)</th>
                                    <th>Size(cm)</th>
                                    {{--  <th>Price</th>  --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Style</th>
                                    <th>Artist</th>
                                    <th>Size(inch)</th>
                                    <th>Size(cm)</th>
                                    {{--  <th>Price</th>  --}}
                                    <th>Action</th>
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
            ssakDataTable('table-data', "{{ route('admin.product.getProductList') }}", true, false);

            $(document).on('click', '.btn_delete', function() {
                if (confirm('Are you delete this painting?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.product.delete")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! Painting deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.product.getProductList') }}", true, false);
                        }
                    });
                }
            });


        });

        function is_active_status(id, e) {
            var status = $(e).is(':checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('admin.product.isActiveStatus') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status

                },
                success: function(data) {
                    toastr.success('Success!', 'Is Active Updated');
                    ssakDataTable('table-data', "{{ route('admin.product.getProductList') }}", true, false);
                }
            });
        }

        function is_explore(id, e) {
            var is_explore = $(e).is(':checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('admin.product.exploreArtistStore') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'is_explore': is_explore
                },
                success: function(data) {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                    } else if (data.status === 'error') {
                        toastr.warning(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                 //   ssakDataTable('table-data', "{{ route('admin.product.getProductList') }}", true, false);
                }

            });
        }
    </script>
@endpush
