function pageCtrl($scope, $http, $window) {
    $scope.email = "";
    $scope.password = "";
    $scope.showError = false;

    $scope.submit = function () {
        $scope.showError = false;
        $http.post(
            '/user/post_login',
            {
                'email':$scope.email,
                'password':$scope.password
            }
        ).success(function (data) {
                if (data.status == true) {
                    window.location.href = data.url;
                } else {
                    $scope.showError = true;
                }
            });
    }
}