<style>
@media screen and (max-width: 767px){
    .custom-breadcrumb{
        padding-top: 0 !important;
        padding-left: 0 !important;
    }
}

@media screen and (min-width: 767px){
      .custom-breadcrumb{
        padding-top: 11px ;
        padding-left: 217px ;
    }
}



</style>

@hasSection('breadcrumb')
    <div class="breadcrumb custom-breadcrumb" >
        <ul class="list-inline">
            <li><a href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i></a></li>

            @yield('breadcrumb')
        </ul>
    </div>
@endif
