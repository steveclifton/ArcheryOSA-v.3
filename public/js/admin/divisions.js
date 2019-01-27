$(function () {

    $(document).on('submit', '.treeForm, .treeFormCompetitions', function(e) {
        e.preventDefault();
        $('#diverror').addClass('hidden');
        $('#comperror').addClass('hidden');
        $('#nameerror').addClass('hidden');


        // Competitions and rounds
        var selectedElmsIds = $('#checkTree').jstree("get_selected", true);
        var checkedRounds = [];
        $.each(selectedElmsIds, function() {
            if (typeof this.data.roundid !== 'undefined' && this.data.roundid != '') {
                checkedRounds.push(this.data.roundid);
            }
        });
        document.getElementById('roundfields').value = checkedRounds.join(",");


        // Divisions
        var selectedElmsIds = $('#checkTreeDivisions').jstree("get_selected", true);

        var checkedDivisions = [];
        $.each(selectedElmsIds, function() {
            if (typeof this.data.divisionid !== 'undefined' && this.data.divisionid != '') {
                checkedDivisions.push(this.data.divisionid);
            }
        });


        document.getElementById('divisionfields').value = checkedDivisions.join(",");

        var errors = false;
        if (checkedDivisions.length == 0 ) {
            $('#diverror').removeClass('hidden');
            errors = true;
        }

        if (checkedRounds.length == 0 ) {
            $('#comperror').removeClass('hidden');
            errors = true;
        }

        if ($('#inputLabel').val() == '') {
            $('#nameerror').removeClass('hidden');
            errors = true;
        }

        if (errors) {
            return false;
        }

        this.submit();
    });

    var jsTreeObj = {
        'core' : {
            'check_callback' : true,
            'themes' : {
                'responsive': false
            }
        },
        'types' : {
            'default' : {
                'icon' : 'fa fa-folder'
            },
            'file' : {
                'icon' : 'fa fa-file'
            }
        },
        'plugins' : ['types', 'checkbox']
    };

    $('#checkTree').jstree(jsTreeObj);
    $('#checkTreeDivisions').jstree(jsTreeObj);



    $(document).on('change', '#eventcompetitions', function (e) {

        var date = $("option:selected", this).val();
        var eventid = $('input[name="eventid"]').val();
        var eventcompetitionid = $("option:selected", this).attr('data-cid');


        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/ajax/events/manage/competition",
            data: {
                date:date,
                eventid: eventid,
                eventcompetitionid:eventcompetitionid
            }
        }).done(function( json ) {

            if (json.success) {
                $('#ajaxFormReplace').empty();
                $('#ajaxFormReplace').append(json.html);

                $('#checkTree').jstree(jsTreeObj);
                $('#checkTreeDivisions').jstree(jsTreeObj);

                $(".treeFormCompetitions").attr("action", json.formaction);
            }

        });


    });




});