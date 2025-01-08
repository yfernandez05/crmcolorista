<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @section('head')
    @include('layouts.partials._head')

    <!-- Custom CSS -->
    <link href="{{asset('css/eliteadmin.css')}}" rel="stylesheet">
    <!-- page css -->
    <link href="{{asset('css/eliteadmin-auth.css')}}" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    @show
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    @include('layouts.partials._preloader')
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="login-register">

        <article class="ui-auth-about-sistem">
            <div class="card d-none">
                <div class="card-body text-success">
                    <h1 class="font-weight-bold">FAST FUNEEL <span class="text-version"> v1.1</span></h1>
                    <p class="font-weight-bold mb-2">de Gestión de relaciones con los clientes</p>
                </div>
            </div>
        </article>
        <article class="ui-auth-form-container">
            <div class="card w-100">
                <div class="card-body">
                    <div class="text-center">
                        <a href="javascript:void(0)">
                            <img src="{{asset('images/logo.png')}}" alt="Home" class="img-fluid"/>
                            <h4 class="mb-0 text-secondary">Sistema CRM</h4>
                        </a>
                    </div>
                    @yield('content')
                </div>
            </div>
        </article>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{asset('js/eliteadmin.js')}}"></script>

    <!--Custom JavaScript -->
    {{-- <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
    </script> --}}

</body>

</html>
