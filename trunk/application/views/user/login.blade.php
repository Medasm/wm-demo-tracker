<div class="row" ng-controller="pageCtrl">
    <div class="span4 offset4 well">
        <legend>Please Sign In</legend>
        <div class="alert alert-error" ng-show="showError">
            Incorrect Username or Password!
        </div>

        <form id='form-login' action="#" accept-charset="UTF-8">
            <input type="text" class="span4" ng-required="true" ng-model="email" placeholder="Email">
            <input type="password" class="span4" ng-required="true" ng-model="password" placeholder="Password">
            <label class="checkbox">
                <input type="checkbox" name="remember" value="true"> Remember Me
            </label>
            <button type="button" ng-click="submit()" class="btn btn-info btn-block">Sign in</button>
        </form>
    </div>
</div>

<% HTML::script('js/views/user/login.js') %>