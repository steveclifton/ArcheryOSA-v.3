$(function () {

    var users = {};

    $(document).on('keyup', '#searchUser', function() {
        let search = $(this).val();
        $('#searchResults').hide();
        if (isNaN(search) && search.length < 3 || search == '') {
            return;
        }
        $('#tableData').empty();

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/ajax/evententries/search",
            data: {
                search: search
            }
        }).done(function( json ) {

            if (json.success) {
                json.data.forEach(function(value) {

                    var row = '<tr>\n' +
                        '<th scope="row">'+value.userid+'</th>\n' +
                        '<td>'+value.firstname+'</td>\n' +
                        '<td>'+value.lastname+'</td>\n' +
                        '<td><button type="button" class="btn addUser btn-success waves-effect waves-light" data-userid="'+value.userid+'">Add</button></td>\n' +
                        '</tr>';

                    $('#tableData').append(row);
                    users[value.userid] = value;
                });

                $('#searchResults').show();
            }
        });
    });

    $(document).on('click', '.addUser', function () {
        var user = users[$(this).attr('data-userid')];


        if (typeof user === 'undefined') {
            alert('Something went wrong, please try again later');
            return;
        }

        $('#searchResults').toggle();

        $('input[name="userid"]').val(user.userid);
        $('input[name="firstname"]').val(user.firstname);
        $('input[name="lastname"]').val(user.lastname);
        $('input[name="email"]').val(user.email);


    });

    $(document).on('submit', '.treeForm', function(e) {
        e.preventDefault();

        // var confirmed = confirm('Warning - changing divisons/rounds will result in some scores being lost. Ok?');
        //
        // if (!confirmed) {
        //     return;
        // }

        // Competitions and rounds
        var selectedElmsIds = $('#checkTree').jstree("get_selected", true);
        var checkedRounds = [];
        $.each(selectedElmsIds, function() {
            if (typeof this.data.roundid !== 'undefined' && typeof this.data.eventcompetitionid !== 'undefined') {
                checkedRounds.push(this.data.eventcompetitionid + '-' + this.data.roundid);
            }
        });

        var selected = [];
        $('#checkb input:checked').each(function() {
            selected.push($(this).attr('value'));
        });

        $('#mDivid').val(selected.join(','));


        if (checkedRounds.length == 0 && $('#jsfields').val() == '') {
            $('#comperror').removeClass('hidden');
            return false;
        }
        // noinspection EqualityComparisonWithCoercionJS
        if ($('#jsfields').val() == '') {
            $('#jsfields').attr('value', checkedRounds.join(","));
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
    }

    $('#checkTree').jstree(jsTreeObj);

});