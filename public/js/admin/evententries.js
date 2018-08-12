$(function () {

    $(document).on('change', '.entrycheck', function () {
        var entryid = $(this).attr('data-entryid');
        var eventurl = $('meta[name="eventurl"]').attr('content');
        var _this = $(this);

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/ajax/events/manage/"+eventurl+"/approveentry/",
            data: {
                entryid: entryid
            }
        }).done(function( json ) {

            if (json.success) {
                $(_this).parent().siblings('#status').empty().html(json.message);
            }

        });

    });

    $(document).on('change', '.paidcheck', function () {
        var entryid = $(this).attr('data-entryid');
        var eventurl = $('meta[name="eventurl"]').attr('content');
        var _this = $(this);

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/ajax/events/manage/"+eventurl+"/approvepaid/",
            data: {
                entryid: entryid
            }
        }).done(function( json ) {

            if (json.success) {
                $(_this).parent().siblings('#paid').empty().html(json.message);
            }

        });

    });

    $(document).on('change', '.confirmemail', function () {
        var entryid = $(this).attr('data-entryid');
        var eventurl = $('meta[name="eventurl"]').attr('content');
        var _this = $(this);

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/ajax/events/manage/"+eventurl+"/sendconfirmation/",
            data: {
                entryid: entryid
            }
        }).done(function( json ) {

            if (json.success) {
                $(_this).prop('disabled', 'disabled');
            }

        });

    });


});