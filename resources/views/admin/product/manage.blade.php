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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        value="{{ !empty($product->title) ? $product->title : '' }}"
                                        placeholder="Please enter title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Select Size Guider</label>
                                    <select name="size_guider_id" id="size_guider_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach ($size_guider as $val)
                                        <option value="{{ $val->id }}" {{ !empty($val->id) && $val->id == @$product->size_guider_id ? 'selected' : '' }}>
                                            {{ $val->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_title">Sub Title</label>
                                    <input type="text" class="form-control" name="sub_title" id="sub_title"
                                        value="{{ !empty($product->sub_title) ? $product->sub_title : '' }}"
                                        placeholder="Please enter sub title">
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
                        </div>
                        <div class="row">
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
                        </div>
                        <div class="row">
                            <!-- Variant Section -->
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Product Variants:</label>
                                </div>

                                <div id="variant-container">
                                    <!-- Existing Variants -->
                                    @if (!empty($product->variants))
                                        @foreach (json_decode($product->variants, true) as $index => $variant)
                                            <div class="row variant-row mb-3">
                                                <!-- Size -->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="sizes_{{ $index }}">Size</label>
                                                        <select name="variants[{{ $index }}][size]" class="form-control">
                                                            <option value="">Please Select</option>
                                                            @foreach ($sizes as $size)
                                                                <option value="{{ $size->id }}" {{ $variant['size_id'] == $size->id ? 'selected' : '' }}>
                                                                    {{ $size->code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error("variants.$index.size")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Color -->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="colors_{{ $index }}">Color</label>
                                                        <select name="variants[{{ $index }}][color]" class="form-control">
                                                            <option value="">Please Select</option>
                                                            @foreach ($colors as $color)
                                                                <option value="{{ $color->id }}" {{ $variant['color_id'] == $color->id ? 'selected' : '' }}>
                                                                    {{ $color->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error("variants.$index.color")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Quantity -->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="quantity_{{ $index }}">Quantity</label>
                                                        <input type="text" name="variants[{{ $index }}][quantity]" class="form-control"
                                                            value="{{ $variant['quantity'] }}" placeholder="Enter quantity">
                                                        @error("variants.$index.quantity")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Original Price -->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="original_price_{{ $index }}">Original Price</label>
                                                        <input type="text" name="variants[{{ $index }}][original_price]" class="form-control"
                                                            value="{{ number_format($variant['original_price'], 2, '.', '') }}" placeholder="Enter original price">
                                                        @error("variants.$index.original_price")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Discounted Price -->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="price_{{ $index }}">Price</label>
                                                        <input type="text" name="variants[{{ $index }}][price]" class="form-control"
                                                            value="{{ number_format($variant['price'], 2, '.', '') }}" placeholder="Enter price">
                                                        @error("variants.$index.price")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Remove Button -->
                                                <div class="col-md-2 d-flex align-items-center">
                                                    <button type="button" class="btn btn-danger mt-3 remove-variant">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <!-- Add Variant Button -->
                                <button type="button" class="btn btn-primary mb-3" id="add-variant-btn">Add Variant</button>
                            </div>
                        </div>

                        <!-- Template Row for New Variants -->
                        <div class="row variant-row mb-3 template-row d-none">
                            <!-- Size -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sizes">Size</label>
                                    <select name="variants[__INDEX__][size]" class="form-control" disabled required>
                                        <option value="">Please Select</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->code }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Size is required.</div>
                                </div>
                            </div>
                            <!-- Color -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="colors">Color</label>
                                    <select name="variants[__INDEX__][color]" class="form-control" disabled>
                                        <option value="">Please Select</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Color is required.</div>
                                </div>
                            </div>
                            <!-- Quantity -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="text" name="variants[__INDEX__][quantity]" class="form-control" placeholder="Enter quantity" disabled required>
                                    <div class="invalid-feedback">Quantity is required.</div>
                                </div>
                            </div>
                            <!-- Original Price -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="original_price">Original Price</label>
                                    <input type="text" name="variants[__INDEX__][original_price]" class="form-control" placeholder="Enter original price" disabled required>
                                    <div class="invalid-feedback">Original price is required.</div>
                                </div>
                            </div>
                            <!-- Discounted Price -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" name="variants[__INDEX__][price]" class="form-control" placeholder="Enter price" disabled required>
                                    <div class="invalid-feedback">Price is required.</div>
                                </div>
                            </div>
                            <!-- Remove Button -->
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="button" class="btn btn-danger mt-3 remove-variant">Remove</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Product Images:</label>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_5_preview')"
                                                class="custom-file-input" id="image_5" name="image_5">
                                            <label class="custom-file-label" for="image_5">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_5_preview" class="img-thumbnail pad" alt="image 5"
                                    src="{{ isImage('products', !empty($product->image_5) ? $product->image_5 : '') }}">

                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_6_preview')"
                                                class="custom-file-input" id="image_6" name="image_6">
                                            <label class="custom-file-label" for="image_6">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_6_preview" class="img-thumbnail pad" alt="image 6"
                                    src="{{ isImage('products', !empty($product->image_6) ? $product->image_6 : '') }}">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_7_preview')"
                                                class="custom-file-input" id="image_7" name="image_7">
                                            <label class="custom-file-label" for="image_7">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_7_preview" class="img-thumbnail pad" alt="image 7"
                                    src="{{ isImage('products', !empty($product->image_7) ? $product->image_7 : '') }}">

                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" onchange="preview('image_8_preview')"
                                                class="custom-file-input" id="image_8" name="image_8">
                                            <label class="custom-file-label" for="image_8">Choose
                                                file</label>
                                        </div>
                                    </div>
                                </div>
                                <img id="image_8_preview" class="img-thumbnail pad" alt="image 8"
                                    src="{{ isImage('products', !empty($product->image_8) ? $product->image_8 : '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 my-3">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Product Other Information:</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="s_description">Return Policy</label>
                                    <textarea class="form-control " name="s_description" id="s_description"
                                        placeholder="Please enter return policy">{{ !empty($product->short_desc) ? $product->short_desc : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control " name="description" id="description"
                                        placeholder="Please enter description">{{ !empty($product->description) ? $product->description : '' }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="row" id="add-features-div">
                            {{-- <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Features:</label>
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

                            </div> --}}
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
    document.addEventListener("DOMContentLoaded", function () {
        // Get references to the required elements
        const categorySelect = document.getElementById("category_id");
        const variantContainer = document.getElementById("variant-container");
        const addVariantBtn = document.getElementById("add-variant-btn");
        const templateRow = document.querySelector(".template-row");

        // Ensure the template row exists
        if (!templateRow) {
            console.error("Template row not found!");
            return;
        }

        // Function to check the category and toggle the visibility of the "Color" section
        function toggleColorSection(row, hide) {
            const colorSection = row.querySelector(".col-md-2:nth-child(2)"); // Assuming color is the second column
            const colorSelect = colorSection.querySelector("select"); // Get the select element

            if (hide) {
                colorSection.style.display = "none";
                colorSelect.disabled = true; // Disable the select field
                colorSelect.removeAttribute("required"); // Remove the 'required' attribute
            } else {
                colorSection.style.display = "block";
                colorSelect.disabled = false; // Enable the select field
                colorSelect.setAttribute("required", "required"); // Add the 'required' attribute
            }
        }


        // Function to handle adding a new variant row
        addVariantBtn.addEventListener("click", function () {
            console.log("Add Variant button clicked");

            // Clone the template row and make it visible
            const newRow = templateRow.cloneNode(true);
            newRow.classList.remove("d-none", "template-row");

            // Enable all input fields within the new row
            const inputs = newRow.querySelectorAll("input, select");
            inputs.forEach((input) => {
                input.disabled = false;
            });

            // Replace placeholder `__INDEX__` with a unique value
            const uniqueIndex = Date.now(); // Use a timestamp as a unique identifier
            newRow.innerHTML = newRow.innerHTML.replace(/__INDEX__/g, uniqueIndex);

            // Append the new row to the container
            variantContainer.appendChild(newRow);

            // Check if the category ID is 3 to hide the color section
            const hideColor = categorySelect.value === "3";
            toggleColorSection(newRow, hideColor);

            // Add event listener to the "Remove" button in the new row
            const removeButton = newRow.querySelector(".remove-variant");
            removeButton.addEventListener("click", function () {
                newRow.remove();
                console.log("Variant row removed");
            });

            console.log("New variant row added");
        });

        // Attach event listeners to any existing "Remove" buttons (if any)
        const existingRemoveButtons = variantContainer.querySelectorAll(".remove-variant");
        existingRemoveButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const row = button.closest(".variant-row");
                if (row) row.remove();
                console.log("Existing variant row removed");
            });
        });

        // Handle category change
        categorySelect.addEventListener("change", function () {
            console.log("Category changed to:", categorySelect.value);

            // Check if the selected category is 3
            const hideColor = categorySelect.value === "3";

            // Update existing rows
            const existingRows = variantContainer.querySelectorAll(".variant-row");
            existingRows.forEach((row) => {
                toggleColorSection(row, hideColor);
            });
        });

        // Initialize the color section visibility for existing variants on page load
        const hideColorOnLoad = categorySelect.value === "3";
        const existingRowsOnLoad = variantContainer.querySelectorAll(".variant-row");
        existingRowsOnLoad.forEach((row) => {
            toggleColorSection(row, hideColorOnLoad);
        });
    });
</script>
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


        $(document).ready(function () {
            // Array of editor IDs
            const editors1 = ['description'];
            const editors2 = ['s_description'];

            // Configuration for CKEditor
            const config = {
                height: 300,
            };

            // Initialize CKEditor for editors1
            editors1.forEach(editorId => {
                if (CKEDITOR.instances[editorId]) {
                    CKEDITOR.instances[editorId].destroy(true);
                }
                CKEDITOR.replace(editorId, config);
            });

            // Initialize CKEditor for editors2
            editors2.forEach(editorId => {
                if (CKEDITOR.instances[editorId]) {
                    CKEDITOR.instances[editorId].destroy(true);
                }
                CKEDITOR.replace(editorId, config);
            });

            // Form submission handler
            $('#manage-form').submit(function (e) {
                e.preventDefault();

                // Update CKEditor instances before submission
                editors1.forEach(editorId => {
                    if (CKEDITOR.instances[editorId]) {
                        CKEDITOR.instances[editorId].updateElement();
                    }
                });

                editors2.forEach(editorId => {
                    if (CKEDITOR.instances[editorId]) {
                        CKEDITOR.instances[editorId].updateElement();
                    }
                });

                const form = $(this);
                const data = new FormData(this);

                clearError(form); // Function to clear previous errors

                $.ajax({
                    url: form.attr('action'),
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    type: form.attr('method'),
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);

                            // Reload the current page after showing the success message
                            setTimeout(function () {
                                window.location.href= response.url;
                            }, 2000); // Delay the reload by 2 seconds (adjust as needed)
                        } else {
                            // Display validation errors
                            $.each(response.message, function (fieldName, field) {
                                const fieldSelector = form.find(`[name="${fieldName}"], [name="${fieldName}[]"]`);
                                fieldSelector.addClass('is-invalid');
                                fieldSelector.after(`<div class="invalid-feedback">${field}</div>`);
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Loop through each error and display it
                            Object.keys(errors).forEach(function (key) {
                                const fieldError = errors[key][0]; // Get the first error message
                                const fieldSelector = form.find(`[name="${key.replace(/\./g, '\\.')}"]`);
                                if (fieldSelector.length) {
                                    fieldSelector.addClass('is-invalid');
                                    fieldSelector.after(`<div class="invalid-feedback">${fieldError}</div>`);
                                }
                            });

                            toastr.error('Please correct the errors and try again.');
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    },
                });
            });

        });

        function clearError(form) {
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').remove();
        }

        function showValidationErrors(form, errors) {
            Object.keys(errors).forEach((key) => {
                const fieldError = errors[key][0]; // Get the first error message
                const fieldSelector = form.find(`[name="${key.replace(/\./g, '\\.')}"]`);
                if (fieldSelector.length) {
                    fieldSelector.addClass('is-invalid');
                    fieldSelector.after(`<div class="invalid-feedback">${fieldError}</div>`);
                }
            });
        }

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
