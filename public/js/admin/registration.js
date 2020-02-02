$(function () {

    var users = {};

    $(document).on('keyup', '#searchUser', function() {
        var search = $(this).val();

        $('#searchResults').hide();

        if (isNaN(search) && search.length < 3 || search == '') {
            return;
        }

        $('#tableData').html('');

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/ajax/evententries/search",
            data: {
                search: search,
                eventid: $('input[name="eventid"]').val()
            }
        }).done(function( json ) {

            if (json.success) {
                json.data.forEach(function(value) {

                    var row =
                        '<tr>' +
                            '<th scope="row">'+value.email+'</th>' +
                            '<td>'+value.firstname+'</td>' +
                            '<td>'+value.lastname+'</td>' +
                            '<td><button type="button" class="btn addUser btn-success waves-effect waves-light" data-userid="'+value.userid+'">Add</button></td>' +
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
        $('input[name="membership"]').val(user.membership);
        $('input[name="phone"]').val(user.phone);
        $('.genderSelect option[value='+ user.gender +']').attr('selected','selected');
        $('.clubSelect option[value='+ user.clubid +']').attr('selected','selected');



    });

});