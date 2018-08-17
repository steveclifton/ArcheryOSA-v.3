$(function () {
    $(document).on('submit', '.treeForm', function(e) {
        e.preventDefault();

        // Competitions and rounds
        var selectedElmsIds = $('#checkTree').jstree("get_selected", true);
        var checkedRounds = [];
        $.each(selectedElmsIds, function() {

            if (typeof this.data.roundid !== 'undefined' && typeof this.data.eventcompetitionid !== 'undefined') {
                checkedRounds.push(this.data.eventcompetitionid + '-' + this.data.roundid);
            }
        });


        if (checkedRounds.length == 0 ) {
            $('#comperror').removeClass('hidden');
            return false;
        }

        document.getElementById('jsfields').value = checkedRounds.join(",");
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
    }

    $('#checkTree').jstree(jsTreeObj);

});