@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $action . ' ' . $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.companies') }}" class="btn btn-primary pull-right"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <form id="blogs-form" action="{{ route('admin.company.store') }}" method="POST"
                        enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($edit_data)?$edit_data->id:'' }}">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                    value="{{ !empty($edit_data)?$edit_data->name:old('name') }}">
                                @if( $errors->has( 'name' ) )
                                    <span class="text-danger">{{ $errors->first( 'name' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="typo" class="col-sm-2 col-form-label">Typo</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="typo" id="typo" placeholder="Typo"
                                    value="{{ !empty($edit_data)?$edit_data->typo:old('typo') }}">
                                @if( $errors->has( 'typo' ) )
                                    <span class="text-danger">{{ $errors->first( 'typo' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Logo</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" onchange="preview('image_preview')"
                                            class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div>
                                @if( $errors->has( 'image' ) )
                                    <span class="text-danger">{{ $errors->first( 'image' ) }}</span>
                                @endif
                                <div class="py-2">
                                    <img id="image_preview" class="img-thumbnail" src="{{ isImage('companies' , !empty($edit_data)?$edit_data->image:'') }}" alt="image"
                                        style="width:150px;height:auto;" />
                                </div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="about" class="col-sm-2 col-form-label">About</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="about" id="about"
                                    placeholder="About">{{ !empty($edit_data)?$edit_data->about:old('about') }}</textarea>
                                @if( $errors->has( 'about' ) )
                                    <span class="text-danger">{{ $errors->first( 'about' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="address" id="address"
                                    placeholder="Address">{{ !empty($edit_data)?$edit_data->address:old('address') }}</textarea>
                                @if( $errors->has( 'address' ) )
                                    <span class="text-danger">{{ $errors->first( 'address' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="status" id="status">
                                    <option value="1" {{ !empty($edit_data) && $edit_data->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !empty($edit_data) && $edit_data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
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
