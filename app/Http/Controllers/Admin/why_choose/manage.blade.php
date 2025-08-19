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
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.product.store') }}">
            @csrf
            <div class="row">
                <input type="hidden" name="edit_id" value="{{ !empty($product->id) ? $product->id : '' }}">
                <div class="col-md-8">
                    <div class="card card-widget">
                        <div class="card-header">
                            <h3 class="card-title">{{ $action . ' ' . $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ !empty($product->title) ? $product->title : old('title') }}"
                                            placeholder="Enter title">
                                        @if ($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_code">Painting Code</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code"
                                            value="{{ !empty($product->productcode) ? $product->productcode : old('product_code') }}"
                                            placeholder="Enter product code">
                                        @if ($errors->has('product_code'))
                                            <span class="text-danger">{{ $errors->first('product_code') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category">Style</label>
                                        <select class="form-control" name="category" id="category">
                                            <option value="">Select Style</option>
                                            @foreach ($categories as $category)
                                                <option
                                                    {{ !empty($product) && $product->style == $category->category ? 'selected' : '' }}
                                                    value="{{ $category->category }}">
                                                    {{ str_replace('-', ' ', $category->category) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('category'))
                                            <span class="text-danger">{{ $errors->first('category') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="artist">Artist</label>
                                        <select class="form-control" name="artist" id="artist">
                                            <option value="">Select Artist</option>
                                            @foreach ($artists as $artist)
                                                <option
                                                    {{ !empty($product) && $product->author == $artist->name ? 'selected' : '' }}
                                                    value="{{ $artist->name }}">{{ str_replace('-', ' ', $artist->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('artist'))
                                            <span class="text-danger">{{ $errors->first('artist') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="medium">Medium</label>
                                        <select class="form-control" name="medium" id="medium">
                                            <option value="">Select Medium</option>
                                            @foreach ($mediums as $medium)
                                                <option
                                                    {{ !empty($product) && $product->medium == $medium->name ? 'selected' : '' }}
                                                    value="{{ $medium->name }}">{{ $medium->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('medium'))
                                            <span class="text-danger">{{ $errors->first('medium') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size_inch">Size (Inch)</label>
                                        <select class="form-control" name="size_inch" id="size_inch">
                                            <option value="">Select Size (Inch)</option>
                                            @foreach ($sizes_inch as $size_inch)
                                                <option
                                                    {{ !empty($product) && $product->sizeinch == $size_inch->size ? 'selected' : '' }}
                                                    value="{{ $size_inch->size }}">{{ $size_inch->size }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('size_inch'))
                                            <span class="text-danger">{{ $errors->first('size_inch') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size_cm">Size (Cm)</label>
                                        <select class="form-control" name="size_cm" id="size_cm">
                                            <option value="">Select Size (Cm)</option>
                                            @foreach ($sizes_cm as $size_cm)
                                                <option
                                                    {{ !empty($product) && $product->sizecm == $size_cm->size ? 'selected' : '' }}
                                                    value="{{ $size_cm->size }}">{{ $size_cm->size }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('size_cm'))
                                            <span class="text-danger">{{ $errors->first('size_cm') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                            value="{{ !empty($product->price) ? $product->price : old('price') }}"
                                            placeholder="Enter price">
                                        @if ($errors->has('price'))
                                            <span class="text-danger">{{ $errors->first('price') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discounted_price">Discounted Price</label>
                                        <input type="number" class="form-control" id="discounted_price" name="discounted_price"
                                            value="{{ !empty($product->discounted_price) ? $product->discounted_price : old('discounted_price') }}"
                                            placeholder="Enter discounted price">
                                        @if ($errors->has('discounted_price'))
                                            <span class="text-danger">{{ $errors->first('discounted_price') }}</span>
                                        @endif
                                    </div>
                                </div>
                                {{--  <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            value="{{ !empty($product->quantity) ? $product->quantity : old('quantity') }}"
                                            placeholder="Enter quantity">
                                        @if ($errors->has('quantity'))
                                            <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                        @endif
                                    </div>
                                </div>  --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Enter description"> {{ !empty($product->description) ? $product->description : old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" placeholder="Enter notes"> {{ !empty($product->notes) ? $product->notes : old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="delivery_details">Delivery Details</label>
                                        <textarea class="form-control summernote" id="delivery_details" name="delivery_details"
                                            placeholder="Enter delivery details"> {!! !empty($product->delivery_details) ? $product->delivery_details : old('delivery_details') !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-widget">
                        <div class="card-header">
                            <h3 class="card-title">Painting Images</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.product.productList') }}" class="btn btn-primary pull-right"><i
                                        class="fa fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="main_image">Main Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="main_image"
                                            name="main_image" onchange="preview('main_preview')">
                                        <label class="custom-file-label" for="main_image">Choose file</label>
                                    </div>
                                </div>
                                @if ($errors->has('main_image'))
                                    <span class="text-danger">{{ $errors->first('main_image') }}</span>
                                @endif
                            </div>
                            <img id="main_preview" class="product_image pad"
                                src="{{ isImage('Main', !empty($product->productimage) ? $product->productimage : '') }}"
                                alt="Photo">
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="setup_image">Setup Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="setup_image" name="setup_image"
                                            onchange="preview('image_preview')">
                                        <label class="custom-file-label" for="setup_image">Choose file</label>
                                    </div>
                                </div>
                                @if ($errors->has('setup_image'))
                                    <span class="text-danger">{{ $errors->first('setup_image') }}</span>
                                @endif
                            </div>
                            <img id="image_preview" class="product_image pad"
                                src="{{ isImage('Setups', !empty($product->productsetupimage) ? $product->productsetupimage : '') }}"
                                alt="Photo">

                        </div>
                    </div>
                    <div class="card card-widget">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <select class="form-control" name="is_active" id="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
        function preview(id) {
            document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
        }
        $(function() {
            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
            })
        })
    </script>
@endpush
