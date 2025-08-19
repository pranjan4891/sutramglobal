@extends('web.layout.layout', ['pageTitle' => $title])

@section('contant')
<style>
    /* General container styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .row {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Section title */
    h2.loginggap {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    /* Centered form styles */
    .buttonlogin {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* OTP inputs layout */
    .verification-code--inputs {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
    }

    .verification-code--inputs input {
        width: 45px;
        height: 50px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        color: #333;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
        transition: all 0.3s ease;
    }

    .verification-code--inputs input:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.5);
    }

    /* Submit button styles */
    button.btn-dark {
        background-color: #343a40;
        color: #fff;
        border: none;
        padding: 10px 30px;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button.btn-dark:hover {
        background-color: #23272b;
    }

    /* Timer and Resend OTP Section */
    #timer_section, #resend_section {
        text-align: center;
        margin-top: 10px;
        font-size: 16px;
        color: #333;
    }

    .resend_btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 30px;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .resend_btn:hover {
        background-color: #0056b3;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .buttonlogin {
            padding: 20px;
        }

        .verification-code--inputs input {
            width: 40px;
            height: 45px;
        }

        button.btn-dark, .resend_btn {
            width: 100%;
        }
    }

</style>

<section class="blacksection">
    <div class="container">
        <div class="row"></div>
    </div>
</section>

<!-- Flash Messages -->
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

<section class="my-5">
    <div class="container py-5">
        <div class="row text-center">
            <h2 class="loginggap">OTP Verification</h2>

            <div class="col-md-4"></div>

            <div class="col-md-4 buttonlogin">
                <form id="otp-verification-form" method="POST" action="{{ route('otp.verify') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ Session::get('sessionEmail') }}">
                    <input type="hidden" name="mobile" value="{{ Session::get('sessionMobile') }}">

                    <!-- OTP Input Fields -->
                    <div class="verification-code--inputs">
                        @for ($i = 0; $i < 6; $i++)
                            <input
                                type="text"
                                maxlength="1"
                                class="otp-input"
                                name="otp[]"
                                required
                                aria-label="OTP digit {{ $i + 1 }}"
                            >
                        @endfor
                    </div>

                    <p>
                        OTP sent to
                        <strong>{{ Session::get('sessionEmail') ?: Session::get('sessionMobile') }}</strong>
                    </p>

                    <!-- Timer Section -->
                    <div id="timer_section" class="mt-3">
                        <p>Resend OTP in <span id="timer">02:00</span></p>
                    </div>

                    <!-- Verify OTP Button -->
                    <button type="submit" class="btn btn-dark" id="verify_btn">Verify OTP</button>

                    <!-- Resend OTP Section -->
                    <div id="resend_section" class="mt-3" style="display: none;">
                        <button class="btn btn-dark resend_btn" id="resend_btn">Resend OTP</button>
                    </div>
                </form>
            </div>

            <!-- Hidden Field for Timer -->
            <input type="hidden" id="expire_at" value="{{ Session::get('sessionExpireAt') }}">





            <div class="col-md-4"></div>
        </div>
    </div>
</section>

@endsection

@push('sub-script')
<script type="text/javascript">
    $(document).ready(function () {
        // OTP input field behavior
        $('.otp-input').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            if (this.value.length === this.maxLength) {
                $(this).next('.otp-input').focus();
            }
        }).on('keydown', function (e) {
            if (e.key === 'Backspace' && this.value.length === 0) {
                $(this).prev('.otp-input').focus();
            }
        });

        // Timer logic
        const expireAt = $('#expire_at').val();
        if (expireAt) {
            startTimer(expireAt);
        }

        // Resend OTP button logic
        $('#resend_btn').on('click', function (e) {
            e.preventDefault();

            const email = '{{ Session::get('sessionEmail') }}';
            const mobile = '{{ Session::get('sessionMobile') }}';

            if (email || mobile) {
                resendOtp(email || mobile, email ? 'email' : 'mobile');
            }
        });
    });

    // Function to start the timer
    function startTimer(expireAt) {
        const endTime = Date.parse(expireAt);
        const timerInterval = setInterval(function () {
            const now = new Date().getTime();
            const timeLeft = endTime - now;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                $('#timer_section').hide();
                $('#resend_section').show();
                $('#verify_btn').hide(); // Hide the Verify button
            } else {
                const minutes = Math.floor(timeLeft / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                $('#timer').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
            }
        }, 1000);
    }

    // Resend OTP AJAX call
    function resendOtp(recipient, type) {
        $.ajax({
            type: "POST",
            url: "{{ route('resend_otp') }}",
            data: {
                recipient: recipient,
                type: type,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.status) {
                    alert('OTP resent successfully to ' + recipient);

                    // Reset timer
                    $('#resend_section').hide();
                    $('#timer_section').show();
                    $('#verify_btn').show(); // Show the Verify button again
                    startTimer(response.new_expire_at);
                } else {
                    alert('Error resending OTP. Please try again.');
                }
            },
            error: function () {
                alert('An error occurred while resending OTP.');
            }
        });
    }

</script>


@endpush
