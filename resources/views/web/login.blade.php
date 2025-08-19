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

<!-- Product section start -->
<section class="my-5">
    <div class="container">
        <div class="row text-center">
            <h2 class="loginggap">LOGIN BY OTP</h2>

            <div class="col-md-4"></div>
            <div class="col-md-4 buttonlogin">
                <div>
                    <form action="{{ route('loginCheck') }}" method="post">
                        @csrf
                        <input id="mobile" type="text" name="mobile" placeholder="Phone Number" required>
                        @error('mobile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <button class=" btn btn-dark" type="submit">Send OTP</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4"></div>

            <!-- Divider with "OR" -->
            <div class="col-md-3"></div>
            <div class="col-md-6 my-4">
                <div class="d-flex align-items-center">
                    <hr class="flex-grow-1">
                    <span class="mx-3">Or</span>
                    <hr class="flex-grow-1">
                </div>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-12">
                <div class="colorbox2 ">
                    <a href="{{ route('loginwithpassword') }}">LOGIN/SIGNUP USING PASSWORD</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('sub-script')
<script type="text/javascript">

</script>
@endpush

<style>
    /* CSS for the button styles */
    .loginbtn {
        display: inline-block;
        background-color: #007bff; /* Blue background */
        color: #fff; /* White text */
        border: none; /* No border */
        padding: 12px 20px; /* Padding for size */
        border-radius: 4px; /* Rounded corners */
        font-size: 16px; /* Font size */
        cursor: pointer; /* Pointer cursor on hover */
        text-align: center; /* Centered text */
        transition: background-color 0.3s ease; /* Transition effect for hover */
        width: 100%; /* Full width */
        text-decoration: none; /* Remove underline */
    }

    .loginbtn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    input[type="email"] {
        width: 100%; /* Full width input */
        padding: 10px; /* Padding for input */
        border: 1px solid #ccc; /* Border style */
        border-radius: 4px; /* Rounded corners */
        margin-bottom: 15px; /* Space below input */
        font-size: 16px; /* Font size */
        outline: none; /* Remove outline */
        transition: border-color 0.3s ease; /* Transition effect for focus */
    }

    input[type="email"]:focus {
        border-color: #007bff; /* Border color on focus */
    }
</style>
