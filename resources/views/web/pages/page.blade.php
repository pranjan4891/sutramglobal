@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<!-- first section start -->
<section class="py-5">
    <div class="container">
      {!!$page->description!!}
    </div>
</section>



@endsection
