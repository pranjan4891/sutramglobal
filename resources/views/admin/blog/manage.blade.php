@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $action . ' ' . $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.blogs') }}" class="btn btn-primary pull-right"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <form id="blogs-form" action="{{ route('admin.blog.store') }}" method="POST"
                        enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="slug" id="slug" value="{{ !empty($edit_data)?$edit_data->slug:'' }}">
                        <div class="form-group row">
                            <label for="title" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                                    value="{{ !empty($edit_data)?$edit_data->title:old('title') }}">
                                @if( $errors->has( 'title' ) )
                                <span class="text-danger">{{ $errors->first( 'title' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select name="category_id" id="category_id" class="form-control custom-select">
                                    <option value="">Category</option>
                                    @foreach ($blogCategory as $category)
                                        <option {{ !empty($edit_data) && $edit_data->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @if( $errors->has( 'category_id' ) )
                                <span class="text-danger">{{ $errors->first( 'category_id' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="image" class="col-sm-2 col-form-label">Banner Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="banner_image" id="banner_image">
                                @if( $errors->has( 'banner_image' ) )
                                <span class="text-danger">{{ $errors->first( 'banner_image' ) }}</span>
                                @endif
                                @if(!empty($edit_data))
                                <div class="text-center p-2">
                                    <img src="{{ isImage('coded-blog' , $edit_data->banner_image) }}" alt="image"
                                        style="width:60%;height:250px;" />
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="short_description" class="col-sm-2 col-form-label">Short Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="short_description" id="short_description"
                                    placeholder="Short Description">{{ !empty($edit_data)?$edit_data->short_description:old('short_description') }}</textarea>
                                @if( $errors->has( 'short_description' ) )
                                <span class="text-danger">{{ $errors->first( 'short_description' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-2 col-form-label">Full Description</label>
                            <div class="col-sm-10">
                                <textarea class="summernote" name="description" id="description"
                                    placeholder="Full Description">{{ !empty($edit_data)?$edit_data->description:old('description') }}</textarea>
                                @if( $errors->has( 'description' ) )
                                <span class="text-danger">{{ $errors->first( 'description' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="meta_title" class="col-sm-2 col-form-label">Meta Title</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="meta_title" id="meta_title"
                                    placeholder="Meta Title">{{ !empty($edit_data)?$edit_data->meta_title:old('meta_title') }}</textarea>
                                @if( $errors->has( 'meta_title' ) )
                                <span class="text-danger">{{ $errors->first( 'meta_title' ) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="meta_description" class="col-sm-2 col-form-label">Meta Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="meta_description" id="meta_description"
                                    placeholder="Meta Description">{{ !empty($edit_data)?$edit_data->meta_description:old('meta_description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mete_keyword" class="col-sm-2 col-form-label">Meta Keywords</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="mete_keyword" id="mete_keyword"
                                    placeholder="Meta Keywords">{{ !empty($edit_data)?$edit_data->mete_keyword:old('mete_keyword') }}</textarea>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label for="tag" class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="tag"
                                    id="tag"><?php //echo !empty($edit_data)?$edit_data->tag:'';?></textarea>
                            </div>
                        </div> --}}

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-2">
                                <div class="form-group mt-2 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="is_active"
                                            name="is_active" value="1" {{
                                            !empty($edit_data)&&$edit_data->is_active=="1"?'checked':'' }}>
                                        <label for="is_active" class="custom-control-label">Is Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group mt-2 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="is_home" name="is_home"
                                            value="1" {{ !empty($edit_data)&&$edit_data->is_home=="1"?'checked':'' }}>
                                        <label for="is_home" class="custom-control-label">Is Home</label>
                                    </div>
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
    $(function() {
		$('.summernote').summernote({height:300,
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