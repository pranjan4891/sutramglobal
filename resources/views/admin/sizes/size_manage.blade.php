@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $action . ' ' . $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.sizes') }}" class="btn btn-primary pull-right"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <form id="blogs-form" action="{{ route('admin.sizes.store') }}" method="POST"
                        enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($edit_data)?$edit_data->id:'' }}">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="name" class=" col-form-label">Size</label>
                                <div class="">
                                    <input type="text" class="form-control" name="code" id="code" placeholder="code"
                                        value="{{ !empty($edit_data)?$edit_data->code:old('code') }}">
                                    @if( $errors->has( 'code' ) )
                                        <span class="text-danger">{{ $errors->first( 'code' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="typo" class=" col-form-label">Category</label>
                                <div class="">
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Men" {{ (!empty($edit_data) && $edit_data->category == 'Men') ? 'selected' : (old('category') == 'Men' ? 'selected' : '') }}>Men</option>
                                        <option value="Women" {{ (!empty($edit_data) && $edit_data->category == 'Women') ? 'selected' : (old('category') == 'Women' ? 'selected' : '') }}>Women</option>
                                    </select>
                                    @if( $errors->has( 'category' ) )
                                        <span class="text-danger">{{ $errors->first( 'category' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="typo" class=" col-form-label">Type</label>
                                <div class="">
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1" {{ (!empty($edit_data) && $edit_data->type == '1') ? 'selected' : (old('category') == '1' ? 'selected' : '') }}>Top</option>
                                        <option value="2" {{ (!empty($edit_data) && $edit_data->type == '2') ? 'selected' : (old('category') == '2' ? 'selected' : '') }}>Buttom</option>
                                    </select>
                                    @if( $errors->has( 'category' ) )
                                        <span class="text-danger">{{ $errors->first( 'category' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name" class=" col-form-label">Chest</label>
                                <div class="">
                                    <input type="text" class="form-control" name="chest" id="chest" placeholder="chest"
                                        value="{{ !empty($edit_data)?$edit_data->chest:old('chest') }}">
                                    @if( $errors->has( 'chest' ) )
                                        <span class="text-danger">{{ $errors->first( 'chest' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name" class="col-form-label">Waist</label>
                                <div class="">
                                    <input type="text" class="form-control" name="waist" id="waist" placeholder="waist"
                                        value="{{ !empty($edit_data)?$edit_data->waist:old('waist') }}">
                                    @if( $errors->has( 'waist' ) )
                                        <span class="text-danger">{{ $errors->first( 'waist' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name" class="col-form-label">Lenght</label>
                                <div class="">
                                    <input type="text" class="form-control" name="length" id="length" placeholder="length"
                                        value="{{ !empty($edit_data)?$edit_data->length:old('length') }}">
                                    @if( $errors->has( 'length' ) )
                                        <span class="text-danger">{{ $errors->first( 'length' ) }}</span>
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
    function preview(id) {
        document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
    }
</script>
@endpush
