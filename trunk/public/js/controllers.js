//for route /demo/add
function Demo_Add_Controller($scope, $http) {
    $scope.studentName = '';
    $scope.mobile = '';
    $scope.course = '';
    $scope.faculty = '';
    $scope.branchId = '';
    $scope.demoDate = '';

    $scope.addDemo = function () {
        $scope.demoDate = $('#demoDate').val();         //bad hack because of angular update issue
        $http.post(
            '/demo/post_add',
            {
                studentName:$scope.studentName,
                mobile:$scope.mobile,
                course:$scope.course,
                faculty:$scope.faculty,
                branchId:$scope.branchId,
                demoDate:$scope.demoDate
            }
        ).success(function (data) {
                alert(data);
            }).error(function (data) {
                //todo: log this
            });
    }
}


//for route /demo/list
function Demo_List_Controller($scope, $http) {

    $scope.demoDate = '12 Jan 2012';
    $scope.branchId = 1;
    $scope.demos = [];

    //action for getting demos data
    $scope.getDemos = function () {
        $scope.demoDate = $('#demoDate').val();
        $http.post(
            '/demo/post_list',
            {
                demoDate:$scope.demoDate,
                branchIds:[$scope.branchId]
            }
        ).success(function ($data) {
                $scope.demos = $data;
            }).error(function ($data) {
                //todo: work for error
            });
    }

    $scope.setEnrolled = function ($demo) {

    }

    $scope.setEnrollLater = function ($demo) {

    }

    $scope.setAbsent = function ($demo) {

    }

    $scope.setNotInterested = function ($demo) {

    }

    //get initial data for default date and branch
    $scope.getDemos();

}