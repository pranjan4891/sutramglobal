<!DOCTYPE html>
<html lang="en">

<head>
  <!-----------------REQURIED META TAGS START-------------------->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-----------------REQURIED META TAGS END-------------------->
  <!------bootstrap cdn----->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

  <!------style css----->
  <link rel="stylesheet" href="CSS/style.css">

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

  <title>Sutramglobal</title>
</head>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <span class="cart-title">My Cart</span>
        <span class="close-btn" id="closeCartBtn">&times;</span>
    </div>

    <div class="cart-items">    
        <div class="extraclassforscrolltocart">
        <div class="cart-item">
            <img src="images/mycart.png" alt="Product Image">
                <div class="item-details">
                          <div class="d-flex spacecart">
                              <div>
                              <p class="item-name">POLO - WAFFLE </p>
                              </div>
                              <div><i class="fas fa-trash delete-btn text-end" title="Remove"></i></div>
                        </div>
                        <p>Size : <span>32</span></p>
                        <p>color : <span> Gray</span></p>
                          <div class="d-flex spacecart">
                              <div>
                                  <div class="quantity-control">
                                      <button class="decrease-qty">-</button>
                                      <span class="item-quantity">2</span>
                                      <button class="increase-qty">+</button>
                                  </div>
                              </div>
                              <div>INR 5300</div>
                        </div>
                 </div>                        
         </div>
         <hr>
         <div class="cart-item">
            <img src="images/mycart.png" alt="Product Image">
                <div class="item-details">
                          <div class="d-flex spacecart">
                              <div>
                              <p class="item-name">POLO - WAFFLE </p>
                              </div>
                              <div><i class="fas fa-trash delete-btn text-end" title="Remove"></i></div>
                        </div>
                        <p>Size : <span>32</span></p>
                        <p>color : <span> Gray</span></p>
                          <div class="d-flex spacecart">
                              <div>
                                  <div class="quantity-control">
                                      <button class="decrease-qty">-</button>
                                      <span class="item-quantity">2</span>
                                      <button class="increase-qty">+</button>
                                  </div>
                              </div>
                              <div>INR 5300</div>
                        </div>
                 </div>                        
         </div>
        </div>
    </div>
    <div class="cart-footer">
    <div class="d-flex spacecart">
                        <div class="py-2">
                            <P>TOTAL ITEMS</P>
                        </div>
                        <div><p class="pt-2">3</p></div>                        
                   </div>
                    <div class="d-flex spacecart">
                        <div>
                            <h6>SUB TOTAL</h6>
                        </div>
                        <div>INR 5300</div>                        
                   </div>
                   <P class="py-3" style="font-size:11px;">Shipping, taxes, and discount codes calculated at checkout.</P>
        <button class="checkout-btn">Proceed to Checkout</button>
    </div>
</div>

<style>
  
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
  content: url('./images/SutramBlack.png'); /* Change the logo to the black version */
}

/* Change the text color of the second navbar */
#site-header.scrolled .list li {
  color: black !important;
}
#site-header.scrolled .navbar-nav .nav-link {
  color: black !important;
}
/* header closed */

  </style>

</head>
<body>
<div id="mySidebar" class="sidebar">
  <div class="cart-header">
    <span class="cart-title px-4">MY ACCOUNT</span>
    <span class="close-btn" onclick="closeSidebar()">&times;</span>
  </div>

  <div class="DOTdrop pt-5">
    <a href="myprofile.php"><h6>MY PROFILE</h6></a>
  </div>
  <div class="DOTdrop">
    <a href="orderdetail.php"><h6>MY ORDERS</h6></a>
  </div>
  <div class="DOTdrop">
    <a href="address.php"><h6>SAVED ADDRESSES</h6></a>
  </div>
  <div class="DOTdrop">
    <a href="policy.php"><h6>RETURN POLICY</h6></a>
  </div>
  <div class="DOTdrop">
    <a href="contact.php"><h6>CONTACT US</h6></a>
  </div>
  <div class="DOTdrop">
    <a href="#"><h6>SIGN OUT</h6></a>
  </div>

  <!-- Footer with Social Media Links, Email, and Phone -->

  <div class="sidebar-footer">
    <div class="contact-info">
    <hr>
      <p>Email: info@example.com</p>
      <p>Phone: +123456789</p>
    </div>
    <div class="social-links">
      <a href="https://www.facebook.com" class="social-link" target="_blank">
        <i class="fab fa-facebook-f" style="color: #4267B2;"></i>
      </a>
      <a href="https://www.twitter.com" class="social-link" target="_blank">
        <i class="fab fa-twitter" style="color: #1DA1F2;"></i>
      </a>
      <a href="https://www.instagram.com" class="social-link" target="_blank">
        <i class="fab fa-instagram" style="color: #E1306C;"></i>
      </a>
      <a href="https://www.linkedin.com" class="social-link" target="_blank">
        <i class="fab fa-linkedin-in" style="color: #0077B5;"></i>
      </a>
    </div>
  </div>
</div>
 <!-- Header -->
 <header id="site-header" class="fixed-top">
  <div class="container-fluid px-4">
    <nav class="navbar navbar-expand-lg navbar-dark p-0">
      <div class="pt-3 mx-2"><span class="hamburger nav-link" onclick="openSidebar()">&#9776;</span></div>
      <a class="navbar-brand logo" href="index.php">
        <img id="logo-img" src="./images/SutramWhite.png" alt="Logo">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <form class="d-flex ms-auto me-3 search-box" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        </form>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="loginandregister.php">Login</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Help</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="wishlist.php">My Wishlist</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"  id="openCartBtn">My Cart</a>
          </li>
        </ul>
      </div>
    </nav>
    <nav class="navbar navbar-light p-0 m-0">
      <div class="container list">
       <ul class="d-flex">
        <li class="nav-item dropdown p-0 m-0 headdrop">
          <a class="nav-link dropdown-toggle p-0 mx-2" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            MEN
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="productCategory.php">POLO - WAFFLE</a></li>
            <li><a class="dropdown-item" href="productCategory.php">POLO - COTTON PIMA</a></li>
            <li><a class="dropdown-item" href="productCategory.php">OVERSIZE</a></li>
            <li><a class="dropdown-item" href="productCategory.php">Regular t-SHIRT</a></li>
            <li><a class="dropdown-item" href="productCategory.php">ACID WASH</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown p-0 m-0 headdrop">
          <a class="nav-link dropdown-toggle p-0 mx-2" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            WOMEN
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
           <li><a class="dropdown-item" href="#">POLO - WAFFLE</a></li>
            <li><a class="dropdown-item" href="#">POLO - COTTON PIMA</a></li>
            <li><a class="dropdown-item" href="#">OVERSIZE</a></li>
            <li><a class="dropdown-item" href="#">Regular t-SHIRT</a></li>
            <li><a class="dropdown-item" href="#">ACID WASH</a></li>
          </ul>
        </li>
          <a href="perfumecategory.php"><li class="text-white p-0 mx-2">PERFUME</li></a>
        </ul>
      </div>
    </nav>
  </div>
</header>
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