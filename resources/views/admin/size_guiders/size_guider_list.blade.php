@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.size_guiders.add') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Size Guider</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" >#</th>
                                    <th width="15%" >Category</th>
                                    <th width="15%" >Sub Category</th>
                                    <th width="15%" >Size</th>
                                    <th width="10%" >Chest</th>
                                    <th width="10%" >Length</th>
                                    <th width="10%" >Shoulder</th>
                                    <th width="10%" >Sleeve</th>
                                    <th width="10%" >Waist</th>
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
            ssakDataTable('table-data', "{{ route('admin.size_guiders.getList') }}", true, false);

            $(document).on('click', '.delete', function() {
                if (confirm('Are you sure you want to delete this size guider?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.size_guiders.delete")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('Success!', 'Size guider deleted successfully');
                            ssakDataTable('table-data', "{{ route('admin.size_guiders.getList') }}", true, false);
                        }
                    });
                }
            });
        });
    </script>
@endpush
