<div class="row" ng-controller="contactCtrl">

    <div class="span12">
        <input type="text" ng-model="age">

        This is about page {{age}}

    </div>

</div>


<script type="text/javascript">

    function contactCtrl($scope) {
        $scope.age = "1";
    }

</script>

