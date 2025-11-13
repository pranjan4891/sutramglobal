@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $action . ' ' . $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.size_guiders') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <form id="size-guider-form" action="{{ route('admin.size_guiders.store') }}" method="POST" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($edit_data)?$edit_data->id:'' }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="cat_id" class="col-form-label">Category <span class="text-danger">*</span></label>
                                <div class="">
                                    <select name="cat_id" id="cat_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (!empty($edit_data) && $edit_data->cat_id == $category->id) ? 'selected' : (old('cat_id') == $category->id ? 'selected' : '') }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('cat_id'))
                                        <span class="text-danger">{{ $errors->first('cat_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sub_cat_id" class="col-form-label">Sub Category</label>
                                <div class="">
                                    <select name="sub_cat_id" id="sub_cat_id" class="form-control">
                                        <option value="">Select Sub Category</option>
                                        @if(!empty($subcategories))
                                            @foreach($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" {{ (!empty($edit_data) && $edit_data->sub_cat_id == $subcategory->id) ? 'selected' : (old('sub_cat_id') == $subcategory->id ? 'selected' : '') }}>{{ $subcategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('sub_cat_id'))
                                        <span class="text-danger">{{ $errors->first('sub_cat_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="size_id" class="col-form-label">Size <span class="text-danger">*</span></label>
                                <div class="">
                                    <select name="size_id" id="size_id" class="form-control" required>
                                        <option value="">Select Size</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" {{ (!empty($edit_data) && $edit_data->size_id == $size->id) ? 'selected' : (old('size_id') == $size->id ? 'selected' : '') }}>{{ $size->name }} ({{ $size->code }})</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('size_id'))
                                        <span class="text-danger">{{ $errors->first('size_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="chest" class="col-form-label">Chest</label>
                                <div class="">
                                    <input type="number" step="0.01" class="form-control" name="chest" id="chest" placeholder="Chest measurement"
                                        value="{{ !empty($edit_data)?$edit_data->chest:old('chest') }}">
                                    @if($errors->has('chest'))
                                        <span class="text-danger">{{ $errors->first('chest') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="length" class="col-form-label">Length</label>
                                <div class="">
                                    <input type="number" step="0.01" class="form-control" name="length" id="length" placeholder="Length measurement"
                                        value="{{ !empty($edit_data)?$edit_data->length:old('length') }}">
                                    @if($errors->has('length'))
                                        <span class="text-danger">{{ $errors->first('length') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="shoulder" class="col-form-label">Shoulder</label>
                                <div class="">
                                    <input type="number" step="0.01" class="form-control" name="shoulder" id="shoulder" placeholder="Shoulder measurement"
                                        value="{{ !empty($edit_data)?$edit_data->shoulder:old('shoulder') }}">
                                    @if($errors->has('shoulder'))
                                        <span class="text-danger">{{ $errors->first('shoulder') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sleeve" class="col-form-label">Sleeve</label>
                                <div class="">
                                    <input type="number" step="0.01" class="form-control" name="sleeve" id="sleeve" placeholder="Sleeve measurement"
                                        value="{{ !empty($edit_data)?$edit_data->sleeve:old('sleeve') }}">
                                    @if($errors->has('sleeve'))
                                        <span class="text-danger">{{ $errors->first('sleeve') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="waist" class="col-form-label">Waist</label>
                                <div class="">
                                    <input type="number" step="0.01" class="form-control" name="waist" id="waist" placeholder="Waist measurement"
                                        value="{{ !empty($edit_data)?$edit_data->waist:old('waist') }}">
                                    @if($errors->has('waist'))
                                        <span class="text-danger">{{ $errors->first('waist') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group row text-right">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('sub-script')
<script>
    $(document).ready(function() {
        $('#cat_id').change(function() {
            var cat_id = $(this).val();
            if (cat_id) {
                $.ajax({
                    url: '{{ route("admin.size_guiders.getSubCategories") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cat_id: cat_id
                    },
                    success: function(data) {
                        $('#sub_cat_id').html('<option value="">Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $('#sub_cat_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#sub_cat_id').html('<option value="">Select Sub Category</option>');
            }
        });
    });
</script>
@endpush
