@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
          <div class="card-tools">
            <a href="javascript:void(0);" class="btn btn-primary pull-right" onclick="addNew();"><i class="fa fa-plus"></i> Add Print Design</a>
          </div>
        </div>

        <div class="card-body">
          <table id="table-data" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Order By</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Order By</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="form-modal" tabindex="-1" aria-labelledby="modal" aria-modal="true" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="manage-form" action="{{ route('admin.masters.printDesignStore') }}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Category</label>
                <select class="form-control" name="category_id" id="category_id">
                  <option value="">Select Category</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Sub Category</label>
                <select class="form-control" name="subcategory_id" id="subcategory_id">
                  <option value="">Select Sub Category</option>
                </select>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
              </div>

              <div class="form-group">
                <label>Image</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image">
                    <label class="custom-file-label" for="image">Choose file</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-md-6">
              <div class="form-group">
                <label>Order By</label>
                <input type="text" class="form-control" name="order_by" id="order_by" placeholder="Enter Order By" >
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
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
    $('#subcategory_id').html('<option value="">Select Sub Category</option>');
    $('.modal-title').html('Add Print Design');
    $('#form-modal').modal('show');
}

$('#category_id').on('change', function(){
    var cat = $(this).val();
    $('#subcategory_id').html('<option>Loading...</option>');
    if (!cat) { $('#subcategory_id').html('<option value="">Select Sub Category</option>'); return; }
    $.ajax({
        url: '{{ route("admin.masters.getSubcategories") }}',
        method: 'post',
        data: { _token: '{{ csrf_token() }}', category_id: cat },
        success: function(res){
            var html = '<option value="">Select Sub Category</option>';
            if (res.success && res.data.length) {
                $.each(res.data, function(i,s){
                    html += '<option value="'+s.id+'">'+s.name+'</option>';
                });
            }
            $('#subcategory_id').html(html);
        }
    });
});

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
                ssakDataTable('table-data', "{{ route('admin.masters.getPrintDesignList') }}", true, false);
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
                $('.modal-title').html('Edit Print Design');
                $('#edit_id').val(response.data.id);
                $('#name').val(response.data.name);
                $('#status').val(response.data.status);
                $('#order_by').val(response.data.position);
                $('#category_id').val(response.data.category_id).trigger('change');

                // after subcategories loaded select subcategory
                setTimeout(function(){
                    $('#subcategory_id').val(response.data.subcategory_id);
                }, 400);

                $('#form-modal').modal('show');
            }
        }
    });
}

$(document).ready(function() {
    ssakDataTable('table-data', "{{ route('admin.masters.getPrintDesignList') }}", true, false);

    $(document).on('click', '.btn_delete', function() {
        if (confirm('Are you delete this Print Design')) {
            var id = $(this).data('id');
            $.ajax({
                url: '{{route("admin.masters.printDesignDelete")}}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id
                },
                success: function(data) {
                    toastr.success('Success! Print Design deleted');
                    ssakDataTable('table-data', "{{ route('admin.masters.getPrintDesignList') }}", true, false);
                }
            });
        }
    });

});

function printDesignStatus(id, status) {
    $.ajax({
        url: '{{ route('admin.masters.printDesignStatus') }}',
        method: 'post',
        data: {
            _token: "{{ csrf_token() }}",
            'id': id,
            'status': status
        },
        success: function(data) {
            toastr.success('Success!', 'Status Updated');
            ssakDataTable('table-data', "{{ route('admin.masters.getPrintDesignList') }}", true, false);
        }
    });
}
</script>
@endpush
