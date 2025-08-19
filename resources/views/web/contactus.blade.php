@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
    .newsocial a{
        color:black;
    }
    .newsocial img{
            height: 25px;
    margin-bottom: 7px;
}

</style>

<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<!-- contact section start -->
<section class="py-5">
    <div class="container">
        <h3 class="text-center">CONTACT US</h3>
        <div class="row">
            @php
    $settingcontact = \App\Models\Setting::find(1);
@endphp
        <div class="col-md-5 designcard">
                <div class="card">
                    <div class="card-body">
                        <!-- Location -->
                        <div class="card-item social">
                            <h4>Quick Connect</h4>

                            </div>
                            <!-- Phone Number -->
                            <div class="card-item social">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span class="text">Phone : +{{ substr($settingcontact->phone, 0, 2) . ' ' . substr($settingcontact->phone, 2) }}</span>
                            </div>

                            <!-- Email -->
                            <div class="card-item social py-3">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span class="text">Email : {{$settingcontact->email}}</span>
                            </div>
                            <div class="card-item social">
                                <h4>Address</h4>
                                <p>{{$settingcontact->address}}</p>
                            </div>
                            <div class="card-item social">
                                <h6>Support Hours</h6>
                                <p class="m-0">8am - 5pm</p>
                                <span>*Excludes Holidays</span>
                            </div>
                            <div class="card-item social contacticon pt-4">
                                <h6>Social Media</h6>
                                   <div class="newsocial">
                                    <a href="https://www.linkedin.com/company/sutramglobal/?viewAsMember=true"><i class="fa-brands fa-linkedin" aria-hidden="true"></i></a>
                                    <a href="https://www.facebook.com/sutramglobal"><i class="fa-brands fa-facebook" aria-hidden="true"></i></a>
                                    <a href="https://www.youtube.com/@sutramglobal"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
                                    <a href="https://in.pinterest.com/sutramglobalsocial/"><i class="fa-brands fa-pinterest" aria-hidden="true"></i></a>
                                    <a href="https://www.instagram.com/sutramglobal/"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                                    <!--<a href=""><img src="public/img/logo-twiter.png"></a>-->
                                   </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 mt-3">
                <div class="text-center mt-3">
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!-- Warning Message -->
                    @if(session()->has('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <h4>How can we help?</h4>
                <p>Let us know your questions, thoughts and ideas via the form below. Our support team will get back to you as soon as possible.</p>
                <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{ old('phone') }}" required>
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="order-number" placeholder="Order Number" value="{{ old('order-number') }}">
                            @error('order-number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea class="form-control" name="message" placeholder="Write your message here..." rows="4" required>{{ old('message') }}</textarea>
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- reCAPTCHA -->
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

                    <button type="submit" class="btn btn-dark mt-3">Submit</button>
                </form>





            </div>
        </div>
    </div>
</section>
@endsection
@push('sub-script')

<!-- Load Google reCAPTCHA API -->
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>

<script>
    // Ensure the SITE_KEY is being passed correctly
    grecaptcha.ready(function () {
        grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', { action: 'contact' })
            .then(function (token) {
                document.getElementById('g-recaptcha-response').value = token;
            }).catch(function (error) {
                console.error('reCAPTCHA Error:', error);
            });
    });
</script>

@endpush
