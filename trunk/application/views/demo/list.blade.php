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
                <th>Status</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>

            <tr ng-repeat="demo in demos" ng-class="getStatusCss(demo)">
                <td>{{ demo.name }}</td>
                <td>{{ demo.mobile }}</td>
                <td>{{ getFormattedDate(demo.demodate) }}</td>
                <td>{{ demo.program}}</td>
                <td>{{ demo.faculty}}</td>
                <td>{{ demo.branch.name}}</td>
                <td>{{ getStatus(demo) }}</td>
                <td>
                    <div class="btn-group pull-right">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Update Status
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a ng-click="showEnrollModal(demo)">Enrolled</a></li>
                            <li><a ng-click="showFollowupModal(demo)">Enroll Later</a></li>
                            <li><a ng-click="setAbsent(demo)">Absent</a></li>
                            <li class="divider"></li>
                            <li><a ng-click="showNotInterestedModel(demo)">Not Interested</a></li>
                        </ul>
                    </div>
                </td>
            </tr>

            </tbody>

        </table>

    </div>
</div>

<!--    Different Modals to update status-->

<!--Enrollment Modal-->
<div id="enroll-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="enroll-modal-label"
     aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="enroll-modal-label">Enrolled</h4>
    </div>
    <div class="modal-body">
        <p>
            <label for="joining-date">Enrollment Date</label>
            <input id="joining-date" placeholder="enter enrollment date for the student"
                   type="text" class="span3 date date-input" ng-model="joiningDate">

            <label for="enrollment-remarks">Remarks</label>
            <textarea id="enrollment-remarks" class="span3" rows="5" ng-model="remarks"></textarea>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button ng-click="setEnrolled()" class="btn btn-primary">Enroll Student</button>
    </div>
</div>

<!--    Followup Modal-->
<div id="followup-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="followup-modal-label"
     aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="followup-modal-label">Enroll Later</h4>
    </div>
    <div class="modal-body">
        <p>
            <label for="followup-date">Followup Date</label>
            <input id="followup-date" placeholder="enter enrollment date for the student"
                   type="text" class="span3 date date-input" ng-model="followupDate">

            <label for="followup-remarks">Remarks</label>
            <textarea id="followup-remarks" class="span3" rows="5" ng-model="remarks"></textarea>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button ng-click="setEnrollLater()" class="btn btn-primary">Mark Followup</button>
    </div>
</div>

<!--    not Interested Modal-->
<div id="not-interested-modal" class="modal hide fade" tabindex="-1" role="dialog"
     aria-labelledby="not-interested-modal-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="not-interested-modal-label">Enroll Later</h4>
    </div>
    <div class="modal-body">
        <p>
            <label for="not-interested-remarks">Remarks</label>
            <textarea id="not-interested-remarks" rows="5" class="span3" ng-model="remarks"></textarea>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button ng-click="setNotInterested()" class="btn btn-primary">Mark Not Interested</button>
    </div>
</div>


<script type="text/javascript">
    initComponents();
</script>