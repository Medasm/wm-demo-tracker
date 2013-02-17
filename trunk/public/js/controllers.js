//for route /demo/add
function Demo_Add_Controller($scope, $http) {
    $scope.studentName = '';
    $scope.mobile = '';
    $scope.course = '';
    $scope.faculty = '';
    $scope.branchId = '';
    $scope.counsellor = '';
    $scope.demoDate;

    $scope.addDemo = function () {
        $scope.demoDate = $('#demoDate').val();         //bad hack because of angular update issue

        //add demo as per current info
        $http.post(
            '/demo/post_add',
            {
                studentName: $scope.studentName,
                mobile: $scope.mobile,
                course: $scope.course,
                faculty: $scope.faculty,
                branchId: $scope.branchId,
                demoDate: $scope.demoDate,
                counsellor: $scope.counsellor
            }
        ).success(function (data) {
                //if demo is created, redirect to demo list
                window.location.href = "/#/demo/list/";
            }).error(function (data) {
                //todo: log this
            });
    }
}

//for route /demo/list
function Demo_List_Controller($scope, $http) {

    $scope.demoDate = dateFormat(new Date(), 'dd mmmm yyyy');
    $scope.branchId = 0;
    $scope.demos = [];
    $scope.currentDemo = null;

    //enrollment date for enrolled status
    $scope.joiningDate = null;

    //followup date for followup status
    $scope.followupDate = null;

    //remarks for various status
    $scope.remarks = '';
    $scope.getEnrolled = true;
    $scope.getFollowup = true;
    $scope.getAbsent = true;
    $scope.getNotInterested = true;
    $scope.getNew = true;


    $scope.getStatusArray = function () {
        var status = [];

        if ($scope.getEnrolled)
            status.push('enrolled');

        if ($scope.getAbsent)
            status.push('absent');

        if ($scope.getFollowup)
            status.push('follow_up');

        if ($scope.getNotInterested)
            status.push('not_interested');

        if ($scope.getNew)
            status.push('created');

        return status;
    }

    //action for getting demos data
    $scope.getDemos = function () {
        $scope.demoDate = $('#demoDate').val();
        $http.post(
            '/demo/post_list',
            {
                demoDate: $scope.demoDate,
                branchIds: [$scope.branchId],
                status: $scope.getStatusArray()
            }
        ).success(function ($data) {
                if (Array.isArray($data))
                    $scope.demos = $data;
                else
                    $scope.demos = [];
            }).error(function ($data) {
                //todo: work for error
            });
    }

    $scope.getStatusCss = function ($demo) {
        switch ($demo.demoStatus[0].status) {
            case 'absent':
                return 'warning';
                break;
            case 'enrolled':
                return 'success';
                break;
            case 'not_interested':
                return 'error';
                break;
            case 'follow_up':
                return 'info';
                break;

            default:
                break;
        }
    }

    $scope.getStatus = function ($demo) {
        switch ($demo.demoStatus[0].status) {
            case 'absent':
                return 'Absent';
                break;
            case 'enrolled':
                return 'Enrolled';
                break;
            case 'not_interested':
                return 'Not Interested';
                break;
            case 'follow_up':
                return 'Enroll Later';
                break;
            default:
                return 'New';
                break;
        }
    }

    $scope.getStatusText = function (demo) {
        return demo.demoStatus.length > 0 ? demo.demoStatus[0].remarks : "No Remarks Available";
    }

    //helper function for getting formatted date
    $scope.getFormattedDate = function ($date) {
        return dateFormat($date, 'dd mmm yyyy');
    }

    $scope.showEnrollModal = function ($demo) {
        $scope.currentDemo = $demo;
        $('#enroll-modal').modal('show');
    }

    $scope.showFollowupModal = function ($demo) {
        $scope.currentDemo = $demo;
        $('#followup-modal').modal('show');
    }

    $scope.showNotInterestedModel = function ($demo) {
        $scope.currentDemo = $demo;
        $('#not-interested-modal').modal('show');
    }

    $scope.setEnrolled = function () {
        if ($scope.currentDemo == null) {
            alert('try again');
            return;
        }

        $scope.joiningDate = $('#joining-date').val();

        $http.post(
            '/demo/mark_enrolled',
            {
                demoId: $scope.currentDemo.id,
                joiningDate: $scope.joiningDate,
                remarks: $scope.remarks
            }
        ).success(function ($demoStatus) {
                $scope.currentDemo.demoStatus.unshift($demoStatus);
                $('#enroll-modal').modal('hide');
                $scope.currentDemo = null;
                $scope.remarks = '';
            }).error(function ($data) {
                //todo: log this
            });

    }

    $scope.setEnrollLater = function ($demo) {
        if ($scope.currentDemo == null) {
            alert('try again');
            return;
        }

        $scope.followupDate = $('#followup-date').val();

        $http.post(
            '/demo/mark_followup',
            {
                demoId: $scope.currentDemo.id,
                followupDate: $scope.followupDate,
                remarks: $scope.remarks
            }
        ).success(function ($demoStatus) {
                $scope.currentDemo.demoStatus.unshift($demoStatus);
                $('#followup-modal').modal('hide');
                $scope.currentDemo = null;
                $scope.remarks = '';
            }).error(function ($data) {
                //todo: log this
            });
    }

    $scope.setAbsent = function ($demo) {
        $http.post('demo/mark_absent', {demoId: $demo.id}).success(function ($newDemoStatus) {
            $demo.demoStatus.unshift($newDemoStatus);
        });
    }

    $scope.setNotInterested = function ($demo) {
        if ($scope.currentDemo == null) {
            alert('try again');
            return;
        }

        $http.post(
            '/demo/mark_not_interested',
            {
                demoId: $scope.currentDemo.id,
                remarks: $scope.remarks
            }
        ).success(function ($demoStatus) {
                $scope.currentDemo.demoStatus.unshift($demoStatus);
                $('#not-interested-modal').modal('hide');
                $scope.currentDemo = null;
                $scope.remarks = '';
            }).error(function ($data) {
                //todo: log this
            });
    }

    $scope.exportData = function () {
        $scope.demoDate = $('#demoDate').val();
        $http.post(
            '/demo/export_data',
            {
                demoDate: $scope.demoDate,
                branchIds: [$scope.branchId],
                status: $scope.getStatusArray()
            }
        ).success(function ($data) {
                if ($data != false) {
                    var downloadFrame = '<iframe height="0" width="0" style="display:none" src="' + $data + '"></iframe>';
                    $(downloadFrame).appendTo('body');
                } else {
                    alert('No Data to Export!')
                }
            }).error(function ($data) {
                //todo: work for error
            });
    }

    //get initial data for default date and branch
    $scope.getDemos();
}


//for route /demo/list
function Demo_Follow_Up_Controller($scope, $http) {

    $scope.demoDate = dateFormat(new Date(), 'dd mmmm yyyy');
    $scope.branchId = 0;
    $scope.demos = [];
    $scope.currentDemo = null;

    //enrollment date for enrolled status
    $scope.joiningDate = null;

    //followup date for followup status
    $scope.followupDate = null;

    //remarks for various status
    $scope.remarks = '';
    $scope.getFollowup = true;

    //action for getting demos data
    $scope.getDemos = function () {
        $scope.demoDate = $('#demoDate').val();
        $http.post(
            '/demo/post_follow_up',
            {
                demoDate: $scope.demoDate,
                branchIds: [$scope.branchId]
            }
        ).success(function ($data) {
                if (Array.isArray($data))
                    $scope.demos = $data;
                else
                    $scope.demos = [];
            }).error(function ($data) {
                //todo: work for error
            });
    }

    $scope.getStatusCss = function ($demo) {
        switch ($demo.demoStatus[0].status) {
            case 'absent':
                return 'warning';
                break;
            case 'enrolled':
                return 'success';
                break;
            case 'not_interested':
                return 'error';
                break;
            case 'follow_up':
                return 'info';
                break;

            default:
                break;
        }
    }

    $scope.getStatus = function ($demo) {
        switch ($demo.demoStatus[0].status) {
            case 'absent':
                return 'Absent';
                break;
            case 'enrolled':
                return 'Enrolled';
                break;
            case 'not_interested':
                return 'Not Interested';
                break;
            case 'follow_up':
                return 'Enroll Later';
                break;
            default:
                return 'New';
                break;
        }
    }

    $scope.getStatusText = function (demo) {
        return demo.demoStatus.length > 0 ? demo.demoStatus[0].remarks : "No Remarks Available";
    }

    //helper function for getting formatted date
    $scope.getFormattedDate = function ($date) {
        return dateFormat($date, 'dd mmm yyyy');
    }

    $scope.showEnrollModal = function ($demo) {
        $scope.currentDemo = $demo;
        $('#enroll-modal').modal('show');
    }

    $scope.showFollowupModal = function ($demo) {
        $scope.currentDemo = $demo;
        $('#followup-modal').modal('show');
    }

    $scope.showNotInterestedModel = function ($demo) {
        $scope.currentDemo = $demo;
        $('#not-interested-modal').modal('show');
    }

    $scope.setEnrolled = function () {
        if ($scope.currentDemo == null) {
            alert('try again');
            return;
        }

        $scope.joiningDate = $('#joining-date').val();

        $http.post(
            '/demo/mark_enrolled',
            {
                demoId: $scope.currentDemo.id,
                joiningDate: $scope.joiningDate,
                remarks: $scope.remarks
            }
        ).success(function ($demoStatus) {
                $scope.currentDemo.demoStatus.unshift($demoStatus);
                $('#enroll-modal').modal('hide');
                $scope.currentDemo = null;
                $scope.remarks = '';
            }).error(function ($data) {
                //todo: log this
            });

    }

    $scope.setEnrollLater = function ($demo) {
        if ($scope.currentDemo == null) {
            alert('try again');
            return;
        }

        $scope.followupDate = $('#followup-date').val();

        $http.post(
            '/demo/mark_followup',
            {
                demoId: $scope.currentDemo.id,
                followupDate: $scope.followupDate,
                remarks: $scope.remarks
            }
        ).success(function ($demoStatus) {
                $scope.currentDemo.demoStatus.unshift($demoStatus);
                $('#followup-modal').modal('hide');
                $scope.currentDemo = null;
                $scope.remarks = '';
            }).error(function ($data) {
                //todo: log this
            });
    }

    $scope.setAbsent = function ($demo) {
        $http.post('demo/mark_absent', {demoId: $demo.id}).success(function ($newDemoStatus) {
            $demo.demoStatus.unshift($newDemoStatus);
        });
    }

    $scope.setNotInterested = function ($demo) {
        if ($scope.currentDemo == null) {
            alert('try again');
            return;
        }

        $http.post(
            '/demo/mark_not_interested',
            {
                demoId: $scope.currentDemo.id,
                remarks: $scope.remarks
            }
        ).success(function ($demoStatus) {
                $scope.currentDemo.demoStatus.unshift($demoStatus);
                $('#not-interested-modal').modal('hide');
                $scope.currentDemo = null;
                $scope.remarks = '';
            }).error(function ($data) {
                //todo: log this
            });
    }

    $scope.exportData = function () {
        $scope.demoDate = $('#demoDate').val();
        $http.post(
            '/demo/export_data_followup',
            {
                demoDate: $scope.demoDate,
                branchIds: [$scope.branchId]
            }
        ).success(function ($data) {
                if ($data != false) {
                    var downloadFrame = '<iframe height="0" width="0" style="display:none" src="' + $data + '"></iframe>';
                    $(downloadFrame).appendTo('body');
                }
                else {
                    alert('No Data to export');
                }
            }).error(function ($data) {
                //todo: work for error
            });
    }

    //get initial data for default date and branch
    $scope.getDemos();
}


function Demo_Edit_Controller($scope, $http, $routeParams) {
    $scope.studentName = '';
    $scope.mobile = '';
    $scope.course = '';
    $scope.faculty = '';
    $scope.branchId = '';
    $scope.counsellor = '';
    $scope.demoDate;
    $scope.id = $routeParams.id;

    $scope.editDemo = function () {
        $scope.demoDate = $('#demoDate').val();         //bad hack because of angular update issue

        //add demo as per current info
        $http.post(
            '/demo/post_edit',
            {
                id: $scope.id
            }
        ).success(function ($data) {
                //if demo is created, redirect to demo list
                $scope.studentName = $data.name;
                $scope.mobile = $data.mobile;
                $scope.course = $data.program;
                $scope.faculty = $data.faculty;
                $scope.branchId = $data.branch_id;
                $scope.counsellor = $data.counsellor;
                $scope.demoDate = $scope.getFormattedDate($data.demodate);

            }).error(function (data) {
                //todo: log this
            });
    }


    //helper function for getting formatted date
    $scope.getFormattedDate = function ($date) {
        return dateFormat($date, 'dd mmm yyyy');
    }

    $scope.editDemo();

    $scope.saveDemo = function () {
        $scope.demoDate = $('#demoDate').val();         //bad hack because of angular update issue

        //add demo as per current info
        $http.post(
            '/demo/post_saveEditDemo',
            {
                demoId: $scope.id,
                studentName: $scope.studentName,
                mobile: $scope.mobile,
                course: $scope.course,
                faculty: $scope.faculty,
                branchId: $scope.branchId,
                demoDate: $scope.demoDate,
                counsellor: $scope.counsellor
            }
        ).success(function ($data) {
                //if demo is created, redirect to demo list
                window.location.href = "/#/demo/list/";
            }).error(function (data) {
                //todo: log this
            });
    }
}