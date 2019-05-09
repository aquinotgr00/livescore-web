<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Small Business - Start Bootstrap Template</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="{{ asset('css/small-business.css') }}" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  {{-- <nav class="navbar navbar-expand-lg navbar-dark fixed-top ls-bg-blue">
    <div class="container">
      <a class="navbar-brand" href="#">Start Bootstrap</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav> --}}

  <!-- Page Content -->
  <div class="container">

    <!-- Heading Row -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-12">
            <img class="img-fluid rounded mb-12 mb-lg-0" src="http://placehold.jp/1140x200.png" alt="">
        </div>
    </div>
    <!-- /.row -->
    
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12 mb-5">
            <div class="card text-center">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <div class="row w-100">
                            <div class="col">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#">Soccer</a>
                                </li>
                            </div>
                            <div class="col">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">World Cup</a>
                                </li>
                            </div>
                            <div class="col">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Hockey</a>
                                </li>
                            </div>
                            <div class="col">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Basketball</a>
                                </li>
                            </div>
                            <div class="col">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Tennis</a>
                                </li>
                            </div>
                            <div class="col">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Cricket</a>
                                </li>
                            </div>
                            <div class="col">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Contact</a>
                                </li>
                            </div>
                        </div>
                    </ul>
                </div>
                <div class="card-body">
                    
                    @yield('content')

                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <!-- Footer -->
    <footer class="py-5 ls-bg-blue">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2019</p>
    </footer>

  </div>
  <!-- /.container -->

  <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
