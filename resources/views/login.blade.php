<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Login</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <link rel="stylesheet" href="css/signin.css">
    <link rel="stylesheet" href="css/font-awesome.css">


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
    <div class="col-md-4 col-md-offset-4">
      <div class="signin-box">
        <a href="{{$loginUrl}}" class="btn btn-block btn-social btn-lg btn-facebook"><i class="fa fa-facebook"></i>Sign in with Facebook</a>
        @if(Session::has('access_error'))
        <div class="row">
          <div class="col-md-12 error">
            <div class="alert alert-danger" role="alert"> <strong>Access Error!</strong> You have not authorized the App. Please click the below link if you like to Re-Authorize the App. </div>
            <a href="{{$reAuthUrl}}" class="btn btn-block btn-social btn-sm btn-facebook"><i class="fa fa-facebook"></i>Re-Authorize Facebook</a>
          </div>
        </div>
        @endif
        @if(Session::has('graph_error'))
         <div class="row">
          <div class="col-md-12 error">
            <div class="alert alert-danger" role="alert"> <strong>Graph Error!</strong> {{Session::get('graph_error')}} Please Login Again!</div>
          </div>
        </div>
        @endif
        @if(Session::has('response_error'))
         <div class="row">
          <div class="col-md-12 error">
            <div class="alert alert-danger" role="alert"> <strong>Response Error!</strong> {{Session::get('response_error')}} Please Login Again!</div>
          </div>
        </div>
        @endif
      </div>
     </div>
    </div> <!-- /container -->
  </body>
</html>