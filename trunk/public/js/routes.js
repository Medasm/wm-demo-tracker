//Define routes for the application

angular.module('main-app', []).
    config(['$routeProvider', function ($routeProvider) {
    $routeProvider.
        when('/', {templateUrl:'/demo/list', controller:Demo_List_Controller}).
        when('/home/about', {templateUrl:'/home/about'}).
        when('/home/contact', {templateUrl:'/home/contact'}).

        // -------------- Login Routes ----------------
        when('/user/login', {templateUrl:'/user/login'}).
        when('/user/post_login', {templateUrl:'/user/post_login'}).
        when('/user/logout', {templateUrl:'/user/logout'}).

        //-------------- Demo Route -------------------
        when('/demo/', {templateUrl:'/demo/list'}).
        when('/demo/add', {templateUrl:'/demo/add', controller:Demo_Add_Controller}).
        when('/demo/list', {templateUrl:'/demo/list', controller:Demo_List_Controller}).

        //-------------- Report Route -------------------
        when('/report/', {templateUrl:'/report/'}).
        when('/report/enrolled', {templateUrl:'/report/enrolled'}).
        when('/report/enroll_later', {templateUrl:'/report/enroll_later'}).
        when('/report/absentees', {templateUrl:'/report/absentees'}).
        when('/report/not_interested', {templateUrl:'/report/not_interested'}).

        //--------------- Default URL ------------------
        otherwise({redirectTo:'/'});

    //todo: call init components when route change has happened and new view has been loaded in dom

}]);


