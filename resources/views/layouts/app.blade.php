 <!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body class="bg-gray-100 g-sidenav-show">
    @include('partials.sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('partials.navbar')
    <!-- End Navbar -->
    <div class="py-4 container-fluid">
      @yield('content')

    </div>
  </main>

  <!--   Core JS Files   -->
  @include('partials.js')
  @stack('scripts')
  
</body>

</html>