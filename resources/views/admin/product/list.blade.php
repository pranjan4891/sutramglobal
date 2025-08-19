@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title . ' ' . $action }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.product.manage') }}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Add Product</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Category and Subcategory Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select id="category" name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="subcategory" name="subcategory" class="form-control">
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>
                        </div>

                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Product Info</th>
                                    <th>Trending</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Product Info</th>
                                    <th>Trending</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
        function ssakDataTable(tableId, ajaxUrl, serverSide = true, processing = true) {
            return $('#' + tableId).DataTable({
                processing: processing,
                serverSide: serverSide,
                ajax: {
                    url: ajaxUrl,
                    type: 'GET',
                    data: function(d) {
                        d.category = $('#category').val(); // Include selected category
                        d.subcategory = $('#subcategory').val(); // Include selected subcategory
                    }
                },
                columns: [
                    { data: 0 }, // #
                    { data: 1 }, // Image
                    { data: 2 }, // Product Info
                    { data: 3 }, // Trending
                    { data: 4 }, // Status
                    { data: 5 }  // Action
                ]
            });
        }
        $(document).ready(function() {
            // Initialize DataTable
            var table = ssakDataTable('table-data', "{{ route('admin.product.getProductList') }}", true, false);

           // Handle category change
            $('#category').on('change', function() {
                var categoryId = $(this).val();
                // Clear subcategory if category changes
                $('#subcategory').empty().append('<option value="">Select Subcategory</option>');

                if (categoryId) {
                    // Fetch subcategories dynamically
                    $.ajax({
                        url: "{{ route('admin.product.getSubcategories') }}",
                        type: "GET",
                        data: { category_id: categoryId },
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#subcategory').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching subcategories:", error);
                        }
                    });
                }

                // Reload DataTable with category filter
                table.ajax.reload();
            });

            // Handle subcategory change to filter products
            $('#subcategory').on('change', function() {
                var subcategoryId = $(this).val();
                var categoryId = $('#category').val();
                var url = "{{ route('admin.product.getProductList') }}?";

                if (categoryId) {
                    url += "category=" + categoryId;
                }
                if (subcategoryId) {
                    url += "&subcategory=" + subcategoryId;
                }

                table.ajax.reload();
            });
        });

        function changeStatus(id, status) {
            $.ajax({
                url: '{{ route('admin.product.changeStatus') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status
                },
                success: function(data) {
                    toastr.success('Success!', 'Status Updated');
                    $('#table-data').DataTable().ajax.reload();
                }
            });
        }

        function changeTrends(id, trending) {
            $.ajax({
                url: '{{ route('admin.product.changeTrends') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'trending': trending
                },
                success: function(data) {
                    toastr.success('Success!', 'Trending Updated');
                    $('#table-data').DataTable().ajax.reload();
                }
            });
        }
    </script>
@endpush
