function pageCtrl($scope, $http, $window) {
    $scope.email = "";
    $scope.password = "";

    $scope.submit = function () {
        $http.post(
            '/user/post_login',
            {
                'email':$scope.email,
                'password':$scope.password
            }
        ).success(function (data) {
                if (data.status == true) {
                    $window.location.href = data.url;
                    console.log('logged in')
                }
            });
    }
}