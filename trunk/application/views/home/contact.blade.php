<div class="row" ng-controller="contactCtrl">

    <div class="span12">
        <input type="text" ng-model="name">

        This is contact page {{name}}

    </div>

</div>


<script type="text/javascript">

    function contactCtrl($scope) {
        $scope.name = "naveen";
    }

</script>

