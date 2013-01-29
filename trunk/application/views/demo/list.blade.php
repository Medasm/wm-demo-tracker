<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="span3">
        <p>
            <label>Demo Date</label>

        <div class="input-append date date-input" data-date-format="dd M yyyy">
            <input size="16" type="text" id="demoDate" ng-model="demoDate" value="" readonly>
            <span class="add-on"><i class="icon-remove"></i></span>
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        </p
        <p>
            <label>Branch</label>
            @foreach($branches as $branch)
            <label class="radio">
                <input type="radio" ng-model="branchId" value="<% $branch->id %>"> <% $branch->name %>
            </label>
            @endforeach
        </p>
        <button class="btn btn-block btn-info" ng-click="getDemos()">Filter</button>

    </div>
    <div class="span9">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Demo Date</th>
                <th>Course</th>
                <th>Faculty</th>
                <th>Branch</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>

            <tr ng-repeat="demo in demos">
                <td>{{ demo.name }}</td>
                <td>{{ demo.mobile }}</td>
                <td>{{ demo.demodate}}</td>
                <td>{{ demo.program}}</td>
                <td>{{ demo.faculty}}</td>
                <td>{{ demo.branch.name}}</td>
                <td>
                    <div class="btn-group pull-right">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Update Status
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a ng-click="setEnrolled(demo)">Enrolled</a></li>
                            <li><a ng-click="setEnrollLater(demo)">Enroll Later</a></li>
                            <li><a ng-click="setAbsent(demo)">Absent</a></li>
                            <li><a ng-click="setNotInterested(demo)">Not Interested</a></li>
                        </ul>
                    </div>
                </td>
            </tr>

            </tbody>

        </table>

    </div>
</div>

<script type="text/javascript">
    initComponents();
</script>