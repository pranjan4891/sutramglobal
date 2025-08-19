``
/*------------------ slider-------------------- */
$(document).ready(function()  {
  $(".owl-carousel").owlCarousel({
    items:5, // Number of items to show at a time
    loop: true, // Infinite loop
    margin:0, // Space between items
    nav: true, // Show navigation buttons
    autoplay: true, // Auto-play the carousel
    autoplayTimeout: 3000, // Auto-play interval in milliseconds
    responsive: {
      0: {
        items: 2 // Number of items to show on different screen sizes
      },
      600: {
        items: 2
      },
      1000: {
        items: 4
      }
    }
  });
});
 // Function to scrool smoth start
 window.addEventListener('scroll', function() {
    var scroll = document.querySelector('.scrollTop');

    if (scroll!=null) { // Check if the element with class .scrollTop exists
      scroll.classList.toggle("active", window.scrollY > 20);
    }
  });
function scrollToTop(){
  window.scrollTo({
    top:0,
    behavior:'smooth'
  });
};
 // Function to scroll smooth end


   // Function to open the popup button form
   function openEnquiryForm() {
    document.getElementById('enquiryForm').style.display = 'block';
  }

  // Function to close the popup form
  function closeEnquiryForm() {
    document.getElementById('enquiryForm').style.display = 'none';
  }


  var cartOpen = false;
var numberOfProducts = 0;

$('body').on('click', '.js-toggle-cart', toggleCart);
$('body').on('click', '.js-add-product', addProduct);
$('body').on('click', '.js-remove-product', removeProduct);

function toggleCart(e) {
  e.preventDefault();
  if(cartOpen) {
    closeCart();
    return;
  }
  openCart();
}

function openCart() {
  cartOpen = true;
  $('body').addClass('open');
}

function closeCart() {
  cartOpen = false;
  $('body').removeClass('open');
}

function addProduct(e) {
  e.preventDefault();
  openCart();
  $('.js-cart-empty').addClass('hide');
  var product = $('.js-cart-product-template').html();
  $('.js-cart-products').prepend(product);
  numberOfProducts++;
}

function removeProduct(e) {
  e.preventDefault();
  numberOfProducts--;
  $(this).closest('.js-cart-product').hide(250);
  if(numberOfProducts == 0) {
    $('.js-cart-empty').removeClass('hide');
  }
}

function openUniqueSidebar() {
  // Close previous sidebar if it exists
  closeSidebar();

  // Open unique sidebar
  document.getElementById("myUniqueSidebar").style.width = "250px";
  document.getElementsByClassName("unique-main-content")[0].classList.add('unique-sidebar-open');
}


function closeUniqueSidebar() {
document.getElementById("myUniqueSidebar").style.width = "0";
document.getElementsByClassName("unique-main-content")[0].classList.remove('unique-sidebar-open');
}


// function openSidebar() {
//   document.body.style.backgroundColor = "rgba(0, 0, 0, 0.5)"; /* Darken background */
//   document.getElementById("minicart").classList.add("active");
// }

function closeSidebar() {
  document.body.style.backgroundColor = "#f2f2f2"; /* Restore background color */
  document.getElementById("minicart").classList.remove("active");
}

// $('.input-number-increment').click(function() {
//   var $input = $(this).parents('.input-number-group').find('.input-number');
//   var val = parseInt($input.val(), 10);
//   $input.val(val + 1);
// });

// $('.input-number-decrement').click(function() {
//   var $input = $(this).parents('.input-number-group').find('.input-number');
//   var val = parseInt($input.val(), 10);
//   if (val > 1) { // Check if value is greater than 0 before decrementing
//       $input.val(val - 1);
//   }
// });

$(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});


function showPopup() {
  var popup = document.getElementById("popup");
  popup.style.display = "block";
}

function closePopup() {
  var popup = document.getElementById("popup");
  popup.style.display = "none";
}

function validateOTP() {
  var otpInput = document.getElementById("otpInput").value;
  // Here you would typically send the OTP to your server for validation
  // For this example, let's assume the OTP is "1234"
  if (otpInput === "1234") {
    alert("OTP validated successfully. Registration complete!");
    closePopup();
  } else {
    alert("Invalid OTP. Please try again.");
  }
}

