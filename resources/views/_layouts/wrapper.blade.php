<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Live 9 Score - Soccer livescore</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="{{ asset('css/small-business.css') }}" rel="stylesheet">

</head>

<body class="bg-dark">

  <!-- Page Content -->
  <div class="container">

    <!-- Heading Row -->
    {{-- <div class="row align-items-center mb-5">
        <div class="col-lg-12">
            <img class="img-fluid rounded mb-12 mb-lg-0" src="http://placehold.jp/1140x200.png" alt="">
        </div>
    </div> --}}
    <!-- /.row -->
    
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center border-0">
                <div class="card-header px-0 ls-bg-dark">
                    <div class="row mx-0">
                        <div class="col-md-2 pr-0">
                            <a href="/">
                                <img style="width:100%;" src="{{asset('img/logo.png')}}" alt="logo">
                            </a>
                        </div>
                        <div class="col-md-9 offset-md-1 px-0 main-nav-wrapper">
                            <ul class="nav nav-tabs card-header-tabs mx-0" style="height: 100%;">
                                <div class="row w-100 mx-0">
                                    <div class="col px-0">
                                        <li class="nav-item main-nav">
                                            <a class="nav-link {{ request()->is('/') || request()->is('match/*') ? 'active' : '' }}" href="/">Soccer</a>
                                        </li>
                                    </div>
                                    <div class="col px-0">
                                        <li class="nav-item main-nav">
                                        <a class="nav-link {{ request()->is('uefa') ? 'active' : '' }}" href="{{ route('homepage.uefa') }}">Champions League</a>
                                        </li>
                                    </div>
                                    <div class="col px-0">
                                        <li class="nav-item main-nav">
                                        <a class="nav-link {{ request()->is('eu-league') ? 'active' : '' }}" href="{{ route('homepage.eu-league') }}">Europa League</a>
                                        </li>
                                    </div>
                                    <div class="col px-0">
                                        <li class="nav-item main-nav">
                                            <a class="nav-link {{ request()->is('euro') ? 'active' : '' }}" href="{{ route('homepage.euro') }}">EURO</a>
                                        </li>
                                    </div>
                                    <div class="col px-0">
                                        <li class="nav-item main-nav">
                                            <a class="nav-link {{ request()->is('world-cup') ? 'active' : '' }}"
                                                href="{{ route('homepage.world-cup') }}"
                                                >
                                                World Cup
                                            </a>
                                        </li>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0 px-0">
                    
                    @yield('content')

                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <!-- Footer -->
    <footer class="py-5 ls-bg-dark">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2019</p>
    </footer>

  </div>
  <!-- /.container -->

  <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  @yield('script')

</body>

</html>
