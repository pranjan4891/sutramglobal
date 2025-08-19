<style>
    /* Hide on desktop and larger devices */
@media (min-width: 768px) {
    .navbar > .container, 
    .navbar > .container-fluid, 
    .navbar > .container-lg, 
    .navbar > .container-md, 
    .navbar > .container-sm, 
    .navbar > .container-xl, 
    .navbar > .container-xxl {
        display: block;
    }
}

/* Display on mobile */
@media (max-width: 767px) {
    .navbar > .container, 
    .navbar > .container-fluid, 
    .navbar > .container-lg, 
    .navbar > .container-md, 
    .navbar > .container-sm, 
    .navbar > .container-xl, 
    .navbar > .container-xxl {
        display: none;
    }
}

</style>


<nav class="navbar navbar-light p-0 m-0 ">
    <div class="container list " >
        @if(isset($categories) && $categories->count())
        <ul class="d-flex">
            @foreach($categories as $category)
                <li class="nav-item dropdown p-0 m-0 headdrop" >
                    <a class="nav-link dropdown-toggle p-0 mx-2" href="#" id="navbarDropdownMenuLink{{ $category->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false" >
                        {{ $category->name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink{{ $category->id }}">
                        @foreach($category->subcategories as $subcategory)
                            <li><a class="dropdown-item" href="{{ url('products/'.$category->slug.'/'.$subcategory->slug) }}">{{ $subcategory->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endforeach


        </ul>
        @endif
    </div>
</nav>
