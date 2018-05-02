<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <title>{{ $pagetitle }}</title>
        <!-- Latest compiled and minified CSS & JS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="jumbotron">
          <h1>{{ $pagetitle }}</h1>
        </div>
        @yield('content')
     </div>
    <script src="//code.jquery.com/jquery.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</body>
</html>
