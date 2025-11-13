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
                            <div class="form-group col-md-6">
                                <label for="sort" class=" col-form-label">Sort</label>
                                <div class="">
                                    <input type="number" class="form-control" name="sort" id="sort" placeholder="Sort order"
                                        value="{{ !empty($edit_data)?$edit_data->sort:old('sort') }}">
                                    @if( $errors->has( 'sort' ) )
                                        <span class="text-danger">{{ $errors->first( 'sort' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name" class=" col-form-label">Name</label>
                                <div class="">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                        value="{{ !empty($edit_data)?$edit_data->name:old('name') }}">
                                    @if( $errors->has( 'name' ) )
                                        <span class="text-danger">{{ $errors->first( 'name' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="code" class=" col-form-label">Code</label>
                                <div class="">
                                    <input type="text" class="form-control" name="code" id="code" placeholder="Code"
                                        value="{{ !empty($edit_data)?$edit_data->code:old('code') }}">
                                    @if( $errors->has( 'code' ) )
                                        <span class="text-danger">{{ $errors->first( 'code' ) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status" class=" col-form-label">Status</label>
                                <div class="">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" {{ (!empty($edit_data) && $edit_data->status == '1') ? 'selected' : (old('status') == '1' ? 'selected' : '') }}>Active</option>
                                        <option value="0" {{ (!empty($edit_data) && $edit_data->status == '0') ? 'selected' : (old('status') == '0' ? 'selected' : '') }}>Inactive</option>
                                    </select>
                                    @if( $errors->has( 'status' ) )
                                        <span class="text-danger">{{ $errors->first( 'status' ) }}</span>
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
