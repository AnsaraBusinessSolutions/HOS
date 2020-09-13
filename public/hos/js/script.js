// $('#others').click(function() {
	
//     if ($(this).is(':checked')) {
//         $('input').attr('checked', true);
//     } else {
//         $('input').attr('checked', false);
//     }
// });

$("#others").click(function () {
    if ($(this).is(":checked")) {
        $("#chkres").show();
    } else {
        $("#chkres").hide();
    }
});