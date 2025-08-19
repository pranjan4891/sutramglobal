@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $action . ' ' . $title }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.pages') }}" class="btn btn-primary pull-right"><i
                                    class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.page.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($edit_data) ? $edit_data->id : '' }}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Page Title</label>
                                        <input type="text" class="form-control" name="title" id="title"
                                            placeholder="Title"
                                            value="{{ !empty($edit_data) ? $edit_data->title : old('title') }}">
                                        @if ($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="summernote" name="description" id="description" placeholder="Description">{{ !empty($edit_data) ? $edit_data->description : old('description') }}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="text-danger">{{ $errors->first('description') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row text-center">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
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
        $(function() {
            $('.summernote').summernote({
                height: 500,
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
