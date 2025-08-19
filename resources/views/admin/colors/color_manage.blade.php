@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $action . ' ' . $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.colors') }}" class="btn btn-primary pull-right"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <form id="blogs-form" action="{{ route('admin.colors.store') }}" method="POST"
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
                            <label for="typo" class="col-sm-2 col-form-label">Code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="code" id="typo" placeholder="code"
                                    value="{{ !empty($edit_data)?$edit_data->code:old('code') }}">
                                @if( $errors->has( 'code' ) )
                                    <span class="text-danger">{{ $errors->first( 'code' ) }}</span>
                                @endif
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
