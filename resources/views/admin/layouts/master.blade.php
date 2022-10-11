<!DOCTYPE html>
<html>
    <head lang="en">
    @section('meta')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="{{ trans('midade/Overrides::Overrides/common.site_name') }}" />
    <meta name="description" content="{{ trans('midade/Overrides::Overrides/common.site_description') }}" />

    @show

    <title>
        @section('title'){{ trans('midade/Overrides::Overrides/common.site_name') }}@show
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('themes/admin-child/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('themes/admin-child/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('themes/admin-child/assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" />
    <!-- Include Choices CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <!-- Include Choices JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- axios -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
    .rtl{
        direction: rtl !important
    }
    .ltr{
        direction: ltr !important
    }
</style>

</head>
<body class="{{ App::currentLocale() == "ar" ? "rtl" : "ltr" }} g-sidenav-show  bg-gray-200">

    <div id="wrapper">
        <div id="overlayer"></div> 


        @include('admin.partials.header')
        
        
    </aside> 
        @yield('page')


        @if(Session::has('flash_message_error'))
            <div class="alert alert-danger alert-block">
                <strong>{!!session('flash_message_error')!!}</strong>
            </div>
        @endif
        
        @if(Session::has('flash_message_success'))
            <div class="alert alert-success alert-block">
                <strong>{!!session('flash_message_success')!!}</strong>
            </div>
        @endif

        @include('admin.partials.footer')
    </div>

    @include('admin.partials.scripts')
    @yield('scripts')

</body>

</html>
