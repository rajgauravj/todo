<?php
namespace App;

use Session;
use Redirect;

use App\Tasks;

if(Session::has('user_id')){
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Task Builder</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <link rel="stylesheet" href="css/todo.css">
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
    <input type="hidden" id="user_id" value="{{Session::get('user_id')}}">
    <!-- container -->
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <div class="todo-box">
          <div class="row">
            <div class="col-sm-10">
              <input type="text" id="user_name" value=" Hi! {{Session::get('user_name')}}">
            </div>
            <div class="col-sm-2 pull-right">
              <a href="logout" class="btn btn-danger">Logout</a>
            </div>
          </div>
          <div class="row">
            <form class="task_input">
              <div class="form-group">
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="add_task" placeholder="Add your tasks here....">
                </div>
                <div class="col-sm-2">
                  <input type="button" class="btn btn-primary add_todo" value="Add Task" />
                </div>
              </div>
            </form>
          </div>
          <div class="row">
            <form class="tasks">
              <div class="form-group">
                <div class="col-sm-12 all_tasks">
                @if(isset($tasks))
                @foreach($tasks as $key=>$value)
                  @if($value['completion']=='pending')
                  <div class="alert alert-success" id="{{$value['id']}}" role="alert">
                    <input type="checkbox" id="{{$value['id']}}"><span class="task_{{$value['id']}}"><strong>{{$value['task']}}</strong></span><a class="delete_alert pull-right" id="{{$value['id']}}"><i class="fa fa-times"></i></a>
                  </div>
                  @elseif($value['completion']=='complete')
                  <div class="alert alert-warning" id="{{$value['id']}}" role="alert">
                    <input type="checkbox" id="{{$value['id']}}" checked><span class="task_{{$value['id']}}" style="text-decoration: line-through;"><strong>{{$value['task']}}</strong></span><a class="delete_alert pull-right" id="{{$value['id']}}"><i class="fa fa-times"></i></a>
                  </div>
                  @endif
                @endforeach 
                @endif
                </div>
              </div>
            </form>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <strong>All Tasks: </strong><span class="all_tasks_count">{{$all_tasks}}</span>
            </div>
            <div class="col-sm-4">
              <strong>Pending Tasks: </strong><span class="pending_tasks_count">{{$pending_tasks}}</span>
            </div>
            <div class="col-sm-4">
              <strong>Completed Tasks: </strong><span class="completed_tasks_count">{{$completed_tasks}}</span>
            </div>
          </div>
        </div>
      </div>
    </div> 
    <!-- /container -->
  </body>
  <script type="text/javascript">
    $(document).ready(function() {
      var all_tasks = $('.alert').length;
      var pending_tasks = $('.alert-success').length;
      var completed_tasks = $('.alert-warning').length;
      $('.add_todo').click(function() {
        var task = $('#add_task').val();
        var user_id = $('#user_id').val();
        var url = 'tasks_input'
        var data = 'task=' + task + "&user_id=" + user_id;
        $.ajax({
          type: "GET",
          url: url,
          data: data,
          success: function(data) {
            $(".all_tasks").append('<div class="alert alert-success" id="' + data.id + '" role="alert"><input type="checkbox" id="' + data.id + '"><span class="task_' + data.id + '"><strong>' + task + '</strong></span><a class="delete_alert pull-right" id="' + data.id + '"><i class="fa fa-times"></i></a></div>');
            all_tasks++;
            $('.all_tasks_count').html(all_tasks);
            pending_tasks++;
            $('.pending_tasks_count').html(pending_tasks);
            $('#add_task').val('');
          }
        });
      });
      $(document).on('click', '.delete_alert', function() {
        var id = $(this).attr('id');
        var class_div = $(this).closest('div').attr('class');
        var user_id = $('#user_id').val();
        var url = 'tasks_delete'
        var data = 'task_id=' + id + "&user_id=" + user_id;
        $.ajax({
          type: "GET",
          url: url,
          data: data,
          success: function(data) {
            if (data == 1) {
              all_tasks--;
              $('.all_tasks_count').html(all_tasks);
              if (class_div == 'alert alert-success') {
                pending_tasks--;
                $('.pending_tasks_count').html(pending_tasks);
              } else if (class_div == 'alert alert-warning') {
                completed_tasks--;
                $('.completed_tasks_count').html(completed_tasks);
              }
              $('#' + id).fadeOut(300, function() {
                $('div').remove('#' + id);
              });
            } else
              return false;
          }
        });
      });
      $(document).on('click', 'div.all_tasks input[type="checkbox"]', function() {
        var id = $(this).attr('id');
        if ($(this).prop("checked"))
          var completion = 'complete';
        else if (!$(this).prop("checked"))
          var completion = 'pending';
        var user_id = $('#user_id').val();
        var url = 'tasks_edit'
        var data = 'task_id=' + id + "&user_id=" + user_id + "&completion=" + completion;
        $.ajax({
          type: "GET",
          url: url,
          data: data,
          success: function(data) {
            if (data == 1) {
              if (completion == 'complete') {
                $('div#' + id).removeClass('alert-success');
                $('div#' + id).addClass('alert-warning');
                $('.task_' + id).css("text-decoration", "line-through");
                completed_tasks++;
                $('.completed_tasks_count').html(completed_tasks);
                pending_tasks--;
                $('.pending_tasks_count').html(pending_tasks);
              } else if (completion == 'pending') {
                $('div#' + id).removeClass('alert-warning');
                $('div#' + id).addClass('alert-success');
                $('.task_' + id).css("text-decoration", "none");
                completed_tasks--;
                $('.completed_tasks_count').html(completed_tasks);
                pending_tasks++;
                $('.pending_tasks_count').html(pending_tasks);
              }
            } else
              return false;
          }
        });
      });
    });
  </script>
</html>
<?php
}
else
  return Redirect::to('/');
?>