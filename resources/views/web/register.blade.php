@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
@media (max-width: 576px) {
.forgettext a {
    color: rgb(0, 0, 0);
    text-decoration: none;
    padding-right: 200px;
    padding-top: 10px;
}
.buttonlogin3 .btn {
    border-radius: 0;
    padding: 10px 120px !important;
}
.loginggap {
    margin-bottom: 0px;
}
.singupinlogin {
    border-left: 1px solid black;
    padding-left: 14px;
    padding-top: 30px;
}
.input-group-append2 {
    position: absolute;
    top: 418px;
    left: 332px;
    transform: translateY(-50%);
    cursor: pointer;
}
.input-group-append3 {
    position: absolute;
    top: -60px;
    left: 320px;
    transform: translateY(-50%);
    cursor: pointer;
    }
     }
</style>
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<div class="container mt-3">
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
<!-- product section start -->
<section class="my-5">
    <div class="container py-5">
        <h2 class="loginggap text-center">REGISTRATION</h2>
        <div class="row">

            <div class="col-md-6 buttonlogin3">
                <div>
                    <form action="{{ route('user_store') }}" method="POST">
                        @csrf

                        <!-- First Name Field -->
                        <input id="firstname" type="text" name="name" placeholder="First Name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <!-- Last Name Field -->
                        <input id="lastname" type="text" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}" required>
                        @error('lastname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <!-- Phone Number Field -->
                        <input id="phone" type="tel" name="phone" placeholder="Mobile Number" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <!-- Email Field -->
                        <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <!-- Password Field -->
                        <input id="password" type="password" name="password" placeholder="Password" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <!-- Password Confirmation Field -->
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Re-enter Password" required>
                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <!-- Password visibility toggle -->
                        <div class="input-group">
                            <div class="input-group-append3" onclick="togglePassword()">
                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                            </div>
                        </div>

                        <div class="input-group2">
                            <div class="input-group-append2" onclick="togglePassword2()">
                                <i class="fa fa-eye" id="togglePasswordIcon2"></i>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-dark mt-2">REGISTER</button>
                    </form>

                    <!-- Display all errors at once (optional) -->
                    @if($errors->any())
                        <div class="alert alert-danger mt-2">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>


            </div>
            <div class="col-md-6 singupinlogin">
                 <h2 class="">Already  Registered?</h2>
                 <p>Get a faster checkout, exclusive member benefits and the latest news, offers and inspiration.</p>

                <hr>
                <a href="{{route('weblogin')}}"><div class="btn btn-dark pt-2">LOGIN</div></a>
            </div>
            <!-- Divider with "OR" -->

        </div>
    </div>
</section>

@endsection
@push('sub-script')
<script type="text/javascript">

    function togglePassword() {
        var passwordField = document.getElementById('password');
        var icon = document.getElementById('togglePasswordIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function togglePassword2() {
        var passwordField = document.getElementById('password_confirmation');
        var icon = document.getElementById('togglePasswordIcon2');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>


@endpush
