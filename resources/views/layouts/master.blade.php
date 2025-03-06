<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="{{ url('assest/img/logo/logo.png')}}" rel="icon">
  <title>RuangAdmin - Dashboard</title>

    @include('layouts.link')
   <style>
 .notify {
    z-index: 9999 !important;
}

.color {
    background-color: #6777ef;
}

   </style>
</head>

<body id="page-top">
    @include('notify::components.notify')
  <div id="wrapper">
    <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        @include('layouts.navbar')
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">

          <div class="row mb-3">
            @yield('content')
          </div>
          <!--Row-->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      @include('layouts.footer')
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  @notifyJs
  <script src="{{ url('vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ url('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
  <script src="{{ url('assets/js/ruang-admin.min.js')}}"></script>
  {{-- <script src="{{ url('vendor/chart.js/Chart.min.js')}}"></script> --}}



<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable();
      $('#dataTableHover').DataTable();
    });
  </script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  <script src="{{ url('assets/js/demo/chart-area-demo.js')}}"></script> --}}
</body>

</html>
