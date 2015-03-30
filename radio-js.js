jQuery(document).ready(function($) {
    var radio = document.getElementById('radio_player');
    var play  = $('#radio_play');
    var mute  = $('#radio_mute');
    var volb  = $('#radio_volume');
    if (radio)
        radio.volume = volb.val();

    $('#radio_stations').change(function() {
        var src = $("select option:selected").attr('value');
        $('#radio_player').attr('src', src);
        radio.play();
    } );
    /*
     * //TODO maybe check if radio is playing and if yes, 
     * then on change play station, otherwise let the user do it.
    */

    play.click(function() {
        if ( radio.paused ) {
            radio.play();
            play.text("Pause");
        } else {
            radio.pause();
            play.text("Play");
        }
    });

    mute.click(function() {
        if ( radio.muted ) {
            radio.muted = false;
            mute.text("Mute");
        } else {
            radio.muted = true;
            mute.text("Muted");
        }
    });

    volb.change(function() {
        radio.volume = volb.val();
    });
});
