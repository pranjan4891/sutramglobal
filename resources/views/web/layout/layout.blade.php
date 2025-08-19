@php
$setting = \App\Models\Setting::find(1);
@endphp
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-----------------REQURIED META TAGS START-------------------->
      <meta charset="UTF-8">
      <link rel="icon" type="image/png" href="{{asset('public/img/FaviconIcon.jpg')}}">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-----------------REQURIED META TAGS END-------------------->
      <!------bootstrap cdn----->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
      <!------style css----->
      <link rel="stylesheet" href="{{ asset('public/web') }}/CSS/style.css">
      <!------font Awesome----->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
      <link
         rel="stylesheet"
         href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
         />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
      <title>Sutram Global | {{ $pageTitle }}</title>
      <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NY84H0BP9Z"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NY84H0BP9Z');
</script>
   </head>
   <!-- Cart Sidebar -->
   <div class="cart-sidebar" id="cartSidebar">
      <div class="cart-header">
         <span class="cart-title">My Cart</span>
         <span class="close-btn" id="closeCartBtn">&times;</span>
      </div>
      <div class="cart-items" id="cartItemsContainer">
         <!-- Cart items will be dynamically loaded here -->
      </div>
      <div class="cart-footer">
         <div class="d-flex spacecart">
            <div class="py-2">
               <p>TOTAL ITEMS</p>
            </div>
            <div>
               <p class="pt-2" id="totalItems">0</p>
            </div>
         </div>
         <div class="d-flex spacecart">
            <div>
               <h6>SUB TOTAL</h6>
            </div>
            <div id="subtotalAmount">INR 0</div>
         </div>
         <p class="py-3" style="font-size:11px;">Shipping, taxes, and discount codes calculated at checkout.</p>
         <button class="checkout-btn" style="display: none;" onclick="proceedToCheckout()">
         <a href="{{ route('cart.checkout') }}">Proceed to Checkout</a>
         </button>
      </div>
   </div>
   <style>
      .navbar-dark .navbar-brand {
      padding-top: 9px;
      color: #fff;
      }
      .fixed-header {
      position: fixed;
      top: 0;
      width: 100%;
      background: #fff; /* Change background as needed */
      box-shadow:0 2px 5px rgb(0 0 0 / 48%);
      z-index:999;
      }
      /* Make header fixed at the top */
      .fixed-top {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1030;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      }
      /* Style when header is scrolled */
      #site-header.scrolled {
      background-color: white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }
      /* Change the color of links and logo after scroll */
      #site-header.scrolled .navbar .nav-link {
      color: black;
      }
      #site-header.scrolled .navbar-brand img {
      filter: none;
      }
      #site-header.scrolled .search-box input {
      background-color: #f8f9fa;
      }
      /* Reset margin and padding for the nav list */
      .list ul {
      margin: 0;
      padding: 0;
      list-style: none;
      }
      .list ul li {
      margin-right: 20px;
      cursor: pointer;
      }
      #site-header {
      position: fixed;
      top: 0px;
      left: 0;
      right: 0;
      z-index: 10;
      background-color: transparent;
      transition: all 0.3s ease-in-out;
      padding: 0px 0;
      }
      /* Change the color of the logo after scroll */
      #site-header.scrolled #logo-img {
      content: url('{{ asset('public/web') }}/images/SutramBlack.png'); /* Change the logo to the black version */
      }
      /* Change the text color of the second navbar */
      #site-header.scrolled .list li {
      color: black !important;
      }
      #site-header.scrolled .navbar-nav .nav-link {
      color: black !important;
      }
      /* header closed */
      .red-heart {
      color: red;
      }
      #site-header.scrolled #btn-search {
      color: black;
      border: 0px solid black  !important;
      }
      #site-header.scrolled #btn-search:hover{
      color: black !important;
      border: 0px solid white  !important;
      }
      #site-header #btn-search {
      margin-top: 5px;
      color: white;"
      border: 0px solid white !important;
      }
      #site-header #btn-search:hover {
      color: black;
      background-color: white !important;
      border: 0px solid black !important;
      }
      .hamburger {
      font-size: 1.5rem;
      cursor: pointer;
      }
      .navbackground {
      background-color:black; /* Matches navbar-dark theme */
      }
      .nav-link {
      color: white;
      transition: color 0.3s;
      }
      .nav-link:hover {
      color: #007bff;
      }
      .offcanvas {
      position: fixed;
      bottom: 0;
      z-index: 1045;
      display: flex;
      flex-direction: column;
      max-width: 85%;
      visibility: hidden;
      background-color: black;
      background-clip: padding-box;
      outline: 0;
      transition: transform .3s ease-in-out;
      }
      @media (max-width: 576px) {
      #site-header.scrolled .list li {
      color: white !important;
      }
      #site-header.scrolled .navbar-nav .nav-link {
      color: white !important;
      }
      .list {
      margin-left: 42px;
      margin-top: -12px;
      font-size: 11px;
      }
      #site-header {
      position: fixed;
      top: -18px;
      left: 0;
      right: 0;
      z-index: 10;
      background-color: transparent;
      transition: all 0.3s ease-in-out;
      padding: 0px 0;
      }
      .logo img {
      height: 25px;
      width: 150px;
      }
      .imagefot {
      padding-left: 11px;
      margin-top:10px;
      }
      .shop {
      padding-left: 11px;
      margin-top:10px;
      }
      .aboutfot {
      padding-left: 11px;
      margin-top:10px;
      }
      .customer{
      padding-left: 11px;
      margin-top:10px;
      }
      .contatfot{
      margin-top:10px;
      }
      .navbackground{
      background-color:#0000007a;
      padding:20px;
      padding-bottom:10px;
      padding-top:10px;
      }
      }
      #toast-container {
      position: fixed;
      z-index: 999999;
      margin-top: 55px;
      }
      @media (min-width: 992px) {
      .navbar-expand-lg .offcanvas-body {
      display: flex
      ;
      flex-grow: 0;
      padding: 0;
      overflow-y: visible;
      flex-direction: row;
      flex-wrap: nowrap;
      align-content: center;
      justify-content: center;
      align-items: center;
      padding-top: 30px;
      }
      }
      /* Hide on desktop */
      .hide-on-desktop {
      display: none;
      }
      /* Show on mobile */
      @media (max-width: 768px) {
      .hide-on-desktop {
      display: block;
      }
      }
      /* Hide on mobile */
      @media (max-width: 768px) {
      .hide-on-mobile {
      display: none;
      }
      }
      /* Show on desktop */
      .hide-on-mobile {
      display: block;
      }
      /* Search Suggestions Dropdown */
        #search-suggestions {
            /* background: #fff;
            border: 1px solid #ddd; */
            max-height: 350px;
            overflow-y: auto;
            padding: 1px;
            z-index: 1000;
        }

        /* Individual Suggestion Items */
        #search-suggestions li {
            padding: 0px;
            list-style: none;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            margin-bottom: 2px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        /* Suggestion Link */
        #search-suggestions li a {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 5px 10px;
        }

        /* Hover Effect */
        #search-suggestions li:hover {
            background: #257bd1;

        }

        /* Styling the Image */
        #search-suggestions li a img {
            width: 30px; /* Adjust as needed */
            height: 30px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px; /* Space between image and text */
        }

        /* Styling the Product Title */
        #search-suggestions li a span {
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        /* Hover Effect on Text */
        #search-suggestions li a:hover {
            color: #ffffff;
            font-weight: bold;
        }
   </style>
   </head>
   <body>
      <div id="mySidebar" class="sidebar">
         <div class="cart-header">
            <span class="cart-title px-4">Products</span>
            <span class="close-btn" onclick="closeSidebar()">&times;</span>
         </div>
         @if(Auth::check())
         <!-- Menu items for logged-in users -->
         <div class="DOTdrop pt-5">
            <a href="{{ url('products/men') }}">
               <h6>MENS</h6>
            </a>
         </div>
         <div class="DOTdrop">
            <a href="{{ url('products/women') }}">
               <h6>WOMENS</h6>
            </a>
         </div>
         <div class="DOTdrop">
            <a href="{{ url('products/perfume') }}">
               <h6>PERFUME</h6>
            </a>
         </div>
         @else
         <!-- Menu items for guests (not logged in) -->
         <div class="DOTdrop pt-5">
            <a href="{{ url('products/men') }}">
               <h6>MENS</h6>
            </a>
         </div>
         <div class="DOTdrop">
            <a href="{{ url('products/women') }}">
               <h6>WOMENS</h6>
            </a>
         </div>
         <div class="DOTdrop">
            <a href="{{ url('products/perfume') }}">
               <h6>PERFUME</h6>
            </a>
         </div>
         @endif
         <!-- Footer with Social Media Links, Email, and Phone -->
         <div class="sidebar-footer">
            <div class="contact-info">
               <hr>
               <p>Email: {{$setting->email}}</p>
               <p>Phone: +{{ substr($setting->phone, 0, 2) . ' ' . substr($setting->phone, 2) }}</p>
            </div>
            <div class="social-links">
               <a href="https://www.youtube.com/@sutramglobal" class="social-link" target="_blank">
               <i class="fab fa-youtube" style="color: black;"></i>
               </a>
               <a href="https://www.facebook.com/sutramglobal?mibextid=LQQJ4d&rdid=s3TySaCMU9FUsH79&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F3LJjoLpAMxrahLGp%2F%3Fmibextid%3DLQQJ4d" class="social-link" target="_blank">
               <i class="fab fa-facebook-f" style="color:black;"></i>
               </a>
               <a href="https://www.linkedin.com/company/sutramglobal/?viewAsMember=true" class="social-link" target="_blank">
               <i class="fab fa-linkedin" style="color: black;"></i>
               </a>
               <a href="https://www.instagram.com/sutramglobal/" class="social-link" target="_blank">
               <i class="fab fa-instagram" style="color: black;"></i>
               </a>
               <a href="https://in.pinterest.com/sutramglobalsocial/" class="social-link" target="_blank">
               <i class="fab fa-pinterest" style="color: black;"></i>
               </a>
            </div>
         </div>
      </div>
      <!-- Header -->
      <header id="site-header" class="fixed-top">
         <div class="container-fluid px-4">
            <nav class="navbar navbar-expand-lg navbar-dark p-0">
               <!-- Sidebar Toggle Button -->
               <div class="pt-3 mx-2">
                  <span class="hamburger nav-link" onclick="openSidebar()">
                  <i class="fas fa-bars"></i>
                  </span>
               </div>
               <!-- Logo -->
               <a class="navbar-brand logo" href="{{ route('home') }}">
               <img id="logo-img" src="{{ asset('public/web') }}/images/SutramWhite.png" alt="Logo">
               </a>
               <!-- Offcanvas Toggle Button -->
               <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
               <span class="hamburger nav-link mt-4 p-0">
               <i class="fas fa-user"></i>
               </span>
               </button>
               <!-- Offcanvas Content -->
               <div class="offcanvas offcanvas-end navbackground" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                  <div class="offcanvas-header">
                     <button type="button" class="btn text-white" data-bs-dismiss="offcanvas" aria-label="Close">
                        <i class="fas fa-times"></i>
                  </div>
                  <div class="offcanvas-body ms-auto">
                    <!-- Search Form -->
                    <div>
                        <div>
                            <form class="d-flex search-box" role="search" action="{{ route('search') }}" method="GET">
                                <input class="form-control me-2" required type="search" name="keyword" id="search-input" placeholder="Search" aria-label="Search" autocomplete="off">
                                <button class="btn btn-outline-dark" id="btn-search" type="submit">Search</button>
                            </form>
                        </div>
                        <div>
                            <!-- Suggestions Dropdown -->
                            <ul id="search-suggestions" class="list-group position-absolute w-40 "></ul>
                        </div>


                    </div>


                  <!-- Navigation Links -->
                  <ul class="navbar-nav p-1">
                  @if(Auth::check())
                  <li class="nav-item">
                  <a class="nav-link" href="{{ route('profile') }}">Hey {{ Auth::user()->first_name ?? 'User' }}</a>
                  </li>
                  <hr style="color:white;">

                  <li class="nav-item">
                  <a class="nav-link" href="{{ route('wishlist') }}">My Wishlist</a>
                  </li>
                  <li class="nav-item hide-on-desktop">
                  <a class="nav-link" href="{{route('order.list')}}">My Order</a>
                  </li>
                  <li class="nav-item hide-on-desktop">
                  <a class="nav-link" href="{{ route('address') }}">Saved Address</a>
                  </li>
                  <li class="nav-item hide-on-desktop">
                  <a class="nav-link" href="{{ route('logout') }}">Sign Out</a>
                  </li>
                  @else
                  <li class="nav-item">
                  <a class="nav-link" href="{{ route('weblogin') }}">Login</a>
                  </li>

                  @endif
                  <li class="nav-item">
                  <a class="nav-link" href="#" id="openCartBtn">My Cart</a>
                  </li>
                  </ul>
                  </div>
               </div>
            </nav>
            @include('web.layout.navbar')
         </div>
      </header>
      {{--
      <div id="alert-message" class="alert d-none"></div>
      --}}
      <script>
         document.addEventListener('scroll', function() {
             var header = document.getElementById("site-header");
             if (window.scrollY > 50) {
                 header.classList.add("scrolled");
             } else {
                 header.classList.remove("scrolled");
             }
         });
      </script>
      <!--------------- header END ----------------->
      @yield('contant')
      <style>
         .social-icons .fab{
         font-size:22px;
         }
         .footer {
         background-color:black; /* Dark background */
         color: #fff; /* White text */
         font-size: 14px; /* Font size */
         }
         .footer p {
         margin: 0;
         }
         .footer-logo {
         width: 80px; /* Adjust logo size */
         height: auto;
         vertical-align: middle;
         }
         .footer-middle, .footer-right {
         display: flex;
         align-items: center;
         }
         .payment-section {
         background-color: black; /* Light background for contrast */
         padding: 20px 0;
         }
         .payment-logos img {
         width: 60px; /* Adjust logo size */
         height: auto;
         transition: transform 0.3s ease; /* Smooth hover effect */
         }
         .payment-logos img:hover {
         transform: scale(1.1); /* Slight zoom effect on hover */
         }
         .payment-logos img:not(:last-child) {
         margin-right: 15px; /* Space between logos */
         }
         h5 {
         font-weight: 600;
         color: #333; /* Darker text for better visibility */
         }
         @media (max-width: 768px) {
         .container {
         flex-direction: column;
         text-align: center;
         }
         .footer-middle, .footer-right {
         margin-top: 10px;
         }
         }
         @media (max-width: 576px) {
         .payment-logos {
         flex-wrap: wrap;
         }
         .payment-logos img {
         margin-bottom: 10px;
         }
         }
      </style>
      <!-- Footer -->
      <section class="footcolor">
         <div class="container-fluid pt-5">
            <div class="row pb-2">
               <div class="col-md-4 imagefot">
                  <a href="#">
                  <img class="logos" src="{{ asset('public/web') }}/images/SutramWhite.png" width="90%">
                  </a>
                  <p class="text-white pr-5 text-capitalize">{{$setting->address}}</p>
                  <!-- Social Media Links -->
                  <div class="social-icons mt-3">
                     <a href="https://www.instagram.com/sutramglobal/" target="_blank" class="text-white me-3">
                     <i class="fab fa-instagram"></i>
                     </a>
                     <a href="https://www.facebook.com/sutramglobal?mibextid=LQQJ4d&rdid=s3TySaCMU9FUsH79&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F3LJjoLpAMxrahLGp%2F%3Fmibextid%3DLQQJ4d" target="_blank" class="text-white me-3">
                     <i class="fab fa-facebook"></i>
                     </a>
                     <a href="https://www.youtube.com/@sutramglobal" target="_blank" class="text-white me-3">
                     <i class="fab fa-youtube"></i>
                     </a>
                     <a href="https://www.linkedin.com/company/sutramglobal/?viewAsMember=true" target="_blank" class="text-white me-3">
                     <i class="fab fa-linkedin"></i>
                     </a>
                     <a href="https://in.pinterest.com/sutramglobalsocial/" target="_blank" class="text-white">
                     <i class="fab fa-pinterest"></i>
                     </a>
                  </div>
               </div>
               <div class="col-md-2 customer">
                  <h4 class="text-white">Customer&nbsp;Service</h4>
                  <!-- <p class="m-1"><a class="" href="">Return & Cancellation</p></a> -->
                  <p class="m-1"><a class="" href="{{url('page/privacy-policy')}}">Privacy & Policy</p>
                  </a>
                  <p class="m-1"><a class="" href="{{url('page/terms-and-condition')}}">Terms & Conditions</p>
                  </a>
                  <p class="m-1"><a class="" href="{{url('page/cancellation-and-refund-policy')}}">Cancellation & RefundPolicy</p>
                  </a>
                  <p class="m-1"><a class="" href="{{url('page/shipping-and-delivery-policy')}}">Shipping & DeliveryPolicy</p>
                  </a>
               </div>
               <div class="col-md-2 shop">
                  <h4 class="text-white">Shop</h4>
                  <p class="m-1"><a class="" href="{{ url('products/men') }}">Men</p>
                  </a>
                  <p class="m-1"><a class="" href="{{ url('products/women') }}">Women</p>
                  </a>
                  <p class="m-1"><a class="" href="{{ url('products/perfume') }}">Perfume</p>
                  </a>
               </div>
               <div class="col-md-2 aboutfot">
                  <h4 class="text-white">Quick Links</h4>
                  @auth
                  <p class="m-1"><a class="" href="{{route('profile')}}">My Profile </p>
                  </a>
                  @endauth
                  <p class="m-1"><a class="" href="{{route('contact')}}">Contact Us</p>
                  </a>
                  @auth
                  <p class="m-1"><a class="" href="{{route('order.list')}}">My Orders</p>
                  </a>
                  @endauth
                  {{--
                  <p class="m-1"><a class="" href=""></p>
                  </a> --}}
               </div>
               <div class="col-md-2 contatfot">
                  <h4 class="text-white">Contact Us</h4>
                  <p class="text-white m-0">+{{ substr($setting->phone, 0, 2) . ' ' . substr($setting->phone, 2) }}</p>
                  <p class="text-white">{{$setting->email}}</p>
               </div>
            </div>
         </div>
         <div class="container payment-section text-end">
            <div class="payment-logos d-flex justify-content-end align-items-center px-3">
               <img src="{{ asset('public/web/images/master.png') }}" alt="Mastercard" class="payment-logo me-3">
               <img src="{{ asset('public/web/images/visa.png') }}" alt="Visa" class="payment-logo me-3">
               <img src="{{ asset('public/web/images/paytm.png') }}" alt="Paytm" class="payment-logo">
               <img src="{{ asset('public/web/images/bhim.png') }}" alt="Paytm" class="payment-logo">
            </div>
         </div>
      </section>
      <footer class="footer text-white py-3">
         <div class="container d-flex justify-content-between align-items-center">
            <!-- Left Section: Copyright -->
            <div class="footer-left">
               <p class="mb-0">&copy; 2024 Sutramglobal . All Right Reserved.</p>
            </div>
            <div class="footer-left">
               <p class="mb-0"> Designed and Developed by <a href="https://devicedisk.in/" target="blank">DeviceDisk</a></p>
            </div>
            <!-- Middle Section: Shipment Secured -->
            <div class="footer-middle d-flex align-items-center">
               <p class="mb-0 me-2">Shipment Secured by</p>
               <img src="{{ asset('public/web/images/shiprocket.png') }}" alt="Shiprocket" class="footer-logo">
            </div>
            <!-- Right Section: Payment Secured -->
            <div class="footer-right d-flex align-items-center">
               <p class="mb-0 me-2">Payment Secured by</p>
               <img src="{{ asset('public/web/images/razorpay.png') }}" alt="Razorpay" class="footer-logo">
            </div>
         </div>
      </footer>
       <!-- Add the jQuery library -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
      <!-- Optionally, you can include the OWL Carousel theme CSS file (choose one) -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
      <!-- or -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.green.min.css">

      <!-- Add the OWL Carousel JavaScript file -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
      <!--------------- CUSTOM JAVASCRIPT START ----------------->
      <script src="{{ asset('public/web') }}/JS/custom.js"></script>
      <!--------------- CUSTOM JAVASCRIPT END ----------------->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
      <script>
         // Get the logged-in user ID (if logged in, otherwise it's null)
         var userId = {{ Auth::id() ?? 'null' }};
      </script>
      @stack('sub-script')
        <script type="text/javascript">
            const loadCartItemsUrl = "{{ route('cart.get-mini-cart-list') }}";

            function loadCartItems() {
            $.ajax({
                url: loadCartItemsUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Populate cart items and totals
                    $('#cartItemsContainer').html(response.html);
                    $('#totalItems').text(response.totalItems);
                    $('#subtotalAmount').text(`INR ${response.subtotal}`);

                    // Show or hide the "Proceed to Checkout" button based on total items
                    if (response.totalItems > 0) {
                        $('.checkout-btn').show(); // Show the button
                    } else {
                        $('.checkout-btn').hide(); // Hide the button
                    }
                },
                error: function(error) {
                    console.error('Error loading cart items:', error);
                }
            });
            }


            // Function to update the quantity of a specific cart item
            function updateCartItemQty(element, cartId, isIncrement) {
                // Get the current quantity from the sibling span with class 'item-quantity'
                let currentQty = parseInt($(element).siblings('.item-quantity').text());

                // Calculate the new quantity
                let newQty = isIncrement ? currentQty + 1 : Math.max(1, currentQty - 1); // Ensure at least 1

                // Send the request to update the cart
                $.ajax({
                    url: "{{ route('cart.update-cart') }}",
                    type: 'POST',
                    data: {
                        cart_id: cartId,
                        qty: newQty
                    },
                    success: function(response) {
                        loadCartItems(); // Refresh the cart
                    },
                    error: function(error) {
                        if (error.status === 422 && error.responseJSON && error.responseJSON.maxQty) {
                            alert(`You can only add up to ${error.responseJSON.maxQty} of this item.`);
                        } else {
                            console.error('Error updating cart item:', error);
                        }
                    }
                });
            }



            // Function to remove an item from the cart
            function removeCartItem(cartId) {
                $.ajax({
                    url: "{{ route('cart.remove-cart') }}",
                    type: 'POST',
                    data: {
                        cart_id: cartId
                    },
                    success: function(response) {
                        loadCartItems(); // Refresh cart after removing item
                    },
                    error: function(error) {
                        console.error('Error removing cart item:', error);
                    }
                });
            }

            $(document).ready(function() {
                // Load cart items on every page load or when cartSidebar is opened
                loadCartItems();

                // Update CSRF token in AJAX headers
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Attach event listeners to quantity and remove buttons dynamically
                $(document).on('click', '.decrease-qty', function() {
                    let cartId = $(this).closest('.cart-item').data('id');
                    updateCartItemQty(this, cartId, false); // false means decrease
                });

                $(document).on('click', '.increase-qty', function() {
                    let cartId = $(this).closest('.cart-item').data('id');
                    updateCartItemQty(this, cartId, true); // true means increase
                });

                $(document).on('click', '.delete-btn', function() {
                    let cartId = $(this).closest('.cart-item').data('id');
                    removeCartItem(cartId);
                });
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                // Clear errors when user types
                $('form input, form textarea').on('input', function() {
                    if ($(this).hasClass('is-invalid')) {
                        $(this).removeClass('is-invalid');
                        $(this).siblings('.invalid-feedback').hide();
                    }
                });

                function showAlertMessage(message, alertType = 'alert-success') {
                    var alertDiv = $('#alert-message');
                    alertDiv.removeClass('d-none alert-success alert-danger')
                            .addClass(alertType + ' show') // Add alert type and show class
                            .text(message); // Set alert message

                    // Hide the alert after 3 seconds
                    setTimeout(function() {
                        alertDiv.removeClass('show'); // Fade out
                    }, 3000);
                }

                // Example usage of the showAlertMessage function
                showAlertMessage("Your custom alert message goes here!", "alert-success");

                // Clear errors function
                function clearError(form) {
                    form.find('.is-invalid').removeClass('is-invalid');
                    form.find('.invalid-feedback').remove();
                }

            });
        </script>

        <script>
            $(document).ready(function() {
                $('#search-input').on('keyup', function() {
                    let keyword = $(this).val();
                    if (keyword.length > 1) {
                        $.ajax({
                            url: "{{ route('search.suggestions') }}",
                            type: "GET",
                            data: { keyword: keyword },
                            success: function(response) {
                                let suggestions = "";
                                response.forEach(product => {
                                    suggestions += `<li class="list-group-item">
                                        <a class="" href="${product.url}">
                                            <img src="${product.image}">
                                            ${product.title}</a>
                                    </li>`;
                                });
                                $("#search-suggestions").html(suggestions).show();
                            }
                        });
                    } else {
                        $("#search-suggestions").hide();
                    }
                });

                // Hide suggestions on clicking outside
                $(document).on("click", function(event) {
                    if (!$(event.target).closest("#search-input, #search-suggestions").length) {
                        $("#search-suggestions").hide();
                    }
                });
            });

        </script>
   </body>
</html>
