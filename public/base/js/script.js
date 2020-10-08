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