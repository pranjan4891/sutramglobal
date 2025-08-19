@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                    <div class="card-tools">
                        <a href="javascript:void(0);" class="btn btn-primary pull-right" onclick="addNew();"><i
                                class="fa fa-plus"></i> Add Category</a>
                    </div>
                </div>

                <div class="card-body">
                    <table id="table-data" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="3%">#</th>
                                <th width="15%">Image</th>
                                <th width="20%">Title</th>
                                <th width="20%">Created At</th>
                                <th width="15%">Status</th>
                                <th>Order By</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="3%">#</th>
                                <th width="15%">Image</th>
                                <th width="20%">Title</th>
                                <th width="20%">Created At</th>
                                <th width="15%">Status</th>
                                <th>Order By</th>
                                <th width="15%">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="form-modal" tabindex="-1" aria-labelledby="modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Model</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manage-form" action="{{ route('admin.masters.categoryStore') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">Order By</label>
                               <input type="text" class="form-control" name="order_by" id="order_by" placeholder="Enter Order By" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary float-right px-3 my-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('sub-script')

<script>
    function addNew() {
        $('#manage-form').find('.is-invalid').removeClass('is-invalid');
        $('#manage-form').find('.invalid-feedback').hide();
        $('#manage-form')[0].reset();
        $('#edit_id').val('');
        $('.modal-title').html('Add Category');
        $('#form-modal').modal('show');
    }
    $('#manage-form').submit(function(e) {
        e.preventDefault();
        $('#manage-form').find('.invalid-feedback').hide();
        var data = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#manage-form')[0].reset();
                    $('#form-modal').modal('hide');
                    ssakDataTable('table-data', "{{ route('admin.masters.getCategoryList') }}", true, false);
                } else {
                    $.each(response.message, function(fieldName, field) {
                        $('#manage-form').find('[name=' + fieldName + ']').addClass('is-invalid');
                        $('#manage-form').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                    })
                }

            }
        })
    })

    function edit(url) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('.modal-title').html('Edit Category');
                    $('#edit_id').val(response.data.id);
                    $('#name').val(response.data.name);
                    $('#status').val(response.data.status);
                    $('#order_by').val(response.data.order_by);
                    $('#form-modal').modal('show');
                }
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
            ssakDataTable('table-data', "{{ route('admin.masters.getCategoryList') }}", true, false);

            $(document).on('click', '.btn_delete', function() {
                if (confirm('Are you delete this Category')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.masters.categoryDelete")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! Category deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.masters.getCategoryList') }}", true, false);
                        }
                    });
                }
            });

        });

        function categoryStatus(id, status) {
            $.ajax({
                url: '{{ route('admin.masters.categoryStatus') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status

                },
                success: function(data) {
                    toastr.success('Success!', 'Status Updated');
                    ssakDataTable('table-data', "{{ route('admin.masters.getCategoryList') }}", true, false);
                }
            });
        }
</script>
@endpush
