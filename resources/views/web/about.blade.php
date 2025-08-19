@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
    .hero-section-about {
  position: relative;
  background: url('public/img/about.jpg') no-repeat center center/cover; /* Replace with your image path */
  min-height: 100vh;
  padding: 20px 20px;
  display: flex;
  align-items: center;

}

.hero-section-about::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(0 0 0 / 7%);
  z-index: 1; 
}
.motivation-section {
  background-color: #f9f9f9;
  position: relative;
}

.motivation-section .small-heading {
  font-size: 14px;
  letter-spacing: 1px;
}

.motivation-section .main-heading {
  position: relative;
  z-index: 2;
}

.motivation-section .main-heading::before {
  content: 'THE BEGINNING';
  position: absolute;
  top: -60px;
  left: 0;
  font-size: 72px;
  font-weight: bold;
  color: rgba(0, 0, 0, 0.05);
  z-index: 1;
  pointer-events: none;
}
.marginformotivation{
    margin-top: 50px;
}
.vision-section {
    padding: 50px 0;
    background-color: #ffffff;
}


.vision-content {
    background-color: black;
    color: #ffffff;
    padding: 30px;

}

.vision-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.vision-item {
    margin-bottom: 20px;
}

.vision-subtitle {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

.vision-item p {
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 10px;
}

.vision-item hr {
    border-top: 1px solid #ffffff;
    opacity: 0.5;
}
  @media (max-width: 768px) {
      .motivation-section .main-heading::before {
  content: 'THE BEGINNING';
  position: absolute;
  top: -60px;
  left: 0;
  font-size: 52px;
  font-weight: bold;
  color: rgba(0, 0, 0, 0.05);
  z-index: 1;
  pointer-events: none;
}
.marginformotivation {
    margin-top: 15px;
}
    .hero-section-about {
  position: relative;
  background: url('public/img/about.jpg') no-repeat center center/cover; /* Replace with your image path */
  min-height: 72vh;
  padding: 20px 20px;
  display: flex;
  align-items: center;

}
  }

</style>
<!-- first section start  -->
<section class="hero-section-about" style="position: relative; padding: 100px 0; background: url('public/img/about.jpg') no-repeat center center/cover;">
  <!-- Full Overlay -->
  <div style="background: rgba(0, 0, 0, 0.6); position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></div>
  
  <div class="container" style="position: relative; z-index: 2;">
    <div class="row align-items-center">
      <!-- Right Side Text -->
      <div class="col-md-6 ms-auto text-white text-content text-start">
        <h6 class="small-heading" style="font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #f1f1f1;">About Us</h6>
        <h2 class="heading" style="font-size: 36px; font-weight: bold; margin: 10px 0; line-height: 1.2;">Our Story</h2>
        <p style="font-size: 16px; line-height: 1.8;">
          Minimalistic, intricate & sustainable; if one had to define Senses in a sentence, this would be it. Our story begins in Mumbai, where to our dismay, every luxury brand promised unique fabrics or a good fit but never both, and we deserved better. Our problems mounted a business, and just like that, our mission began with a minor yet substantial issue! For we wanted to look sleek & feel suave. Thus born Senses, the sixth sense one needs! A fashion brand that creates sustainable yet timeless collections for men through cautiously selected designs, fabrics, clear-cut silhouettes & attention to detail at an affordable price.
        </p>
      </div>
    </div>
  </div>
</section>
<section class="motivation-section py-5">
  <div class="container marginformotivation">
    <!-- Top Heading -->
    <div class="row">
      <div class="col-md-12">
        <h6 class="small-heading text-uppercase text-primary" style="font-weight: bold;">Our Journey</h6>
        <h2 class="main-heading" style="font-size: 36px; font-weight: bold; color: #333;">Things Which Motivate Us</h2>
      </div>
    </div>
    <div class="row mt-4">
      <!-- Left Side Content -->
      <div class="col-md-6">
        <p style="font-size: 16px; line-height: 1.8; color: #555;">
          One Vintage is a distinctive luxury brand founded by Simone Myson in 2010. This avant-garde label ingeniously revitalizes antique textiles and relics, seamlessly weaving them into contemporary and modern masterpieces.
        </p>
        <p style="font-size: 16px; line-height: 1.8; color: #555;">
          The Simone Myson brand sets forth its vision as contemporary and independent of established canons within the wedding fashion realm, boldly ready to develop its own rules and trends. Fueled by a commitment to innovation, a desire to create the extraordinary, and unwavering faith in Ukrainian business, the Simone Myson team propels itself forward, striving to make its name known worldwide.
        </p>
        <p style="font-size: 16px; line-height: 1.8; color: #555;">
          "Lorem ipsum dolor sit amet consectetur. Elit augue est adipiscing erat sed sit aliquet. Cras vitae auctor molestie nisi phasellus neque morbi et interdum."
        </p>
        <p style="font-style: italic; color: #888;">- Founder, Sutanglobal</p>
      </div>

      <!-- Right Side Images -->
      <div class="col-md-6">
        <div class="row g-3">
          
          <div class="col-6">
            <img src="public/img/about2.jpg" alt="Image 2" class="img-fluid">
          </div>
           <div class="col-6">
            <img src="public/img/about4.jpg" alt="Image 4" class="img-fluid">
          </div>
          <div class="col-6">
            <img src="public/img/about1.jpg" alt="Image 1" class="img-fluid">
          </div>
          <div class="col-6">
            <img src="public/img/about3.jpg" alt="Image 3" class="img-fluid">
          </div>
         
        </div>
      </div>
    </div>
  </div>
</section>
<section class="vision-section" >
    <div class="container"style="background-color:black;">
        <div class="row align-items-center">
            <!-- Image Section -->
            <div class="col-md-6 p-0">
                <img src="public/img/about5.jpg" alt="Vision Image" class="img-fluid vision-image" />
            </div>
            <!-- Content Section -->
            <div class="col-md-6">
                <div class="vision-content">
                    <h2 class="vision-title">Our Vision</h2>
                    <div class="vision-item">
                        <h4 class="vision-subtitle">Quality Products</h4>
                        <p>Lorem ipsum dolor sit amet consectetur. Sed tincidunt ut sodales vitae sed id netus nibh. Nibh interdum at aliquet urna donec.</p>
                        <hr />
                    </div>
                    <div class="vision-item">
                        <h4 class="vision-subtitle">Quality Services</h4>
                        <p>Lorem ipsum dolor sit amet consectetur. Sed tincidunt ut sodales vitae sed id netus nibh. Nibh interdum at aliquet urna donec.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
     <div class="container why-choose">
        <div class="row mb-4">
            <div class="col-12 ">
                <h2>Why Choose sutramglobal?</h2>
                <ul class="" style="max-width: 600px;">
                    <li><strong>Affordable Quality:</strong> Premium fabrics, precise craftsmanship, and sophisticated fragrances—all at prices that fit your budget.</li>
                    <li><strong>Durability & Comfort:</strong> Every product is made to last, ensuring you look and feel your best, time after time.</li>
                    <li><strong>Expressive Style:</strong> Whether you prefer minimalism or bold prints, our collections are designed to complement your unique personality.</li>
                </ul>
                <p>When you choose sutramglobal, you’re not just shopping—you’re choosing to <strong>Dress Your Story</strong> in the most authentic way.</p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <img src="public/img/why1.jpg" alt="Image 1" class="img-fluid">
            </div>
            <div class="col-md-3 col-6">
                <img src="public/img/why1.jpg" alt="Image 2" class="img-fluid">
            </div>
            <div class="col-md-3 col-6">
                <img src="public/img/why1.jpg" alt="Image 3" class="img-fluid">
            </div>
            <div class="col-md-3 col-6">
                <img src="public/img/why1.jpg" alt="Image 4" class="img-fluid">
            </div>
        </div>
    </div>
</section>
<section class="py-5">
<div class="container">
    <div class="row">
        <div class="col-md-6">
        <div class="">
                <img src="public/img/corporate1.jpg" alt="Image 1" class="img-fluid">
            </div>
            <h4>Corporate Orders: Style That Speaks for Your Brand</h4>
            <p>sutramglobal isn’t just for individuals; we also cater to businesses looking to leave a lasting impression. Whether you’re planning employee gifting, team uniforms, or customised branding solutions, we’re here to help.</p>
            <div class="col-12 ">
                <h5>Why choose sutramglobal for your corporate needs?</h5>
                <ul class="" style="max-width: 600px;">
                    <li><strong>Affordable Quality:</strong> Premium fabrics, precise craftsmanship, and sophisticated fragrances—all at prices that fit your budget.</li>
                    <li><strong>Durability & Comfort:</strong> Every product is made to last, ensuring you look and feel your best, time after time.</li>
                    <li><strong>Expressive Style:</strong> Whether you prefer minimalism or bold prints, our collections are designed to complement your unique personality.</li>
                </ul>
                <p>When you choose sutramglobal, you’re not just shopping—you’re choosing to <strong>Dress Your Story</strong> in the most authentic way.</p>
            </div>

        </div>
        <div class="col-md-6">
        <div class="">
                <img src="public/img/corporate2.jpg" alt="Image 1" class="img-fluid">
            </div>
        </div>
    </div>
</div>
</section>
<section class="vision-section" >
    <div class="container">
        <div class="row align-items-center">
            <!-- Image Section -->
            <div class="col-md-6 p-0">
                <img src="public/img/about5.jpg" alt="Vision Image" class="img-fluid vision-image" />
            </div>
            <!-- Content Section -->
            <div class="col-md-6">
                <div class="">
                    <div class="vision-item">
                        <h4 class="vision-subtitle">Join the Movement</h4>
                        <p>Lorem ipsum dolor sit amet consectetur. Sed tincidunt ut sodales vitae sed id netus nibh. Nibh interdum at aliquet urna donec.</p>
                        <hr />
                    </div>
                    <div class="vision-item">
                        <p><b>Dress Your Story </b>Lorem ipsum dolor sit amet consectetur. Sed tincidunt ut sodales vitae sed id netus nibh. Nibh interdum at aliquet urna donec.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection