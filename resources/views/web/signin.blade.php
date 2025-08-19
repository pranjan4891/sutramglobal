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
.buttonlogin2 .btn {
    border-radius: 0;
    padding: 15px 145px !important;
}
.loginggap {
    margin-bottom: 0px;
}
.singupinlogin {
    border-left: 1px solid black;
    padding-left: 14px;
    padding-top: 30px;
}
.input-group-append {
    position: absolute;
    top: -16px;
    left: 310px;
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
    <div class="row ">
            <div class="col-md-6  buttonlogin2">
                <h2 class="loginggap text-center">LOGIN</h2>
                    <div>
                        <form action="{{ route('loginpass') }}" method="POST">
                            @csrf
                            <input id="email" type="email" name="email" placeholder="Email" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input id="password" type="password" name="password" placeholder="Password" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-append" onclick="togglePassword()">
                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                </div>
                            </div>
                            <div class="mt-2 forgettext">
                                <a href="{{route('password.request')}}">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn btn-dark">LOGIN</button> <!-- Use button for submission -->
                        </form>

                    </div>

            </div>
            <div class="col-md-6 singupinlogin">
                 <h2 class="">Create an account</h2>
                 <p>Get a faster checkout, exclusive member benefits and the latest news, offers and inspiration.</p>

                <hr>
                <a href="{{route('webregister')}}"><div class="btn btn-dark pt-2">SIGN UP</div></a>
            </div>
            </div>

    </div>
</div>
</section>


@endsection

@push('sub-script')
<script type="text/javascript">
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('togglePasswordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }
</script>
@endpush
