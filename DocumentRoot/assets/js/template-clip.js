jQuery(function ($) {

    /**
     * Set le src de la video source a partir de la source sélectionnée
     */
    const source_url = $("#sources").find('option:selected').attr("name")
    $("#video-source").prop('src', source_url)
    $("#video-clip").prop('src', source_url)

    /**
     * Evenement quand le select de source change
     */
    $("#sources").on('select', function () {

        const path = this.find('option:selected').attr("name");

        // Mettre a jour la source du tag video source.
        $("#video-source").prop('src', path)

        // Mettre à jour la liste des extraits présents
        //Faire une requete et ajouter a la liste
    })

    /**
     * Timecodes bouttons
     */

    $("#btn_clip_start").click(function () {

        //On récupere le current time en secondes
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        $("#timecode_start").val(hh_mm_ss_lll)
    })

    $("#btn_clip_end").click(function () {
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        $("#timecode_end").val(hh_mm_ss_lll)
    })

    /**
     * Preview
     */

    $("#btn_preview").click(function () {
        const src = $("#video-source").prop('src')
        const src_timecodes = src + '#t=10,13'
        const $html_video_clip = $("#video-clip")
        $html_video_clip.prop('src', src_timecodes)
        $html_video_clip.trigger('play')

        /**
         * Loop preview
         */
        $html_video_clip.on('timeupdate', function () {


            if ($('#checkbox_loop_preview').is(':checked')) {
                loop_video(this, 10, 13)
            }
            else{
                if(video_cip_ends(this, 13))
                this.pause()
            }
        })



    })


    /**
     * Soumission du formulaire de création d'extrait
     */

    $("#form-clip-source").submit(function (event) {

        event.preventDefault();

        const data = $('form#form-clip-source').serialize() + '&PHPSESSID=' + PHPSESSID

        $.post('/api/v1/clip-source', data).done(function (data) {

            //Si le formulaire est rejeté on récupere les erreurs et on les affiche. A faire.
            console.log(data)

        }).fail(function () {
            console.log('Une erreur est survenue')
        })
    });
});


/**
 * Helper functions
 */

/**
   * Formate une durée en secondes au format hh:mm:ss.lll
   * @param {string} timecode_seconds 
   * @returns string
   */
function seconds_to_hh_mm_ss_lll(timecode_seconds) {

    const miliseconds = Math.floor((timecode_seconds - Math.floor(timecode_seconds)) * 1000)
    const miliseconds_formatted = miliseconds < 100 ? '0' + miliseconds : miliseconds

    const seconds = parseInt(timecode_seconds, 10)
    const seconds_formatted = seconds < 10 ? '0' + seconds : seconds

    const hours = Math.floor(timecode_seconds / 3600)
    const hours_formatted = hours < 10 ? '0' + hours : hours

    const minutes = Math.floor((timecode_seconds - (hours * 3600)) / 60);
    const minutes_formated = minutes < 10 ? '0' + minutes : minutes

    return `${hours_formatted}:${minutes_formated}:${seconds_formatted}.${miliseconds_formatted}`
}

/**
 * Joue la vidéo si le timecode courant arrive à la fin du timecode de fin
 * @param {string} video Element HTML video 
 * @param {*} start timecode de début (en secondes)
 * @param {*} end timecode de fin (en secondes)
 */
function loop_video(video, start, end) {
    if (video_cip_ends(video, end)) {
        video.currentTime = start;
        video.play();
    }
}


function video_cip_ends(video, end){
    return video.currentTime >= end
}