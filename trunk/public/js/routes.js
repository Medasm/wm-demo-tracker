//service for loader module
angular.module('LoaderServices', [])
    .config(function ($httpProvider) {
        $httpProvider.responseInterceptors.push('appHttpInterceptor');
        var spinnerFunction = function (data, headersGetter) {
            // todo start the spinner here
            $('#ajax-loader').show();

            return data;
        };
        $httpProvider.defaults.transformRequest.push(spinnerFunction);
    })
// register the interceptor as a service, intercepts ALL angular ajax http calls
    .factory('appHttpInterceptor', function ($q, $window) {
        return function (promise) {
            return promise.then(function (response) {
                // todo hide the spinner
                $('#ajax-loader').hide();
                return response;

            }, function (response) {
                // todo hide the spinner
                $('#ajax-loader').hide();
                return $q.reject(response);
            });
        };
    })


//Define routes for the application
angular.module('main-app', ['LoaderServices']).
    config(['$routeProvider', function ($routeProvider) {
        $routeProvider.
            when('/', {templateUrl: '/demo/list', controller: Demo_List_Controller}).
            when('/home/about', {templateUrl: '/home/about'}).
            when('/home/contact', {templateUrl: '/home/contact'}).

            // -------------- Login Routes ----------------
            when('/user/login', {templateUrl: '/user/login'}).
            when('/user/post_login', {templateUrl: '/user/post_login'}).
            when('/user/logout', {templateUrl: '/user/logout'}).

            //-------------- Demo Route -------------------
            when('/demo/', {templateUrl: '/demo/list', controller: Demo_List_Controller}).
            when('/demo/add', {templateUrl: '/demo/add', controller: Demo_Add_Controller}).
            when('/demo/list', {templateUrl: '/demo/list', controller: Demo_List_Controller}).
            when('/demo/follow_up', {templateUrl: '/demo/follow_up', controller: Demo_Follow_Up_Controller}).

            //-------------- Report Route -------------------
            when('/report/', {templateUrl: '/report/'}).
            when('/report/enrolled', {templateUrl: '/report/enrolled'}).
            when('/report/enroll_later', {templateUrl: '/report/enroll_later'}).
            when('/report/absentees', {templateUrl: '/report/absentees'}).
            when('/report/not_interested', {templateUrl: '/report/not_interested'}).

            //--------------- Default URL ------------------
            otherwise({redirectTo: '/'});

        //todo: call init components when route change has happened and new view has been loaded in dom

    }]);


