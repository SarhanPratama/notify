<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Seroo - {{ $title }}</title>

    <!-- Include CSS -->
    @include('layouts.link')

    <!-- Custom Styles -->
    <style>
        .notify {
            z-index: 9999 !important;
        }

        .color {
            background-color: #6777ef;
        }

        .bg-maron {
            /* background-color: #8e1616; */
            background-color: #861414;
        }

        .text-maron {
            color: #8e1616;
        }
    </style>
</head>

<body id="page-top">
    <!-- Notifications -->
    @include('notify::components.notify')

    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                @include('layouts.navbar')

                <!-- Container Fluid -->
                <div class="container-fluid" id="container-wrapper">
                    <div class="row mb-3">
                        @yield('content')
                    </div>
                    <!-- Row -->
                </div>
                <!---Container Fluid-->
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

@include('layouts.script')
</body>

</html>
