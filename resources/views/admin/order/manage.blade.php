@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <style>
        .product_image {
            height: 300px;
            width: -webkit-fill-available;
            object-fit: fill;
        }
    </style>
    <form id="manage-form" action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header py-3">
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($product->id) ? $product->id : '' }}">
                        <h4 class="card-title mt-1"><i class="fab fa-product-hunt bigfonts"></i>
                            {{ !empty($title) ? $title : '' }}
                        </h4>
                        <div class="card-tools">
                            <a href="{{ route('admin.products') }}" class="btn btn-secondary pull-right">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Product Details:</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        value="{{ !empty($product->title) ? $product->title : '' }}"
                                        placeholder="Please enter title">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sub_title">Sub Title</label>
                                    <input type="text" class="form-control" name="sub_title" id="sub_title"
                                        value="{{ !empty($product->sub_title) ? $product->sub_title : '' }}"
                                        placeholder="Please enter sub title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control"
                                        onchange="get_subcategory(this);">
                                        <option value="">Select</option>
                                        @foreach ($categories as $key => $category)
                                        <option value="{{ $category->id }}" {{ !empty($product->category_id) &&
                                            $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id">Sub Category</label>
                                    <select name="subcategory_id" id="subcategory_id" class="form-control">
                                        <option value="">Select</option>
                                        @if (!empty($subcategories))
                                        @foreach ($subcategories as $key => $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ !empty($product->subcategory_id) &&
                                            $product->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="part_number">Part Number</label>
                                    <input type="text" class="form-control" name="part_number"
                                        id="part_number"
                                        value="{{ !empty($product->part_number) ? $product->part_number : '' }}"
                                        placeholder="Please enter part number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sku">SKU</label>
                                    <input type="text" class="form-control" name="sku" id="sku"
                                        value="{{ !empty($product->sku) ? $product->sku : '' }}"
                                        placeholder="Please enter sku">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Base Price</label>
                                    <input type="text" class="form-control" name="price" id="price"
                                        value="{{ !empty($product->price) ? number_format($product->price, 2, '.', '') : '' }}"
                                        placeholder="Please enter price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="text" class="form-control" name="quantity" id="quantity"
                                        value="{{ !empty($product->quantity) ? $product->quantity : '' }}"
                                        placeholder="Please enter quantity">
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
                                    src="{{ isImage('products', !empty($product->image_1) ? $product->image_1 : '') }}">
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
                                    src="{{ isImage('products', !empty($product->image_2) ? $product->image_2 : '') }}">
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
                                    src="{{ isImage('products', !empty($product->image_3) ? $product->image_3 : '') }}">
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
                                    src="{{ isImage('products', !empty($product->image_4) ? $product->image_4 : '') }}">
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Companies Price:</label>
                                </div>
                            </div>
                            @foreach ($companies as $key => $company)
                            @php
                            $isChecked = !empty($product->company_prices) && array_key_exists($company->id,
                            json_decode($product->company_prices, true));
                            $price = $isChecked ? json_decode($product->company_prices, true)[$company->id]['price'] : '';
                            @endphp
                            <div class="col-md-6">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="company_{{ $key }}" name="company_id[]"
                                            class="company-checkbox" data-target="#company_{{ $key }}_div"
                                            value="{{ $company->id }}" {{ $isChecked ? 'checked' : '' }}>
                                        <label for="company_{{ $key }}">{{ $company->name }}</label>
                                    </div>
                                </div>
                                <div id="company_{{ $key }}_div" class="form-group"
                                    style="display: {{ $isChecked ? 'block' : 'none' }};">
                                    <input type="text" class="form-control" name="company_price[{{ $company->id }}]"
                                        id="company_price_{{ $key }}" value="{{ $price }}"
                                        placeholder="Please enter price">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row" id="add-features-div">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Overview:</label>
                                </div>
                            </div>

                            <div class="col-md-12" id="add-features-input-div">


                                @if (!empty($product->features))
                                @php
                                $features = is_string($product->features) && !empty($product->features) ?
                                json_decode($product->features, true) : [];
                                $featureIndex = 20;
                                @endphp
                                @foreach ($features as $key => $feature)
                                @php
                                $featureIndex++;
                                $featureID = "feature_" . $featureIndex;
                                $descriptionID = "description_" . $featureIndex;
                                @endphp

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="{{ $featureID }}" class="form-label">Key</label>
                                            <input type="text" class="form-control" id="{{ $featureID }}"
                                                name="feature[]" value="{{ $key }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="{{ $descriptionID }}" class="form-label">Value</label>
                                            <input type="text" class="form-control" id="{{ $descriptionID }}"
                                                name="feature_description[]" value="{{ $feature }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 py-2 mt-4">
                                        @if($featureIndex == 21 || (count($features) == $featureIndex))
                                        <button type="button" onclick="handleAddFeatures()"
                                            class="btn btn-dark btn-sm py-2"><i class="fa fa-plus"></i> Add
                                            more</button>
                                        @else
                                        <button type="button" onclick="handleRemoveFeatures(this)"
                                            class="btn btn-danger btn-sm py-2"><i class="fa fa-trash-alt"></i>
                                            Remove</button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="feature_1" class="form-label">Key</label>
                                            <input type="text" class="form-control" id="feature_1" name="feature[]"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="description_1" class="form-label">Value</label>
                                            <input type="text" class="form-control" id="description_1"
                                                name="feature_description[]" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3 py-2 mt-4">
                                        <button type="button" onclick="handleAddFeatures()"
                                            class="btn btn-dark btn-sm py-2"><i class="fa fa-plus"></i> Add
                                            more</button>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control ckEditor" name="description" id="description"
                                        placeholder="Please enter description">{{ !empty($product->description) ? $product->description : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="status" id="status">
                                        <option value="1" {{ !empty($product) && $product->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !empty($product) && $product->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button class="btn px-5 bg-gradient-dark btn-flat float-right" type="submit">Save</button>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.company-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const targetDiv = document.querySelector(this.getAttribute('data-target'));
                    if (this.checked) {
                        targetDiv.style.display = 'block';
                    } else {
                        targetDiv.style.display = 'none';
                    }
                });
            });
        });


        $(document).ready(function() {
            const editors = ['description'];
            editors.forEach(editorId => {
                const config = {
                    height: 300
                };

                if (CKEDITOR.instances[editorId]) {
                    CKEDITOR.instances[editorId].destroy(true);
                }

                CKEDITOR.replace(editorId, config);
            });

            $('#manage-form').submit(function(e) {
                e.preventDefault();

                editors.forEach(editorId => {
                    if (CKEDITOR.instances[editorId]) {
                        CKEDITOR.instances[editorId].updateElement();
                    }
                });
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

        const handleAddFeatures = () => {
            const featuresInputDiv = document.getElementById('add-features-input-div');
            const featuresIndex = $('#add-features-input-div .row').length + 1;

            if (!featuresInputDiv) {
                console.error("add-features-input-div not found.");
                return;
            }

            // Your HTML template for features input fields
            let html = `<div class="col-md-4">
                    <div class="form-group">
                        <label for="feature_${featuresIndex}" class="form-label">Key</label>
                        <input type="text" class="form-control" id="feature_${featuresIndex}" name="feature[]">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="description_${featuresIndex}" class="form-label">Value</label>
                        <input type="text" class="form-control" id="description_${featuresIndex}" name="feature_description[]">
                    </div>
                </div>
                <div class="col-md-3 py-2 mt-4">
                    <button type="button" onclick="handleRemoveFeatures(this)" class="btn btn-danger btn-sm py-2"><i class="fa fa-trash-alt"></i> Remove</button>
                </div>`;

            const featuresRow = document.createElement('div');
            featuresRow.className = "row";
            featuresRow.innerHTML = html;

            featuresInputDiv.appendChild(featuresRow);
        };

        const handleRemoveFeatures = (event) => {
            $(event).closest('.row').remove();
        };

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
