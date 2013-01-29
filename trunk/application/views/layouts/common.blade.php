<!doctype html>
<html lang="en" ng-app="main-app">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TBD</title>

    <!-- Css -->
    <% HTML::style('css/bootstrap.min.css') %>
    <% HTML::style('css/bootstrap-responsive.min.css') %>
    <% HTML::style('css/datetimepicker.css') %>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <% HTML::style('css/app.css') %>

    <!--Scripts-->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js"></script>

    <% HTML::script('js/bootstrap.min.js') %>
    <% HTML::script('js/bootstrap-datetimepicker.js') %>
    <% HTML::script('js/routes.js') %>
    <% HTML::script('js/controllers.js') %>
    <% HTML::script('js/app.js') %>

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Wisdom Mart - Demo Tracker</a>

            <div class="nav-collapse collapse">
                @if(Auth::check())
                <ul class="nav">
                    <li class="active"><a href="/">Home</a></li>
                    <li><a href="#demo/add">Add Demo</a></li>
                    <li><a href="#demo/list">Demos List</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#report/enrolled">Enrolled</a></li>
                            <li><a href="#report/enroll_later">Enroll Later</a></li>
                            <li><a href="#report/absentees">Absentees</a></li>
                            <li><a href="#report/not_interested">Not Interested</a></li>
                        </ul>
                    </li>
                    <li><a href="<% URL::to("/user/logout") %>">Logout</a></li>
                </ul>
                @endif
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container" ng-view>

</div>

</body>
</html>