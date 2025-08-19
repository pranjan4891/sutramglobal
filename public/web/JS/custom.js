      // my cart sidebar
      const cartSidebar = document.getElementById('cartSidebar');
      const openCartBtn = document.getElementById('openCartBtn');
      const closeCartBtn = document.getElementById('closeCartBtn');

      // Open cart on button click
      openCartBtn.addEventListener('click', function() {
          cartSidebar.classList.add('open');
      });

      // Close cart on close button click
      closeCartBtn.addEventListener('click', function() {
          cartSidebar.classList.remove('open');
      });

      // Close cart by clicking outside of it
      window.addEventListener('click', function(event) {
          if (event.target !== cartSidebar && !cartSidebar.contains(event.target) && event.target !== openCartBtn) {
              cartSidebar.classList.remove('open');
          }
      });



// product detail image changer
function changeImage(element) {
  document.getElementById('mainProductImage').src = element.src;
}
    // Handle star rating click
    const stars = document.querySelectorAll('.stars');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            let rating = star.getAttribute('data-value');
            ratingInput.value = rating;

            // Clear all stars and add 'checked' class to selected ones
            stars.forEach(s => {
                s.classList.remove('checked');
                if (s.getAttribute('data-value') <= rating) {
                    s.classList.add('checked');
                }
            });
        });
    });



  // Quantity Increase/Decrease
  const increaseBtns = document.querySelectorAll('.increase-qty');
  const decreaseBtns = document.querySelectorAll('.decrease-qty');

  increaseBtns.forEach((btn, index) => {
      btn.addEventListener('click', () => {
          const qty = document.querySelectorAll('.item-quantity')[index];
          qty.innerText = parseInt(qty.innerText) + 1;
      });
  });

  decreaseBtns.forEach((btn, index) => {
      btn.addEventListener('click', () => {
          const qty = document.querySelectorAll('.item-quantity')[index];
          if (parseInt(qty.innerText) > 1) {
              qty.innerText = parseInt(qty.innerText) - 1;
          }
      });
  });


// left sidebar by three toggle icon
function openSidebar() {
  document.getElementById("mySidebar").style.width = "250px";
  document.querySelector(".main-content").style.marginLeft = "250px";
}

function closeSidebar() {
  document.getElementById("mySidebar").style.width = "0";
  document.querySelector(".main-content").style.marginLeft = "0";
}



// addresss js start
function deleteAddress() {
  if (confirm("Are you sure you want to delete this address?")) {
    // Add your delete action here
    alert("Address deleted!");
  }
}

function toggleDropdown(event) {
  const dropdownMenu = event.target.nextElementSibling;
  dropdownMenu.classList.toggle("showed");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropdowned-btn')) {
    var dropdowns = document.getElementsByClassName("dropdowned-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('showed')) {
        openDropdown.classList.remove('showed');
      }
    }
  }
}

// Function to show the edit form
function showEditForm() {
  document.getElementById('editAddressModal').style.display = 'block';
}

// Function to close the edit form
function closeEditForm() {
  document.getElementById('editAddressModal').style.display = 'none';
}

// address js end

    // Function to open the size guide modal
    function openSizeGuide() {
      document.getElementById("sizeTableModal").style.display = "block";
  }

  // Function to close the size guide modal
  function closeSizeGuide() {
      document.getElementById("sizeTableModal").style.display = "none";
  }

  // Close the modal when clicking anywhere outside of it
  window.onclick = function(event) {
      const modal = document.getElementById("sizeTableModal");
      if (event.target == modal) {
          closeSizeGuide();
      }
  }


// for event secton tab

function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tab-content");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

function denyConsent() {
  // Handle denying consent (e.g., redirect to a page explaining the consequences)
  alert("You denied consent.");
}

function acceptAll() {
  // Set cookie for consent with expiration time 24 hours in the future
  var d = new Date();
  d.setTime(d.getTime() + (24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toUTCString();
  document.cookie = "cookie_consent=true; " + expires + "; path=/";
  // Hide the modal
  document.getElementById("cookieModal").style.display = "none";
}

// Check if the cookie exists on page load and hide the modal if it does
window.onload = function () {
  if (document.cookie.indexOf('cookie_consent=true') !== -1) {
    document.getElementById("cookieModal").style.display = "none";
  }
};

// document.querySelector('.tablink').classList.add('active');

//   function openTab(event, tabName) {
//     var i, tabcontent, tablinks;
//     tabcontent = document.getElementsByClassName("tab-content");
//     tabcontent[1].style.display = "show";
//     for (i = 0; i < tabcontent.length; i++) {
//       tabcontent[i].style.display = "none";
//     }
//     tablinks = document.getElementsByClassName("tablink");
//     for (i = 0; i < tablinks.length; i++) {
//       tablinks[i].classList.remove("active");
//     }
//     document.getElementById(tabName).style.display = "block";
//     event.currentTarget.classList.add("active");
//   }

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
 window.addEventListener('scroll' , function(){
  var scroll = document.querySelector('.scrollTop');
  scroll.classList.toggle("active" , window.scrollY > 20)
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



  //multiple dropdowns
  document.addEventListener("DOMContentLoaded", function(){
    // make it as accordion for smaller screens
    if (window.innerWidth < 992) {

      // close all inner dropdowns when parent is closed
      document.querySelectorAll('.navbar .dropdown').forEach(function(everydropdown){
        everydropdown.addEventListener('hidden.bs.dropdown', function () {
          // after dropdown is hidden, then find all submenus
            this.querySelectorAll('.submenu').forEach(function(everysubmenu){
              // hide every submenu as well
              everysubmenu.style.display = 'none';
            });
        })
      });

      document.querySelectorAll('.dropdown-menu a').forEach(function(element){
        element.addEventListener('click', function (e) {
            let nextEl = this.nextElementSibling;
            if(nextEl && nextEl.classList.contains('submenu')) {
              // prevent opening link if link needs to open dropdown
              e.preventDefault();
              if(nextEl.style.display == 'block'){
                nextEl.style.display = 'none';
              } else {
                nextEl.style.display = 'block';
              }

            }
        });
      })
    }
    // end if innerWidth
    });

