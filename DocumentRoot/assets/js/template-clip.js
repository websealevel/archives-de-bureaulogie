jQuery(function ($) {

    /**
     * Set le src de la video source a partir de la source sélectionnée
     */
    const source_url = $("#sources").find('option:selected').attr("name")
    $("#video-source").prop('src', source_url)
    $("#video-clip").prop('src', source_url)

    /**
     * Timer
     */
    $("#video-source").on('timeupdate', function(){
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        $("#current-time").html(hh_mm_ss_lll)
    })

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
     * Pause
     */

    /**
     * Timecodes bouttons
     */

    $("#btn_clip_start").click(function () {

        //On récupere le current time en secondes
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        console.log(hh_mm_ss_lll)
        $("#timecode_start").val(hh_mm_ss_lll)
    })

    $("#btn_clip_end").click(function () {
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        console.log(hh_mm_ss_lll)
        $("#timecode_end").val(hh_mm_ss_lll)
    })

    /**
     * Preview
     */

    $("#btn_preview").click(function () {

        const src = $("#video-source").prop('src')

        const timecode_start = $("#timecode_start").val()
        const timecode_end = $("#timecode_end").val()

        const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)
        const timecode_end_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)

        if (timecode_end_in_sec <= timecode_start_in_sec) {
            $("div.errors").html("<p>Impossible de prévisualiser l'extrait : le timecode de fin doit être plus grand que le timecode de début</p>")

            $("#timecode_start").addClass('error')
            $("#timecode_end").addClass('error')

            return
        }
        else{
            $("div.errors").html('')
            $("#timecode_start").removeClass('error')
            $("#timecode_end").removeClass('error')
        }

        const src_timecodes = src + `#t=${timecode_start_in_sec},${timecode_end_in_sec}`

        const $html_video_clip = $("#video-clip")
        $html_video_clip.prop('src', src_timecodes)
        $html_video_clip.trigger('play')

        /**
         * Loop preview
         */
        $html_video_clip.on('timeupdate', function () {

            if ($('#checkbox_loop_preview').is(':checked')) {
                loop_video(this, timecode_start_in_sec, timecode_end_in_sec)
            }
            else {
                if (has_reached_end(timecode_start_in_sec, timecode_end_in_sec))
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
 * Formate une durée au format hh:mm:ss.lll en secondes
 * @param {string} timecode_hh_mm_ss_lll 
 * @returns string
 */
function hh_mm_ss_lll_to_seconds(timecode_hh_mm_ss_lll) {

    const h = timecode_hh_mm_ss_lll.substring(0, 1)
    const m = timecode_hh_mm_ss_lll.substring(3, 4)
    const s = timecode_hh_mm_ss_lll.substring(6, 8)
    const l = timecode_hh_mm_ss_lll.substring(9, 11)

    const seconds = parseInt(h) * 3600 + parseInt(m) * 60 + parseInt(s) + parseInt(l) / 1000

    console.log('Conversion : ', timecode_hh_mm_ss_lll, seconds)

    return seconds

}

/**
 * Joue la vidéo si le timecode courant arrive à la fin du timecode de fin
 * @param {string} video Element HTML video 
 * @param {*} start timecode de début (en secondes)
 * @param {*} end timecode de fin (en secondes)
 */
function loop_video(video, start, end) {
    if (has_reached_end(video, end)) {
        video.currentTime = start;
        video.play();
    }
}

/**
 * Retourne vrai si la vidéo est arrivée au timecode de fin, faux sinon
 * @param {*} video Element HTML video
 * @param {*} end timecode de fin (en secondes)
 * @returns 
 */
function has_reached_end(video, end) {
    return video.currentTime >= end
}