@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
    .pod-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background: #343a408c;
}
.pod-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.pod-img img {
  max-height: 200px;
  object-fit: contain;
}
</style>
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>

<section class="pod-designs py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">{{ $subCategory->name }} Print Designs</h2>
      <p class="text-muted">Choose a design and get it printed on your T-Shirt</p>
    </div>

    <div class="row g-4">
      @forelse($designs as $design)
        <div class="col-md-3 col-sm-6">
          <div class=" text-center p-3 h-100 shadow-sm rounded">
            <div class=" mb-3">
               <img src="{{ asset('public/uploads/pod_designs/'.$design->image) }}" alt="{{ $design->name }}" class="img-fluid rounded">
            </div>
            <h5 class="fw-semibold">{{ $design->name }}</h5>
             <h5 class="fw-semibold">INR 600.00</h5>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <p class="text-muted">No designs available for this category.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>


{{-- <section class="pod-designs py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Our Print Designs</h2>
      <p class="text-muted">Choose a design and get it printed on your T-Shirt</p>
    </div>

    <div class="row g-4">
      <!-- Design Card -->
     

      <!-- Design Card -->
      <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/AllIneedisbahutpaisa.png') }}" alt="Design 2" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">All I need is bahut paisa</h5>
        </div>
      </div>

      <!-- Design Card -->
      <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/dogymnotlove.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Do gym, not love</h5>
        </div>
      </div>
      
       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/10.png') }}" alt="Design 1" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">10</h5>
        </div>
      </div>

      <!-- Design Card -->
      <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/ekcupchai.png') }}" alt="Design 4" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Ek Cup Chai</h5>
        </div>
      </div>

 <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/entrylate.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Entry late</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/GoodVibes.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Good Vibes</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/iamthisold.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">I am this old</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/legendsarealwayslate.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Legends are always late</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/Nevergiveupsanskrit.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Never give upsanskrit</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/relax.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Relax</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/Shhhh.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Shhhh</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/takeiteasy.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Take it easy</h5>
        </div>
      </div>

       <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/tangmatkaro.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Tang mat karo</h5>
        </div>
      </div>
      
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/iamclassic.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">I am classic</h5>
        </div>
      </div>
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/jolforing.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Jol Foring</h5>
        </div>
      </div>
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/Lifeisbeautiful.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Life is beautiful</h5>
        </div>
      </div>
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/Nasa.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Nasa</h5>
        </div>
      </div>
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/richrisk.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Rich risk</h5>
        </div>
      </div>
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/trafficandpyar.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Traffic and pyar</h5>
        </div>
      </div>
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/TumiRobeNirobe.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">Tumi robe nirobe</h5>
        </div>
      </div>
             <div class="col-md-3 col-sm-6">
        <div class="pod-card text-center p-3 h-100 shadow-sm rounded">
          <div class="pod-img mb-3">
            <img src="{{ asset('public/img/youvsyou.png') }}" alt="Design 3" class="img-fluid rounded">
          </div>
          <h5 class="fw-semibold">You vs you</h5>
        </div>
      </div>

    </div>
  </div>
</section> --}}

@endsection