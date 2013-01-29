//this function should be called every time we load a partial view
//complete view. This shall instantiate various components for
//pages to work properly, including the UI components
function initComponents() {
    $(function () {
        //initialize datepicker component

        $(".datetime-input").datetimepicker({
            format:"dd MM yyyy h:ii",
            autoclose:true,
            todayBtn:false,
            minuteStep:15
        });

        $(".date-input").datetimepicker({
            format:"dd MM yyyy",
            autoclose:true,
            todayBtn:false,
            minuteStep:15
        });

    });
}