$('.close').click( function() {
    $(this).closest('.row').addClass('row-hidden');
});

/* Shows 'info' or 'error' messages */
function show_message(type, messages) {
    var type = '#' + type + '-blk';
    $(type).empty();
    messages.forEach( function(message) {
        $(type).append('<span class="msg">' + message + '</span>');
    });
    $(type).closest('.row').removeClass('row-hidden');
}

function hide_messages() {
    $('.msg').html('');
    $('#info-blk').closest('.row').addClass('row-hidden');
    $('#error-blk').closest('.row').addClass('row-hidden');
}

function showMessage(message, type = 0, duration = 7000) {
    $message = $('.popup');
    if (type == 0) {
        $message.css('background-color', '#38c172');
    }else{
        $message.css('background-color', '#C0392B');
    }
    // (type == 0 ?  : $message.css('background-color', '#C0392B'));
    $message.html(message).slideDown();
    setTimeout(function(){
        $message.slideUp();
    },duration);
}