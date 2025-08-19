@extends('web.layout.layout', ['pageTitle' => $title])

@section('contant')
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
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<section class="my-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 offset-md-3 buttonlogin2">
                <h2 class="loginggap">Forgot Password</h2>
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <input id="email" type="email" name="email" placeholder="Enter your email" required><br><br>
                    <button type="submit" class="btn btn-dark">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
