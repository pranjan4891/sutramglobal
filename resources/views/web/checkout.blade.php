@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<section class="blacksection">
   <div class="container">
      <div class="row">
      </div>
   </div>
</section>
<style>
   .pricecheck p{
       width:250px;
   }
   .sidebar-ing {
   position: fixed;
   top: 0;
   right: -620px; /* Hide initially */
   width: 600px;
   height: 100%;
   background-color: #fff;
   box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
   transition: right 0.4s ease;
   transition-duration: 300ms;
   z-index: 1000;
   }
   .sidebar-ing.open {
   left: 0; /* Show sidebar */
   }
   .sidebar-header {
   display: flex;
   justify-content: space-between;
   align-items: center;
   padding: 0px;
   }
   .sidebar-body {
   padding: 15px;
   }
   .close-btn {
   font-size: 40px;
   cursor: pointer;
   color:black;
   background-color: white;
   }
   .newbtn a{
    color: #000000;
    text-decoration: underline;
    font-size: 18px;
   }

   /*css for pop up  coupon  */
    .coupon-popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .coupon-content {
        width: 50%;
    background-color: #fff;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    position: relative;
    }

    .coupon-content h3 {
      margin-top: 0;
      font-size: 18px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
    }

    .close-btn {
      position: absolute;
      top: 0px;
      right: 10px;
      background: none;
      border: none;
      font-size:40px;
      cursor: pointer;
    }

    .coupon-input-wrapper {
      display: flex;
      align-items: center;
      border: 1px solid #ccc;
      border-radius: 5px;
      overflow: hidden;
      margin-bottom: 15px;
    }

    .coupon-input {
      width: 100%;
      padding: 10px;
      border: none;
      outline: none;
    }

    .apply-btn {
      padding: 10px 20px;
      background-color:black;
      color: white;
      border: none;
      cursor: pointer;
      border-left: 1px solid #ccc;
      height: 100%;
    }

    .available-coupons {
      margin-top: 20px;
    }

    .coupon-item {
      border: 1px solid #ccc;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .coupon-details {
      max-width: 70%;
    }

    .coupon-item .apply-btn {
      background-color:black;
      padding: 8px 12px;
    }

    @media (max-width: 480px) {
      .coupon-content {
        width: 95%;
      }

      .coupon-item {
        flex-direction: column;
        align-items: flex-center;
      }

      .coupon-item .apply-btn {
        margin-top: 10px;
        width: 100%;
      }
    }
   /*css for pop up  coupon  */


   @media (max-width: 768px) {
    .pricecheck p {
        width: 200px; /* Adjust width for tablets and small screens */
    }
}

@media (max-width: 576px) {
    .pricecheck p {
        width: 150px; /* Adjust width for mobile phones */
    }
}

@media (max-width: 400px) {
    .pricecheck p {
        width: 100%; /* Take full width for very small devices */
    }
}


</style>
<section class="py-5">
   <div class="container">
   <h2 class="loginggap text-center">CHECKOUT</h2>
   <div class="row">
   <div class="col-md-6 sepcheck">
        <div class="colorbox2 loginbtn text-end">
            <a href="#" onclick="toggleSidebar()">CHOOSE FROM SAVED ADDRESSES</a>
        </div>

        <!-- Address Section -->
        <div id="addressSection">
            <!-- Form for Address Details -->
            <form class="pt-5" id="addressForm" style="display: none;">
                @csrf
                <input type="hidden" name="address_id" id="address_id"> <!-- Hidden field to store address ID -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="name" id="name" placeholder="Full Name" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="state" id="state" placeholder="State" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="city" id="city" placeholder="City" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="pincode" id="pincode" placeholder="Pincode" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="mobile" id="mobile" placeholder="Mobile Number" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="alternate_mobile" id="alternate_mobile" placeholder="Alternate Mobile Number" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="full_address" id="full_address" placeholder="Full Address" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <input type="text" name="landmark" id="landmark" placeholder="Landmark" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="text-end newbtn">
                    <a  href="{{route('checkout.addaddress')}}" class=" pt-2">
                        Add new address
                    </a>
                </div>
                <input type="hidden" name="email" id="email" value="{{Auth::user()->email}}">
                <div class="text-center mt-4 checkbtn">
                    <button type="submit" class="btn btn-dark pt-2">CONTINUE TO PAYMENT</button>
                </div>
            </form>

            <!-- Add Address Button -->
            <div id="addAddressButton" class="text-center my-3" style="display: none;">
                <a href="{{ route('checkout.addaddress') }}" class="btn btn-dark pt-2">Add New Address</a>
            </div>
        </div>
   </div>

   <div class="col-md-5 mx-auto">
    <div id="cart-items-container"></div> <!-- Container for dynamic cart items -->

    <div class="cart-footer">
         <!--pop up code for coupon start -->
         <div class="d-flex spacecart">
            <div class="d-flex ">
                <div class="me-3 coupontitle">
                  <i class="fa-solid fa-money-bill" style="font-size:30px;"></i>
                </div>
                <div class="">Apply Coupon / Gift Card <br><span style="color:gray; font-size:16px;">crazy deals and other amazing offers</span></div>
            </div>
            <div>
               <p id="coupon" style="cursor: pointer; color: black;">View</p>
            </div>
        </div>


        <!--pop up code for coupon end -->





        <hr>
        <div class="d-flex spacecart">
            <div>
                <h6>SUB TOTAL</h6>
            </div>
            <div>
                <p id="subtotal">INR 0</p> <!-- Subtotal amount will be updated dynamically -->
            </div>
        </div>
        {{--<div class="d-flex spacecart">
            <div>
                <h6>DELIVERY CHARGES</h6>
            </div>
            <div>
                <p id="delivery-charges">INR 0</p> <!-- Set a static delivery charge or update dynamically if needed -->
            </div>
        </div>--}}
        <div class="d-flex spacecart">
            <div>
                <h6>DISCOUNTS AMOUNT</h6>
            </div>
            <div>
                <p id="discount-charges">INR 0</p> <!-- Set a static delivery charge or update dynamically if needed -->
            </div>
        </div>
        <div class="d-flex spacecart">
            <div>
                <h4>TOTAL</h4>
            </div>
            <div>
                <p id="total">INR 0</p> <!-- Total amount will be updated dynamically -->
            </div>
        </div>
    </div>
</div>

   <!-- Sidebar -->

   <div class="sidebar-ing" id="addressSidebar">
        <div class="sidebar-header">
            <h5 class="pt-3">Saved Addresses</h5>
            <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        </div>
        <hr>
        <div class="maindivforaddress">
            <!-- Address cards will be dynamically loaded here by JavaScript -->
        </div>
    </div>

</section>
<div class="coupon-popup" id="couponPopup">
    <div class="coupon-content">
        <button class="close-btn" id="closePopup">&times;</button>
        <h3>Coupons & Offers</h3>
        <div class="coupon-input-wrapper">
            <input type="text" class="coupon-input" id="couponInput" placeholder="Enter Coupon Code">
            <button class="apply-btn">APPLY</button>
        </div>

        <div class="available-coupons">
            <h4>Available Coupons</h4>
            <!-- Dynamic coupons will be inserted here -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const couponContainer = document.querySelector('.available-coupons');
        const couponInput = document.getElementById('couponInput'); // Reference to the coupon input field

        // Fetch coupons dynamically using the named route
        fetch('{{ route('coupons.fetch') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(coupons => {
                if (coupons.length === 0) {
                    couponContainer.innerHTML = `<p style="color: red;">No coupons available at the moment.</p>`;
                    return;
                }

                const couponItems = coupons.map(coupon => `
                    <div class="coupon-item">
                        <div class="coupon-details">
                            <strong> ${coupon.code}</strong>
                            <p style="color: green; margin: 5px 0;">${coupon.description}</p>
                            <p>Discount: ${coupon.discount_type === 'percentage' ? coupon.discount_value + '%' : 'Rs.' + coupon.discount_value}</p>
                        </div>
                        <button class="apply-btn" data-code="${coupon.code}">Redeem</button>
                    </div>
                `).join('');

                // Update the DOM
                couponContainer.innerHTML = `<h4>Available Coupons</h4>${couponItems}`;

                // Add click event listeners for Redeem buttons
                const redeemButtons = couponContainer.querySelectorAll('.apply-btn');
                redeemButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const couponCode = this.getAttribute('data-code');
                        couponInput.value = couponCode; // Copy the coupon code to the input field
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching coupons:', error);
                couponContainer.innerHTML = `<p style="color: red;">Failed to load coupons. Please try again later.</p>`;
            });
    });
</script>





@endsection
@push('sub-script')

<script type="text/javascript">
    function toggleSidebar() {
        const sidebar = document.getElementById('addressSidebar');
        sidebar.classList.toggle('open');

        // Load addresses only if the sidebar is opening
        if (sidebar.classList.contains('open')) {
            loadAddresses();
        }
    }

    // Function to load address details from the server
    function loadAddresses() {
        $.ajax({
            url: "{{ route('cart.customer.get-addresses') }}",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const addressContainer = document.querySelector('.maindivforaddress');
                addressContainer.innerHTML = ''; // Clear previous addresses

                if (response.length > 0) {
                    response.forEach(function (address) {
                        const alternateMobile = address.alertnate_mobile ? address.alertnate_mobile : 'N/A'; // Fallback for undefined values

                        const addressHtml = `
                            <div class="card-bodys">
                                <div class="d-flex selectaddreess">
                                    <div>
                                        <h5>${address.name}</h5>
                                    </div>
                                    <div class="colorbox2 loginbtn text-end">
                                        ${address.status === 1 ? '<i>Default</i>' : `<a href="#" onclick="selectAddress(${address.id})">Select</a>`}
                                    </div>
                                </div>
                                <p><b>Mobile</b>: ${address.mobile}, ${alternateMobile}</p>
                                <p>${address.address1}, ${address.city}, ${address.state} - ${address.zip_code}</p>
                                <p><b>Landmark</b>: ${address.address2 ? address.address2 : 'N/A'}</p>
                                <hr>
                            </div>`;

                        addressContainer.insertAdjacentHTML('beforeend', addressHtml);
                    });
                } else {
                    addressContainer.innerHTML = '<p>No saved addresses found.</p>';
                }
            },
            error: function (error) {
                console.error("Error fetching addresses:", error);
            }
        });
    }


    // Function to handle address selection
    function selectAddress(addressId) {
        $.ajax({
            url: "{{route('cart.customer.set-default-address')}}",
            type: 'POST',
            data: {
                address_id: addressId,
                _token: "{{ csrf_token() }}" // CSRF token for secure request
            },
            success: function(response) {
                if (response.success) {
                    loadAddresses(); // Refresh the address list to reflect the updated default address
                    window.requestAnimationFrame(loadDefaultAddress);
                } else {
                    console.error("Failed to update default address:", response.error);
                }
            },
            error: function(error) {
                console.error("Error updating default address:", error);
            }
        });
    }
</script>

<script type="text/javascript">
    // Function to load the default address with status 1
    function loadDefaultAddress() {
        $.ajax({
            url: "{{ route('cart.customer.get-default-address') }}", // Route to get the default address
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.status === 1) {
                    // Show the form and prefill it with the address details
                    document.getElementById('addressForm').style.display = 'block';
                    document.getElementById('addAddressButton').style.display = 'none';

                    document.getElementById('address_id').value = response.id || '';
                    document.getElementById('name').value = response.name || '';
                    document.getElementById('state').value = response.state || '';
                    document.getElementById('city').value = response.city || '';
                    document.getElementById('pincode').value = response.zip_code || '';
                    document.getElementById('mobile').value = response.mobile || '';
                    document.getElementById('alternate_mobile').value = response.alertnate_mobile || '';
                    document.getElementById('full_address').value = response.address1 || '';
                    document.getElementById('landmark').value = response.address2 || '';
                } else {
                    // No default address, show the "Add New Address" button
                    document.getElementById('addressForm').style.display = 'none';
                    document.getElementById('addAddressButton').style.display = 'block';
                }
            },
            error: function(error) {
                console.error("Error fetching default address:", error);
                // In case of error, default to showing the "Add New Address" button
                document.getElementById('addressForm').style.display = 'none';
                document.getElementById('addAddressButton').style.display = 'block';
            }
        });
    }

    // Call the function to load the default address when the page loads
    document.addEventListener('DOMContentLoaded', loadDefaultAddress);
</script>

<script type="text/javascript">
    // Function to load cart items from the server
    function loadCartItems() {
        $.ajax({
            url: "{{ route('cart.get-items') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const cartItemsContainer = document.getElementById('cart-items-container');
                cartItemsContainer.innerHTML = ''; // Clear existing items

                if (response.items && response.items.length > 0) {
                    let subtotal = 0;

                    response.items.forEach(function(item) {
                        subtotal += item.price * item.quantity;

                        const colorSection = (item.colorName && item.colorName !== "N/A") 
                            ? `<p>Color : <span>${item.colorName}</span></p>` 
                            : '';

                        const itemHtml = `
                            <div class="cart-items">
                                <div class="cart-item">
                                    <img src="${item.image_url}" alt="Product Image">
                                    <div class="item-details">
                                        <div class="d-flex spacecart">
                                            <div class="pricecheck">
                                                <p class="item-name">${item.name}</p>
                                            </div>
                                            <div>
                                                ${item.type == '0' ? `<p>INR ${item.price}</p>` : ''}
                                            </div>
                                        </div>
                                        <div class="d-flex spacecart">
                                            ${item.type == '0' ? `
                                            <div>
                                                <p>Size : <span>${item.sizeCode}</span></p>
                                                ${colorSection}
                                            </div>
                                            <div>
                                                <p>Quantity : <span>${item.quantity}</span></p>
                                            </div>` : `
                                            <div>
                                                <p>Free : <span>Gift</span></p>
                                            </div>`}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        cartItemsContainer.insertAdjacentHTML('beforeend', itemHtml);
                    });

                    // Update subtotal and totals
                    document.getElementById('subtotal').textContent = `INR ${subtotal.toFixed(2)}`;
                    document.getElementById('total').textContent = `INR ${subtotal.toFixed(2)}`;
                    const deliveryCharges = 0; // Adjust dynamically if needed
                    document.getElementById('delivery-charges').textContent = `INR ${deliveryCharges.toFixed(2)}`;
                    document.getElementById('discount-charges').textContent = `INR 0.00`;
                    document.getElementById('total').textContent = `INR ${(subtotal + deliveryCharges).toFixed(2)}`;
                } else {
                    cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
                }
            },
            error: function(error) {
                console.error('Error fetching cart items:', error);
            }
        });
    }

    // Call the function to load cart items on page load
    document.addEventListener('DOMContentLoaded', loadCartItems);

    // Coupon Application Logic
    document.addEventListener('DOMContentLoaded', function () {
        const couponContainer = document.querySelector('.available-coupons');
        const couponInput = document.getElementById('couponInput');
        const applyButton = document.querySelector('.apply-btn'); // Apply button in coupon input
        const couponPopup = document.getElementById('couponPopup'); // The coupon popup modal

        // Fetch coupons dynamically
        fetch('{{ route('coupons.fetch') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(coupons => {
                if (coupons.length === 0) {
                    couponContainer.innerHTML = `<p style="color: red;">No coupons available at the moment.</p>`;
                    return;
                }

                const couponItems = coupons.map(coupon => `
                    <div class="coupon-item">
                        <div class="coupon-details">
                            <strong>🔖 ${coupon.code}</strong>
                            <p style="color: green; margin: 5px 0;">${coupon.description}</p>
                        </div>
                        <button class="apply-btn" data-id="${coupon.id}" data-code="${coupon.code}" 
                            data-discount="${coupon.discount_value}" data-type="${coupon.discount_type}">
                            Redeem
                        </button>
                    </div>
                `).join('');

                couponContainer.innerHTML = `<h4>Available Coupons</h4>${couponItems}`;

                // Add event listeners to Redeem buttons
                document.querySelectorAll('.apply-btn[data-code]').forEach(button => {
                    button.addEventListener('click', function () {
                        couponInput.value = this.getAttribute('data-code');
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching coupons:', error);
                couponContainer.innerHTML = `<p style="color: red;">Failed to load coupons. Please try again later.</p>`;
            });

        // Apply coupon logic
        applyButton.addEventListener('click', function () {
            const enteredCoupon = couponInput.value.trim();
            const selectedCoupon = document.querySelector(`.apply-btn[data-code="${enteredCoupon}"]`);

            if (selectedCoupon) {
                const discountType = selectedCoupon.getAttribute('data-type');
                const discountValue = parseFloat(selectedCoupon.getAttribute('data-discount'));
                const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('INR ', ''));
                const couponId = selectedCoupon.getAttribute('data-id');
                const userId = "{{ auth()->id() }}"; // Ensure user ID is accessible

                let discount = 0;
                if (discountType === 'percentage') {
                    discount = (subtotal * discountValue) / 100;
                } else if (discountType === 'fixed') {
                    discount = discountValue;
                }

                discount = Math.round(discount); // Round off discount to the nearest integer

                // Save coupon usage to the database
                $.ajax({
                    url: "{{ route('coupon.uses.save') }}", // Add route to save coupon usage
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}", // Include CSRF token
                        coupon_id: couponId,
                        user_id: userId
                    },
                    success: function(response) {
                        console.log('Coupon usage saved successfully:', response);
                        $("#total").text("INR " + response.remaining_total);
                        $("#discount-charges").text("INR " + response.discount);
                        alert('Coupon applied successfully!');
                    },
                     error: function(error) {
                        console.error('Error saving coupon usage:', error);
                    $("#couponInput").val('');
                        if (error.responseJSON && error.responseJSON.message) {
                            alert(error.responseJSON.message);
                        } else {
                            alert('Failed to apply coupon. Please try again.');
                        }
                    }
                });

                // Close the coupon modal
                couponPopup.style.display = 'none';

            } else {
                alert('Invalid coupon code!');
            }
        });
    });
</script>




<script type="text/javascript">
    document.querySelector('#addressForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this); // Gather form data

        fetch("{{ route('checkout.process') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json",
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to the payment page
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'Something went wrong!');
            }
        })
        .catch(error => {
            console.error('Error processing order:', error);
            alert('An error occurred while processing your order.');
        });
    });
</script>


<!--js for coupon start -->
<script type="text/javascript">
  document.getElementById("coupon").onclick = function() {
    document.getElementById("couponPopup").style.display = "flex";
  };

  document.getElementById("closePopup").onclick = function() {
    document.getElementById("couponPopup").style.display = "none";
  };

  window.onclick = function(event) {
    if (event.target === document.getElementById("couponPopup")) {
      document.getElementById("couponPopup").style.display = "none";
    }
  };

</script>
<!--js for coupon end -->

@endpush
