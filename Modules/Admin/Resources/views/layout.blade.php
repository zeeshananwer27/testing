<!DOCTYPE html>
<html lang="{{ locale() }}">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <base href="{{ url('/') }}">
        
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @yield('title') - Boletazo Admin
        </title>

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:600|Roboto:400,500" rel="stylesheet">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        @foreach ($assets->allCss() as $css)
            <link media="all" type="text/css" rel="stylesheet" href="{{ v($css) }}">
        @endforeach

        @stack('styles')

        @include('admin::partials.globals')
    </head>

    <body class="skin-blue sidebar-mini offcanvas clearfix {{ is_rtl() ? 'rtl' : 'ltr' }}">
        <div class="left-side"></div>

        @include('admin::partials.sidebar')

        <div class="wrapper">
            <div class="content-wrapper">
                @include('admin::partials.top_nav')

                <section class="content-header clearfix">
                    @yield('content_header')
                </section>

                <section class="content">
                    @include('admin::partials.notification')

                    @yield('content')
                </section>

                <div id="notification-toast"></div>
            </div>
        </div>

        @include('admin::partials.footer')

        @include('admin::partials.confirmation_modal')

        @foreach($assets->allJs() as $js)
            <script src="{{ v($js) }}"></script>
        @endforeach

        @stack('scripts')
    </body>
</html>
