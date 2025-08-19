@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
    .dropdowned-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdowned-content.show {
    display: block;
}

.dropdowned-btn {
    background: none;
    border: none;
    cursor: pointer;
}

</style>
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<div class="container mt-3">
    {{-- @if(Session::has('success'))
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
    @endif --}}
</div>
<!-- contact section start -->
<section class="my-5">
  <div class="container">
    <div class="text-center pb-3">
      <h2>SAVED ADDRESSES</h2>
      <p>Easily manage your addresses: edit, delete, or set a default with a click!</p>
    </div>
    <div class="row">
        @foreach($addresses as $address)
        <div id="address-{{ $address->id }}" class="col-md-6 py-2">
            <div class="card-bodyss profilebody">
                <div class="d-flex threedots">
                    @if($address->is_default)
                        <div>
                            <p class="py-1">Default</p>
                        </div>
                    @else
                        <div class="DOTdrop">
                            @if ($address->status == 1)
                                <a href="Javascript:void(0)">Default</a>
                            @else
                                <a href="{{ route('setDefaultAddress', ['id' => $address->id]) }}">Set as Default</a>
                            @endif
                        </div>
                    @endif
                    <div class="dropdowned">
                        <button onclick="toggleDropdown(event)" class="dropdowned-btn">⋮</button>
                        <div class="dropdowned-content p-3">
                            <div class="DOTdrop">
                                <a href="#" onclick="showEditForm({{ $address->id }})">EDIT</a>
                            </div>
                            <div class="">
                                <a href="#" onclick="deleteAddressdd({{ $address->id }})">DELETE</a>
                            </div>
                        </div>
                    </div>
                </div>
                <h5>{{ $address->name }}</h5>
                <p><b>Mobile</b>: {{ $address->mobile }}{{ $address->alertnate_mobile ? ', ' . $address->alertnate_mobile : '' }}</p>
                <p>{{ $address->address1 }}, {{ $address->city }}, {{ $address->state }} - {{ $address->zip_code }}</p>
                <p><b>Landmark</b>: {{ $address->address2 }}</p>
            </div>
        </div>

        @endforeach



    </div>
    <div class="text-center py-4">
      <a href="{{route('addaddress')}}"><div class="btn btn-dark pt-2">ADD NEW ADDRESS</div></a>
    </div>
  </div>
</section>

<div id="editAddressModal" class="modal" style="display: none;">
    <div class="modal-content new">
      <span class="close-btn" onclick="closeEditForm()">&times;</span>
      <h3>Edit Address</h3>
      <form id="editAddressForm">
        <input type="hidden" name="address_id"> <!-- Hidden field to store address ID -->

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <input type="text" name="name" placeholder="First Name" class="form-control">
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="state" placeholder="State" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="city" placeholder="City" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="pincode" placeholder="Pincode" class="form-control">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" name="mobile" placeholder="Mobile Number" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" name="alternate_mobile" placeholder="Alternate Mobile Number" class="form-control">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <input type="text" name="full_address" placeholder="Full Address" class="form-control">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <input type="text" name="landmark" placeholder="Landmark" class="form-control">
            </div>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-dark pt-2">SAVE ADDRESS</button>
        </div>
      </form>
    </div>
  </div>


<!-- contact section end -->


@endsection
@push('sub-script')
<script>
    // Toggle dropdown menu
    function toggleDropdown(event) {
        // Close all other open dropdowns
        document.querySelectorAll('.dropdowned-content').forEach((dropdown) => {
            if (dropdown !== event.target.closest('.dropdowned').querySelector('.dropdowned-content')) {
                dropdown.classList.remove('show');
            }
        });

        // Toggle the clicked dropdown
        const dropdown = event.target.closest('.dropdowned').querySelector('.dropdowned-content');
        dropdown.classList.toggle('show');
    }

    // Close the dropdown if clicked outside
    document.addEventListener('click', function(event) {
        const isDropdownButton = event.target.closest('.dropdowned-btn');

        // If the click is outside the dropdown or its button, close all dropdowns
        if (!isDropdownButton) {
            document.querySelectorAll('.dropdowned-content').forEach((dropdown) => {
                dropdown.classList.remove('show');
            });
        }
    });


    // Delete address via AJAX
    // function deleteAddressdd(id) {
    //     if (confirm('Are you sure you want to delete this address?')) {
    //         fetch(`address/delete/${id}`, {
    //             method: 'DELETE',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //                 'Content-Type': 'application/json',
    //             },
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 // Display a temporary success message
    //             // alert('Address deleted successfully!');

    //                 // Reload the page after a short delay to give time for the user to see the message
    //                 setTimeout(() => {
    //                     location.reload(); // Reload the page after 1.5 seconds
    //                 }, 1500);
    //             } else {
    //                 alert('Failed to delete the address. Please try again.');
    //             }
    //         })
    //         .catch(error => console.error('Error:', error));
    //     }
    // }

    function deleteAddressdd(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the address.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0B0B0B',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make an AJAX request to delete the address
            fetch(`address/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        confirmButtonColor: '#0B0B0B',
                    });

                    // Remove the deleted address from the DOM
                    document.querySelector(`#address-${id}`).remove();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message,
                        confirmButtonColor: '#d33',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again later.',
                    confirmButtonColor: '#d33',
                });
            });
        }
    });
}




   // Show edit form dynamically with address data
    function showEditForm(id) {
        // Fetch the address data from the server using the ID
        fetch(`address/edit/${id}`)
            .then(response => response.json())
            .then(addressData => {
                // Check if address data exists
                if (!addressData) {
                    alert('No data found for this address.');
                    return;
                }

                // Populate form fields with fetched address data
                document.querySelector('input[name="name"]').value = addressData.name;
             //   document.querySelector('input[name="lastname"]').value = addressData.lastname || '';
                document.querySelector('input[name="state"]').value = addressData.state;
                document.querySelector('input[name="city"]').value = addressData.city;
                document.querySelector('input[name="pincode"]').value = addressData.zip_code;
                document.querySelector('input[name="mobile"]').value = addressData.mobile;
                document.querySelector('input[name="alternate_mobile"]').value = addressData.alertnate_mobile;
                document.querySelector('input[name="full_address"]').value = addressData.address1;
                document.querySelector('input[name="landmark"]').value = addressData.address2 || '';

                // Set the hidden input to store the address ID for the update
                document.querySelector('input[name="address_id"]').value = id;

                // Display the modal
                document.getElementById('editAddressModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching address data:', error);
                alert('Failed to load the address data. Please try again.');
            });
    }

    // Close edit form
    function closeEditForm() {
        document.getElementById('editAddressModal').style.display = 'none';
    }

    // Handle form submission for updating the address using AJAX
    document.getElementById('editAddressForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form from submitting the traditional way

        const addressId = document.querySelector('input[name="address_id"]').value;
        const formData = new FormData(this);

        // Send the form data to the server via AJAX
        fetch(`address/update/${addressId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData)),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Address updated successfully!');
                    location.reload(); // Optionally reload the page to reflect changes
                } else {
                    alert('Failed to update the address. Please try again.');
                }
            })
            .catch(error => console.error('Error updating address:', error));
    });


    </script>
@endpush
