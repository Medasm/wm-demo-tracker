<div class="row">
    <div class="span3">
        <p>
            <label>Demo Date</label>

        <div class="input-append date date-input" data-date-format="dd M yyyy">
            <input class="span2" type="text" id="demoDate" ng-model="demoDate">
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

        <p>
            <label>Demo Status</label>
            <label class="checkbox">
                <input type="checkbox" ng-model="getNew"> New
            </label>
            <label class="checkbox">
                <input type="checkbox" ng-model="getAbsent"> Absent
            </label>
            <label class="checkbox">
                <input type="checkbox" ng-model="getEnrolled"> Enrolled [Paid Only]
            </label>
            <label class="checkbox">
                <input type="checkbox" ng-model="getFollowup"> Enrolled Later
            </label>
            <label class="checkbox">
                <input type="checkbox" ng-model="getNotInterested"> Not Interested
            </label>
        </p>
        <button class="btn btn-success" ng-click="getDemos()">&nbsp;&nbsp;&nbsp;Filter&nbsp;&nbsp;&nbsp;</button>
        <button class="btn btn-info pull-right" ng-click="exportData()">Export to Excel</button>

    </div>
    <div class="span9">

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Demo Date</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Course</th>
                <th>Faculty</th>
                <th>Counsellor</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody ng-show="demos.length>0">

            <tr ng-repeat="demo in demos" ng-class="getStatusCss(demo)">
                <td>{{ getFormattedDate(demo.demodate) }}</td>
                <td>{{ demo.name }}</td>
                <td>{{ demo.mobile }}</td>
                <td>{{ demo.program}}</td>
                <td>{{ demo.faculty}}</td>
                <td>{{ demo.counsellor}}</td>
                <td>{{ getStatus(demo) }}</td>
                <td style="max-width: 100px;">{{ getStatusText(demo) }}</td>
                <td>
                    <div ng-show="showControls(demo)" class="btn-group">
                        <a class="btn" href="#/demo/edit/{{demo.id}}">Edit</a>
                        <button class="btn dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a ng-click="setNew(demo)">New</a></li>
                            <li><a ng-click="showEnrollModal(demo)">Enrolled [Paid Only]</a></li>
                            <li><a ng-click="showFollowupModal(demo)">Enroll Later</a></li>
                            <li><a ng-click="setAbsent(demo)">Absent</a></li>
                            <li class="divider"></li>
                            <li><a ng-click="showNotInterestedModel(demo)">Not Interested</a></li>

                        </ul>
                    </div>
                </td>
            </tr>

            </tbody>
            <tbody ng-show="demos.length==0">
            <tr>
                <td colspan="9" style="text-align: center">
                    <br/>
                    <strong>
                        No Data found for given branch and date. Try selecting different date or branch.
                    </strong>
                    <br/>
                    <br/>
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
    <form name="enrolledForm">

        <div class="modal-body">
            <p>
                <label for="joining-date">Enrollment Date</label>
                <input id="joining-date" placeholder="enter enrollment date for the student"
                       type="text" class="span3 date date-input" ng-model="joiningDate">

                <label for="enrollment-remarks">Remarks</label>
                <textarea id="enrollment-remarks" class="span3" rows="5" ng-required="true"
                          ng-model="remarks"></textarea>
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button ng-click="setEnrolled()" ng-disabled="enrolledForm.$invalid" class="btn btn-primary">Enroll
                Student
            </button>
        </div>
    </form>
</div>

<!--    Followup Modal-->
<div id="followup-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="followup-modal-label"
     aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="followup-modal-label">Enroll Later</h4>
    </div>
    <form name="followupForm" novalidate>

        <div class="modal-body">
            <p>
                <label for="followup-date">Followup Date</label>
                <input id="followup-date" placeholder="enter enrollment date for the student"
                       type="text" class="span3 date date-input" ng-model="followupDate">

                <label for="followup-remarks">Remarks</label>
                <textarea id="followup-remarks" ng-required="true" class="span3" rows="5" ng-model="remarks"></textarea>
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button ng-click="setEnrollLater()" ng-disabled="followupForm.$invalid" class="btn btn-primary">Mark
                Followup
            </button>
        </div>
    </form>
</div>

<!--    not Interested Modal-->
<div id="not-interested-modal" class="modal hide fade" tabindex="-1" role="dialog"
     aria-labelledby="not-interested-modal-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="not-interested-modal-label">Not Interested</h4>
    </div>
    <form name="notInterestedForm" novalidate>
        <div class="modal-body">
            <p>
                <label for="not-interested-remarks">Remarks</label>
                <textarea ng-required="true" id="not-interested-remarks" rows="5" class="span3"
                          ng-model="remarks"></textarea>
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button ng-click="setNotInterested()" ng-disabled="notInterestedForm.$invalid" class="btn btn-primary">Mark
                Not Interested
            </button>
        </div>
    </form>
</div>


<script type="text/javascript">
    initComponents();
</script>

