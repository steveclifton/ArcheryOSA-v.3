$(function () {
    $(document).on('submit', '.treeForm', function(e) {
        e.preventDefault();

        // Competitions and rounds
        var selectedElmsIds = $('#checkTree').jstree("get_selected", true);
        var checkedCompetitions = [];
        $.each(selectedElmsIds, function() {
            if (this.data.roundid != '') {
                checkedCompetitions.push(this.data.roundid);
            }
        });
        document.getElementById('jsfields').value = checkedCompetitions.join(",");

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