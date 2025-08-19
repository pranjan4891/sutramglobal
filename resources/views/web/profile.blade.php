@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<style>
    .modal-overlaych {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-contentch {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 100%;
        max-width: 500px;
        position: relative;
    }

    .modal-headerch {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-bodych {
        margin-bottom: 20px;
    }

    .close-btnch {
        cursor: pointer;
        font-size:40px;
    }
    .password-toggle {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
}
.password-toggle i {
    font-size: 18px;
}
</style>
<!-- profile section start -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <h2 class="text-center">MY PROFILE</h2>
            <div class="col-md-6">
                <div class="profileImage mb-4">
                    <div class="me-3 imagediv">
                        @if (Auth::user()->profile_photo == null)
                            <img src="{{asset('public/web/images/wishlist.png')}}" alt="Customer Image" class="img-fluid" style="height:200px;">
                        @else
                            <img src="{{isImage('profile_photos', Auth::user()->profile_photo)}}" alt="Customer Image" class="img-fluid" style="height:200px;">
                        @endif

                    </div>
                    <div class="review-details text-start mx-4">
                        <div class="titles pt-3">
                            <p class="m-0">Welcome!</p>
                            <h5 class="card-title pb-3">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</h5>
                            <p>{{Auth::user()->c_code.' '.Auth::user()->mobile}}</p>
                            <p>{{Auth::user()->email}}</p>
                            <p class="">{{Auth::user()->sex}}</p>
                            <div class="d-flex titles">
                                <!-- EDIT PROFILE button triggers JS pop-up -->
                                <div class="titles colorbox2" style="margin-right: 15px;">
                                    <a href="#" id="editProfileBtnch">
                                        <p>EDIT&nbsp;PROFILE</p>
                                    </a>
                                </div>
                                <!-- CHANGE PASSWORD button triggers JS pop-up -->
                                <div class="colorbox2 d-flex">
                                    <a href="#" id="changePasswordBtnch">
                                        <p>{{ Auth::user()->password ? 'CHANGE PASSWORD' : 'ADD PASSWORD' }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="signout text-end">
                    <a href="{{route('logout')}}" ><div class="btn btn-outline-dark fs-6">SIGN OUT</div></a>
                </div>
            </div>
        </div>
    </div>
    <div id="popupContainerch"></div>
</section>
<!-- profile section end -->
<!-- contact section start -->
<section class="mb-5">
    <div class="container">
    <div class="row">
            <div class="col-md-4 pro">
            <a href="{{route('address')}}"><div class="card-bodyss  profilebody text-center">
                  <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <h5>SAVED ADDRESSES</h5>
                      <p>Save your address once, and shop effortlessly every time !</p>
                  </div></a>
            </div>
            <div class="col-md-4  pro">
            <a href="{{route('order.list')}}"><div class="card-bodyss profilebody text-center">
                  <i class="fa fa-gift" aria-hidden="true"></i>
                      <h5>MY ORDERS</h5>
                      <p>Order now for premium quality products and exceptional service, guaranteed</p>
                  </div></a>
            </div>
            <div class="col-md-4 pro ">
            <a href="{{route('contact')}}"><div class="card-bodyss profilebody text-center">
                  <i class="fa fa-phone" aria-hidden="true"></i>
                      <h5>CONTACT US</h5>
                      <p>We’d love to hear from you <br>contact us !</p>
                  </div></a>
            </div>
        </div>
    </div>
</section>

@endsection
@push('sub-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editProfileBtnch = document.getElementById('editProfileBtnch');
        const changePasswordBtnch = document.getElementById('changePasswordBtnch');
        const popupContainerch = document.getElementById('popupContainerch');

        let currentModalch = null;  // Store a reference to the current modal for easy removal

        // Function to create and show modal
        function showModalch(title, content) {
            const modalch = document.createElement('div');
            modalch.classList.add('modal-overlaych');

            modalch.innerHTML = `
                <div class="modal-contentch">
                    <div class="modal-headerch">
                        <h5>${title}</h5>
                        <span class="close-btnch">&times;</span>
                    </div>
                    <div class="modal-bodych">
                        ${content}
                    </div>
                </div>
            `;

            // Close functionality for modal
            modalch.querySelectorAll('.close-btnch').forEach(btn => {
                btn.addEventListener('click', function() {
                    modalch.remove();
                    currentModalch = null; // Reset the current modal reference
                });
            });

            // Remove any existing modals before adding the new one
            if (currentModalch) {
                currentModalch.remove();
            }

            popupContainerch.appendChild(modalch);
            currentModalch = modalch;  // Store reference to the current modal
        }

        // Handle "Edit Profile" button click
        editProfileBtnch.addEventListener('click', function (e) {
            e.preventDefault();
            const editProfileContentch = `
            <form id="editProfileFormch" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="text" class="form-control" id="profileNamech" value="{{Auth::user()->first_name}}" placeholder="First Name">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="profileNamech1" value="{{Auth::user()->last_name}}" placeholder="Last Name">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="profilePhonech" value="{{Auth::user()->mobile}}" placeholder="Phone">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" id="profileEmailch" value="{{Auth::user()->email}}" placeholder="Email">
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Gender</label>
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" id="maleCheckch" name="genderch" value="Male" {{Auth::user()->sex === 'Male' ? 'checked' : ''}}>
                            <label class="form-check-label" for="maleCheckch">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="femaleCheckch" name="genderch" value="Female" {{Auth::user()->sex === 'Female' ? 'checked' : ''}}>
                            <label class="form-check-label" for="femaleCheckch">Female</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="date" class="form-control" id="profileDobch" value="{{Auth::user()->dob}}" placeholder="Date of Birth">
                </div>
                <!-- Profile Photo Upload -->
                <div class="mb-3">
                    <label for="profilePhotoch" class="form-label">Upload Profile Photo</label>
                    <input type="file" class="form-control" id="profilePhotoch" name="profile_photo">
                </div>

                <div class="text-center colorbox2">
                    <button type="submit" class="btn btn-dark">UPDATE</button>
                </div>
            </form>
            `;
            showModalch('UPDATE PROFILE', editProfileContentch);

            // Handle form submission
            document.getElementById('editProfileFormch').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData();
                formData.append('firstname', document.getElementById('profileNamech').value);
                formData.append('lastname', document.getElementById('profileNamech1').value);
                formData.append('phone', document.getElementById('profilePhonech').value);
                formData.append('email', document.getElementById('profileEmailch').value);
                formData.append('gender', document.querySelector('input[name="genderch"]:checked').value);
                formData.append('dob', document.getElementById('profileDobch').value);

                formData.append('profile_photo', document.getElementById('profilePhotoch').files[0]);

                fetch('home/updateProfile', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Profile updated successfully!');
                        currentModalch.remove(); // Close modal on success
                        currentModalch = null; // Clear modal reference
                        window.location.reload(); // Reload page after successful update
                    } else {
                        alert('Profile update failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });


        const userHasPassword = "{{ Auth::user()->password ? 'true' : 'false' }}";

        // Handle "Change Password" button click
        document.getElementById('changePasswordBtnch').addEventListener('click', function (e) {
            e.preventDefault();

            const changePasswordContentch = `
                <form id="changePasswordFormch">
                    ${userHasPassword === 'true' ? `
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="currentPasswordch" placeholder="Current Password">
                        <span class="password-toggle" onclick="togglePassword('currentPasswordch', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>` : ''}
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="newPasswordch" placeholder="New Password">
                        <span class="password-toggle" onclick="togglePassword('newPasswordch', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="confirmNewPasswordch" placeholder="Confirm Password">
                        <span class="password-toggle" onclick="togglePassword('confirmNewPasswordch', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-dark">SUBMIT</button>
                    </div>
                </form>
            `;

            showModalch(userHasPassword === 'true' ? 'CHANGE PASSWORD' : 'ADD PASSWORD', changePasswordContentch);

            document.getElementById('changePasswordFormch').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = {
                    currentPassword: document.getElementById('currentPasswordch')?.value || '',
                    newPassword: document.getElementById('newPasswordch').value,
                    confirmNewPassword: document.getElementById('confirmNewPasswordch').value,
                };

                fetch('home/changePassword', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(userHasPassword === 'true' ? 'Password changed successfully.' : 'Password set successfully.');
                        currentModalch.remove(); // Close modal
                        currentModalch = null;
                    } else {
                        let errorMessage = 'Password update failed.';
                        if (data.errors) {
                            errorMessage = data.errors.join('\n');
                        }
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });


    });
</script>
<script>
    function togglePassword(fieldId, toggleIcon) {
        const inputField = document.getElementById(fieldId);
        const icon = toggleIcon.querySelector("i");

        if (inputField.type === "password") {
            inputField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            inputField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

@endpush

