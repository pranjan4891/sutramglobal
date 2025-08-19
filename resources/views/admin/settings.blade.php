@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header py-3">
                        <h4 class="card-title mt-1"><i class="fa fa-cog bigfonts"></i> {{ $action . ' ' . $title }}</h4>
                    </div>
                    <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="site_title">Site Title</label>
                                        <input type="text" class="form-control" name="site_title" id="site_title"
                                            value="{{ !empty($setting->site_title) ? $setting->site_title : '' }}"
                                            placeholder="Please enter site title">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                        <label>India Office</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Home Office Email</label>
                                        <input type="text" class="form-control" name="email" id="email"
                                            value="{{ !empty($setting->email) ? $setting->email : '' }}"
                                            placeholder="Please enter email address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Home Office Phone</label>
                                        <input type="number" class="form-control" name="phone" id="phone"
                                            value="{{ !empty($setting->phone) ? $setting->phone : '' }}"
                                            placeholder="Please enter phone">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Home Office Address</label>
                                        <input type="text" class="form-control" name="address" id="address"
                                            placeholder="Please enter address"
                                            value="{{ !empty($setting->address) ? $setting->address : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email2">Other Office Email</label>
                                        <input type="text" class="form-control" name="email2" id="email2"
                                            value="{{ !empty($setting->email2) ? $setting->email2 : '' }}"
                                            placeholder="Please enter email address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone2">Other Office Phone</label>
                                        <input type="number" class="form-control" name="phone2" id="phone2"
                                            value="{{ !empty($setting->phone2) ? $setting->phone2 : '' }}"
                                            placeholder="Please enter phone">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address2">Other Office Address</label>
                                        <input type="text" class="form-control" name="address2" id="address2"
                                            placeholder="Please enter address"
                                            value="{{ !empty($setting->address2) ? $setting->address2 : '' }}">
                                    </div>
                                </div>
                                {{-- <div class="col-md-12">
                                    <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                        <label>Dubai Office</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email3">Email</label>
                                        <input type="text" class="form-control" name="email3" id="email3"
                                            value="{{ !empty($setting->email3) ? $setting->email3 : '' }}"
                                            placeholder="Please enter email address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone3">Phone</label>
                                        <input type="number" class="form-control" name="phone3" id="phone3"
                                            value="{{ !empty($setting->phone3) ? $setting->phone3 : '' }}"
                                            placeholder="Please enter phone">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address3">Address</label>
                                        <input type="text" class="form-control" name="address3" id="address3"
                                            placeholder="Please enter address"
                                            value="{{ !empty($setting->address3) ? $setting->address3 : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email4">Other Office Email</label>
                                        <input type="text" class="form-control" name="email4" id="email4"
                                            value="{{ !empty($setting->email4) ? $setting->email4 : '' }}"
                                            placeholder="Please enter email address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone4">Other Office Phone</label>
                                        <input type="number" class="form-control" name="phone4" id="phone4"
                                            value="{{ !empty($setting->phone4) ? $setting->phone4 : '' }}"
                                            placeholder="Please enter phone">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address4">Other Office Address</label>
                                        <input type="text" class="form-control" name="address4" id="address4"
                                            placeholder="Please enter address"
                                            value="{{ !empty($setting->address4) ? $setting->address4 : '' }}">
                                    </div>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                        <label>Social Links</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="text" class="form-control" name="facebook" id="facebook"
                                            value="{{ !empty($setting->facebook) ? $setting->facebook : '' }}"
                                            placeholder="Please enter facebook link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" class="form-control" name="instagram" id="instagram"
                                            value="{{ !empty($setting->instagram) ? $setting->instagram : '' }}"
                                            placeholder="Please enter instagram link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="youtube">Youtube</label>
                                        <input type="text" class="form-control" name="youtube" id="youtube"
                                            value="{{ !empty($setting->youtube) ? $setting->youtube : '' }}"
                                            placeholder="Please enter youtube link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" class="form-control" name="twitter" id="twitter"
                                            value="{{ !empty($setting->twitter) ? $setting->twitter : '' }}"
                                            placeholder="Please enter twitter link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="google_plus">Google Plus</label>
                                        <input type="text" class="form-control" name="google_plus" id="google_plus"
                                            value="{{ !empty($setting->google_plus) ? $setting->google_plus : '' }}"
                                            placeholder="Please enter google plus link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pinterest">Pinterest</label>
                                        <input type="text" class="form-control" name="pinterest" id="pinterest"
                                            value="{{ !empty($setting->pinterest) ? $setting->pinterest : '' }}"
                                            placeholder="Please enter pinterest link">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="footer_note">Footer Note</label>
                                        <input type="text" class="form-control" name="footer_note" id="footer_note" value="{{ !empty($setting->footer_note) ? $setting->footer_note : '' }}" placeholder="Please enter footer note">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-dark px-4">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <a href="javascript:history.go(-1);" class="btn btn-primary btn-sm float-right">Back</a>
                        <h4 class="card-title mt-1">Logo</h4>
                    </div>
                    <form id="logo-form" action="{{ route('admin.settings.updateLogo') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="header_logo">Header Logo (500x145)</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" onchange="preview('header_logo_preview')"
                                                    class="custom-file-input" id="header_logo" name="header_logo">
                                                <label class="custom-file-label" for="header_logo">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <img id="header_logo_preview" class="img-thumbnail pad" alt="Header Logo"
                                        src="{{ isImage('settings', $setting->header_logo) }}">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="footer_logo">Footer Logo (500x145)</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" onchange="preview('footer_logo_preview')"
                                                    class="custom-file-input" id="footer_logo" name="footer_logo">
                                                <label class="custom-file-label" for="footer_logo">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <img id="footer_logo_preview" class="img-thumbnail pad" alt="Footer Logo"
                                        src="{{ isImage('settings', $setting->footer_logo) }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-dark px-4">Update Logo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('sub-script')
    <script type="text/javascript">
        function preview(id) {
            document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
        }
        $('#settings-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(this);
            clearError(form)
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        window.location.reload();
                    } else {
                        $.each(response.message, function(fieldName, field) {
                            form.find('[name=' + fieldName + ']').addClass('is-invalid');
                            form.find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                        })
                    }
                }
            });
        });
        $('#logo-form').submit(function(e) {
            e.preventDefault();
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
                        window.location.reload();
                    } else {
                        $.each(response.message, function(fieldName, field) {
                            form.find('[name=' + fieldName + ']').addClass('is-invalid');
                            form.find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                        })
                    }
                }
            });
        });
    </script>
@endpush
