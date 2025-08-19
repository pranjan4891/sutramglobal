@extends('admin.layout.layout', ['pageTitle' => $title])
@section('contant')
<div class="container-fluid">
    <style>
        .product_image {
            height: 300px;
            width: -webkit-fill-available;
            object-fit: fill;
        }
    </style>
    <form id="manage-form" action="{{ route('admin.product.variantStore') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header py-3">
                        <input type="hidden" name="product_id" id="product_id" value="{{ $product_id }}">
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($variant->id) ? $variant->id : '' }}">
                        <h3 class="card-title mt-1"><i class="fab fa-product-hunt bigfonts"></i>
                            {{ !empty($title) ? $title : '' }}
                        </h3>
                        <a href="{{ route('admin.product.variants', ['product_id' => $product_id]) }}" class="btn px-5 bg-gradient-secondary btn-flat float-right">Back</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text" class="form-control" name="color" id="color"
                                        value="{{ !empty($variant->color) ? $variant->color : '' }}"
                                        placeholder="Please enter color">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="text" class="form-control" name="quantity" id="quantity"
                                        value="{{ !empty($variant->quantity) ? $variant->quantity : '' }}"
                                        placeholder="Please enter quantity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="part_number">Part Number</label>
                                    <input type="text" class="form-control" name="part_number"
                                        id="part_number"
                                        value="{{ !empty($variant->part_number) ? $variant->part_number : '' }}"
                                        placeholder="Please enter part number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sku">SKU</label>
                                    <input type="text" class="form-control" name="sku" id="sku"
                                        value="{{ !empty($variant->sku) ? $variant->sku : '' }}"
                                        placeholder="Please enter sku">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">Images</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_1_preview')"
                                                class="custom-file-input" id="image_1" name="image_1">
                                            <label class="custom-file-label" for="image_1">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_1_preview" class="img-thumbnail pad" alt="image 1"
                                    src="{{ isImage('products', !empty($variant->image_1) ? $variant->image_1 : '') }}">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_2_preview')"
                                                class="custom-file-input" id="image_2" name="image_2">
                                            <label class="custom-file-label" for="image_2">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_2_preview" class="img-thumbnail pad" alt="image 2"
                                    src="{{ isImage('products', !empty($variant->image_2) ? $variant->image_2 : '') }}">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_3_preview')"
                                                class="custom-file-input" id="image_3" name="image_3">
                                            <label class="custom-file-label" for="image_3">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_3_preview" class="img-thumbnail pad" alt="image 3"
                                    src="{{ isImage('products', !empty($variant->image_3) ? $variant->image_3 : '') }}">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_4_preview')"
                                                class="custom-file-input" id="image_4" name="image_4">
                                            <label class="custom-file-label" for="image_4">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_4_preview" class="img-thumbnail pad" alt="image 4"
                                    src="{{ isImage('products', !empty($variant->image_4) ? $variant->image_4 : '') }}">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button class="btn px-5 bg-gradient-dark btn-flat float-right" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
@endsection
@push('sub-script')
    <script>

        $(document).ready(function() {
            $('#manage-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData(this);
                clearError(form)
                $.ajax({
                    url: form.attr('action'),
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    type: form.attr('method'),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            window.location.href = response.url;
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                form.find('[name=' + fieldName + ']').addClass('is-invalid');
                                form.find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }
                    }
                });
            });

        });
        function preview(imageId) {
            const image = document.getElementById(imageId);
            const input = document.getElementById(imageId.replace('_preview', ''));
            const reader = new FileReader();

            reader.onload = function (e) {
                image.src = e.target.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }


    </script>
@endpush
