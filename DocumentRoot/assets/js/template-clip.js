jQuery(function ($) {

    /**
     * Set le src de la video source a partir de la source sélectionnée
     */
    const source_url = $("#sources").find('option:selected').attr("name")
    $("#video-source").prop('src', source_url)
    $("#video-clip").prop('src', source_url)
    $("#source_name").val(source_url)


    /**
     * Timer
     */
    $("#video-source").on('timeupdate', function () {
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        $("#current-time").html(hh_mm_ss_lll)
    })



    /**
     * Evenement quand le select de source change
     */
    $("#sources").change(function () {
        const source_url = $(this).find(":selected").attr('name')
        // Mettre a jour la source du tag video source.
        $("#video-source").prop('src', source_url)
        $("#video-clip").prop('src', source_url)
        $("#source_name").val(source_url)

        // Mettre à jour la liste des extraits présents
        //Faire une requete et ajouter a la liste
    })


    /**
     * Pause un lecteur vidéo quand l'autre est joué et vice versa
     */
    $("#video-source").on('play', function () {
        $("#video-clip").trigger('pause')
    })

    $("#video-clip").on('play', function () {
        $("#video-source").trigger('pause')
    })

    /**
     * Timecodes bouttons
     */

    $("#btn_clip_start").click(function () {
        set_timecode_start()
    })

    $("#btn_clip_end").click(function () {
        set_timecode_end()
    })

    /**
     * Rewind/Forward buttons
     */

    $("#btn_rewind_5_s").click(function () {
        shift_current_time(-5)
    })

    $("#btn_rewind_1_s").click(function () {
        shift_current_time(-1)
    })

    $("#btn_forward_1_s").click(function () {
        shift_current_time(1)

    })

    $("#btn_forward_5_s").click(function () {
        shift_current_time(5)

    })

    /**
     * Avance le temps courant du lecteur video source de x secondes
     * @param {*} delay_in_s L'avance en seconde à donner au currentTime (peut etre positif ou négatif)
     */
    function shift_current_time(delay_in_s) {
        const delay = delay_in_s
        const currentTime = $("#video-source").prop('currentTime')
        const time = (currentTime + delay) < 0 ? 0 : currentTime + delay
        $("#video-source").prop('currentTime', time)
    }

    /**
     * Met à jour le timecode de départ avec le temps courant du player video source
     */
    function set_timecode_start() {
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        $("#timecode_start").val(hh_mm_ss_lll)
        update_clip_duration()
    }

    /**
    * Met à jour le timecode de fin avec le temps courant du player video source
    */
    function set_timecode_end() {
        const timecode_seconds = $("#video-source").prop("currentTime")
        const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
        $("#timecode_end").val(hh_mm_ss_lll)
        update_clip_duration()
    }

    /**
     * Raccourcis clavier
     */

    const keyboard_controls = {
        rewind_5_s: {
            key: 'Q',
            code: 81,
            shiftKey: true
        },
        rewind_1_s: {
            key: 'q',
            code: 81,
            shiftKey: false
        },
        forward_1_s: {
            key: 'd',
            code: 68,
            shiftKey: false
        },
        forward_5_s: {
            key: 'D',
            code: 68,
            shiftKey: true
        },
        play_pause: {
            key: 'p',
            code: 80,
            shiftKey: false
        },
        clip: {
            key: 'Enter',
            code: 13,
            shiftKey: true
        },
        clip_start: {
            key: 'a',
            code: 65,
            shiftKey: false
        },
        clip_end: {
            key: 'a',
            code: 90,
            shiftKey: false
        }
    }

    $(document).keydown(function (event) {

        const key = event.originalEvent.key
        const shiftKey = event.originalEvent.shiftKey

        if ('q' === key && shiftKey) {
            shift_current_time(-5)
            return
        }

        if ('Q' === key && !shiftKey) {
            shift_current_time(-1)
            return
        }

        if ('d' === key && !shiftKey) {
            shift_current_time(1)
            return
        }

        if ('D' === key && shiftKey) {
            shift_current_time(5)
            return
        }

        if ('a' === key && !shiftKey) {
            set_timecode_start()
            return
        }

        if ('z' === key && !shiftKey) {
            set_timecode_end()
            return
        }
    })


    $(document).keyup(function (event) {

        const keyCode = event.originalEvent.keyCode
        const shiftKey = event.originalEvent.shiftKey

        if (80 === keyCode && !shiftKey) {

            const is_playing = $("#video-source").prop('currentTime') > 0 && !$("#video-source").prop('paused')

            if (is_playing) {
                $("#video-source").trigger('pause')
            } else {
                console.log('play')
                $("#video-source").trigger('play')
            }
            return
        }

        if (13 === keyCode && shiftKey) {
            console.log('CLIP!')
            return
        }
    })

    /**
     * Preview
     */

    $("#btn_preview").click(function () {


        const preview_video_is_playing = $("#video-clip").prop('currentTime') > 0 & !$("#video-clip").prop('paused')

        // console.log(preview_video_is_playing)

        //Si pas en lecure, play, sinon pause
        if (preview_video_is_playing) {
            $("#video-clip").trigger('pause')
            $("#btn_preview").val('Prévisualiser')
        } else {
            $("#btn_preview").val('Pause')
            const src = $("#video-source").prop('src')

            const timecode_start = $("#timecode_start").val()
            const timecode_end = $("#timecode_end").val()

            const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)
            const timecode_end_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)

            // console.log(timecode_start_in_sec, timecode_end_in_sec)

            if (timecode_end_in_sec <= timecode_start_in_sec) {
                $("div.errors").html("<p>Impossible de prévisualiser l'extrait : le timecode de fin doit être plus grand que le timecode de début</p>")

                $("#timecode_start").addClass('error')
                $("#timecode_end").addClass('error')

                return
            }
            else {
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
        }
    })

    /**
    * Prévisualisation de la traîne
    */
    $("#btn_preview_tail").click(function () {

        //Decocher la loop si cochée (sinon bug)
        $('#checkbox_loop_preview').prop('checked', false)

        const src = $("#video-source").prop('src')

        const timecode_end = $("#timecode_end").val()

        const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)
        const tail_duration_in_sec = $("#tail_duration_in_s").val()
        const timecode_end_in_sec = parseInt(timecode_start_in_sec) + parseInt(tail_duration_in_sec)

        const src_timecodes = src + `#t=${timecode_start_in_sec},${timecode_end_in_sec}`

        const $html_video_clip = $("#video-clip")
        $html_video_clip.prop('src', src_timecodes)
        $html_video_clip.trigger('play')
    })


    /**
     * Soumission du formulaire de création d'extrait
     */

    $("#form-clip-source").submit(function (event) {

        event.preventDefault();

        //Disable le bouton, message traitement en cours + spinner ascii
        $("#btn-submit-clip").prop("disabled", true)
        window.requestAnimationFrame(spinner_ascii.step);

        const data = $('form#form-clip-source').serialize() + '&PHPSESSID=' + PHPSESSID

        $.post('/api/v1/clip-source', data).done(function (response) {

            //Si le formulaire est rejeté on récupere les erreurs et on les affiche
            if (typeof response !== 'string' && '' !== response && 'errors' in response) {

                const errors = response.errors

                let items = []

                for (const input in errors) {
                    items.push("<li>" + errors[input].message + "</li>")
                }

                $("div.errors").html('<ul>' + items.join('') + '</ul>')
            } else {
                //Clean error messages.
                $("div.errors").html('')

                //Ajouter un message (feedback) : Extrait généré avec succes !

                //La réponse: un markup html contenant les infos sur le nouveau clip
                //A ajouter à la liste des clips sur la source
                console.log(response)

            }


        }).fail(function () {
            $("div.errors").html('Hmm, il semblerait qu\'il y ait eu un problème de connexion. Veuillez rééssayer s\'il vous plaît.')
        }).always(function () {
            window.cancelAnimationFrame(spinner_ascii.requestID)
            $("#btn-submit-clip").val('Cut !')
            $("#btn-submit-clip").prop("disabled", false)
        })
    });
});






/**
 * Helper functions
 */

/**
 * Met à jour la durée de l'extrait dans l'élément #clip-duration. Réagit à un évènement qui modifie la durée de l'extrait (ie, changement des timecodes)
 * @returns void
 */
function update_clip_duration() {

    const start = $("#timecode_start").val()
    const end = $("#timecode_end").val()

    const duration_in_s = hh_mm_ss_lll_to_seconds(end) - hh_mm_ss_lll_to_seconds(start)

    const duration = seconds_to_hh_mm_ss_lll(duration_in_s)

    if (duration_in_s < 0) {
        $("#clip-duration").html('valeur incorrecte')
        $("#clip-duration").removeClass('valid')
        $("#clip-duration").addClass('error')
        return
    }

    if (duration_in_s > 140) {
        $("#clip-duration").html(duration)
        $("#clip-duration").removeClass('valid')
        $("#clip-duration").addClass('error')
        return
    }

    $("#clip-duration").removeClass('error')
    $("#clip-duration").addClass('valid')
    $("#clip-duration").html(duration)
}

/**
   * Formate une durée en secondes au format hh:mm:ss.lll
   * @param {string} timecode_seconds 
   * @returns string
   */
function seconds_to_hh_mm_ss_lll(timecode_seconds) {

    const miliseconds = Math.floor((timecode_seconds - Math.floor(timecode_seconds)) * 1000)
    const miliseconds_formatted = miliseconds < 100 ? '0' + miliseconds : miliseconds

    const seconds = Math.floor(timecode_seconds % 60)
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

    const h = timecode_hh_mm_ss_lll.substring(0, 2)
    const m = timecode_hh_mm_ss_lll.substring(3, 5)
    const s = timecode_hh_mm_ss_lll.substring(6, 8)
    const l = timecode_hh_mm_ss_lll.substring(9, 12)

    // console.log(h, m, s, l)

    const seconds = parseInt(h) * 3600 + parseInt(m) * 60 + parseInt(s) + parseInt(l) / 1000

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


/**
 * Spinner ASCII
 * @link source: https://codepen.io/vpegado/pen/gbOpRm
 */

const spinner_ascii = {
    duration: 900,
    element: '',
    step: function (timestamp) {
        var frame = Math.floor(timestamp * spinner_ascii.frames.length / spinner_ascii.duration) % spinner_ascii.frames.length;

        if (!spinner_ascii.element) {
            spinner_ascii.element = window.document.getElementById('btn-submit-clip');
        }

        ;
        spinner_ascii.element.value = 'Traitement en cours, veuillez patienter ' + spinner_ascii.frames[frame];
        spinner_ascii.requestID = window.requestAnimationFrame(spinner_ascii.step);
    },
    frames: '▤▧▥▨'.split(''),
    requestID: ''
}

// frames = '▙▛▜▟'.split('');
// frames = '▤▧▥▨'.split('');
//frames = '◴◵◶◷'.split('');
//frames = '◩◪'.split('');
//frames = '◰◱◲◳'.split('');
//frames = '◐◓◑◒'.split('');
