@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.blog.add') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Blog</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="3%" >#</th>
                                    <th width="10%" >Image</th>
                                    <th width="30%" >Title</th>
                                    <th width="15%" >Created Info</th>
                                    <th width="10%" >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                {{-- <tr>
                                    <th width="10%" >#</th>
                                    <th width="10%" >Image</th>
                                    <th width="10%" >Title</th>
                                    <th width="10%" >Created Info</th>
                                    <th width="10%" >Action</th>
                                </tr> --}}
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
            ssakDataTable('table-data', "{{ route('admin.blog.getBlogList') }}", true, false);

            $(document).on('click', '.blog_delete', function() {
                if (confirm('Are you delete this blog')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.blog.delete")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! Blog deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.blog.getBlogList') }}", true, false);
                        }
                    });
                }
            });


        });

        function isActive(id, e) {
            var status = $(e).is(':checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('admin.blog.isActive') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status

                },
                success: function(data) {
                    toastr.success('Success!', 'Is Active Updated');
                    ssakDataTable('table-data', "{{ route('admin.blog.getBlogList') }}", true, false);
                }
            });
        }

        function isHome(id, e) {
            var is_home = $(e).is(':checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('admin.blog.isHome') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'is_home': is_home
                },
                success: function(data) {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                    } else if (data.status === 'error') {
                        toastr.warning(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                 //   ssakDataTable('table-data', "{{ route('admin.blog.getBlogList') }}", true, false);
                }

            });
        }
    </script>
@endpush
