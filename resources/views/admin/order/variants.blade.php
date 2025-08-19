@extends('admin.layout.layout', ['pageTitle' => $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.products') }}" class="btn btn-secondary pull-right">Back</a>
                            <a href="{{ route('admin.product.manageVariants', ['product_id' => $product_id]) }}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Add Variant</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <input type="hidden" class="data-filter" name="product_id" value="{{$product_id}}">
                        <table id="table-data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Part Number</th>
                                    <th>SKU</th>
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Availability </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Part Number</th>
                                    <th>SKU</th>
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Availability</th>
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
        $(document).ready(function() {
            ssakDataTable('table-data', "{{ route('admin.product.getProductVariants') }}", true, false);

            $(document).on('click', '.btn_delete', function() {
                if (confirm('Are you delete this Product Variants?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{route("admin.product.variantDelete")}}',
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(data) {
                            toastr.success('success', 'Success! Product Variants deleted');
                            ssakDataTable('table-data',
                                "{{ route('admin.product.getProductVariants') }}", true, false);
                        }
                    });
                }
            });

        });

        function variantStatus(id, status) {
            $.ajax({
                url: '{{ route('admin.product.variantStatus') }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': id,
                    'status': status

                },
                success: function(data) {
                    toastr.success('Success!', 'Status Updated');
                    ssakDataTable('table-data', "{{ route('admin.product.getProductVariants') }}", true, false);
                }
            });
        }
    </script>
@endpush
