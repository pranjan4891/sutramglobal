@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<section class="contact-form-section py-5">
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
        <h2 class="text-center pb-5">ADD NEW ADDRESS</h2>
        <form action="{{ route('checkout.address.store') }}" method="POST">
            @csrf
            <!-- Include CSRF token for security -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="First Name" class="form-control" value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="lastname" placeholder="Last Name" class="form-control" value="{{ old('lastname') }}">
                    </div>
                    @error('lastname')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if(auth()->check() && auth()->user()->email == null)
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="state" placeholder="State" class="form-control" value="{{ old('state') }}">
                    </div>
                    @error('state')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="city" placeholder="City" class="form-control" value="{{ old('city') }}">
                    </div>
                    @error('city')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="pincode" placeholder="Pincode" class="form-control"    value="{{ old('pincode') }}">
                    </div>
                    @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="mobile" placeholder="Mobile Number" class="form-control"    value="{{ old('mobile') }}">
                    </div>
                    @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="alternate_mobile" placeholder="Alternate Mobile Number" class="form-control"    value="{{ old('alternate_mobile') }}">
                    </div>
                    @error('alternate_mobile')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="full_address" placeholder="Full Address" class="form-control"    value="{{ old('full_address') }}">
                    </div>
                    @error('full_address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" name="landmark" placeholder="Landmark" class="form-control"    value="{{ old('landmark') }}">
                    </div>
                    @error('landmark')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-dark pt-2">SAVE ADDRESS</button>
            </div>
        </form>

  </div>
        </div>
    </div>
</div>
</section>

@endsection
@push('sub-script')

@endpush
