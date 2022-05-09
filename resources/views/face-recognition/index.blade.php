@extends('layouts.akb')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form id="dtr" class="form-inline">
                    <div class="form-group mb-2">
                        <input type="text" name="employee_no" class="form-control" id="employee_no"
                            placeholder="Employee Number">
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <select class="form-control form-control-lg" name="action" id="action">
                            <option value="none">--Please select action--</option>
                            <option value="am-in">AM IN</option>
                            <option value="am-out">AM OUT</option>
                            <option value="pm-in">PM IN</option>
                            <option value="pm-out">PM OUT</option>
                            <option value="ot-in">OT IN</option>
                            <option value="ot-out">OT OUT</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">PROCESS</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <video id="vide" width="400" height="400" autoplay muted></video>
            </div>
        </div>
    </div>

    <script>
        jQuery.validator.addMethod("notEqual", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please specify a different (non-default) value");
        $("#dtr").validate({
            rules: {
                employee_no: {
                    required: true
                },
                action: {
                    required: true,
                    notEqual: "none"
                }
            },
            submitHandler: function(form) {

                $.ajax({
                    url: "/api/capture-log/" + $('#employee_no').val() + "/" + $('#action').val(),
                    type: "POST",
                    data: {
                        employee_no: $('#employee_no').val(),
                        action: $('#action').val()
                    },
                    success: function(data) {
                        console.log(data)
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                })
                return false;
            }
        });
        // $('#dtr').on('submit', function(e) {
        //     e.preventDefault();
        //     var employee_no = $('#employee_no').val();
        //     var action = $('#action').val();

        //     if (employee_no !== "" && action !== "") {
        //         var data = {
        //             employee_no: employee_no,
        //             action: action
        //         };
        //         $.ajax({
        //             url: "/api/capture-log/" + employee_no + "/" + action
        //             type: "POST",
        //             data: data,
        //             success: function(data) {

        //             },
        //             error: function(xhr) {

        //             }
        //         })
        //     }
        //     return false;
        // })
    </script>
@endsection
