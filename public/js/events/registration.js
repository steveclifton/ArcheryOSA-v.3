$(function () {
    $(document).on('change', '.eventcompclass select', function(e) {
        $(this).closest('.eventcompclass').find('select').each(function(e) {
            $(this).attr('required', 'required');
        });
    });


    $(document).on('change', '#paymentType', function(e) {
        var value = $(this).find(":selected").val();

        console.log(value);
    });


});