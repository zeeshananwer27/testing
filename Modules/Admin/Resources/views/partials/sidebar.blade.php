<aside class="main-sidebar">
    <header class="main-header clearfix"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <a class="logo" href="{{ route('admin.dashboard.index') }}">
            <span class="logo-lg">Boletazo</span>
        </a>

        <a href="javascript:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i aria-hidden="true" class="fa fa-bars"></i>
        </a>
    </header>

    <section class="sidebar">
        {!! $sidebar !!}
    </section>
</aside>
