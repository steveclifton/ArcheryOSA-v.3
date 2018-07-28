$(function () {

    $(document).on('submit', '.treeForm', function(e) {
        e.preventDefault();
        var selectedElmsIds = $('#checkTree').jstree("get_selected", true);

        var checked_ids = [];
        $.each(selectedElmsIds, function() {
            console.log(this.data.roundid);
            checked_ids.push(this.data.roundid);
        });

        document.getElementById('jsfields').value = checked_ids.join(",");

        this.submit();
    });



});