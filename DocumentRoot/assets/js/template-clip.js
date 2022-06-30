jQuery(function ($) {

    init_on_landing()

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
        $("#video-source").prop('src', source_url)
        $("#video-clip").prop('src', source_url)
        $("#source_name").val(source_url)
        fetch_clips_of_current_source(source_url)
    })

    /**
     * Boutons de controle du lecteur/edition
     */
    $("#video-source").on('play', function (event) {
        $("#video-clip").trigger('pause')
    })

    $("#video-clip").on('play', function (event) {
        $("#video-source").trigger('pause')
    })

    $("#btn_clip_start").click(function () {
        set_timecode_start()
    })

    $("#btn_clip_end").click(function () {
        set_timecode_end()
    })

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

    $("#btn_play_pause").click(function () {
        play_pause_video_source()
    })

    /**
     * Raccourcis clavier
     */

    /**
     * Raccourcis claviers, traitement des évenements keydown.
     */
    $(document).keydown(function (event) {

        if (any_input_text_is_focused(event))
            return

        const key = event.originalEvent.key
        const shiftKey = event.originalEvent.shiftKey

        if ('Q' === key && shiftKey) {
            shift_current_time(-5)
            return
        }

        if ('q' === key && !shiftKey) {
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

        if ('k' === key && !shiftKey) {
            set_timecode_start()
            return
        }

        if ('l' === key && !shiftKey) {
            set_timecode_end()
            return
        }

        if ('p' === key && !shiftKey) {
            play_pause_preview()
            return
        }

        if ('o' === key && !shiftKey) {
            preview_trail()
            return
        }

        if ('m' === key && !shiftKey) {
            add_marker()
            return
        }
    })


    /**
     * Empecher de soumettre le form quand on press Enter dans un input text
     */
    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

    /**
    * Raccourcis claviers, traitement des évenements keyup.
    */
    $(document).keyup(function (event) {

        const keyCode = event.originalEvent.keyCode
        const shiftKey = event.originalEvent.shiftKey

        if (83 === keyCode && !shiftKey) {
            if (any_input_text_is_focused(event))
                return
            play_pause_video_source()
            return
        }

        if (13 === keyCode && shiftKey) {
            console.log(shiftKey)
            post_clip()
            return
        }
    })

    /**
     * Preview
     */

    $("#btn_preview").click(function () {
        play_pause_preview()
    })

    /**
    * Prévisualisation de la traîne
    */
    $("#btn_preview_tail").click(function () {
        preview_trail()
    })

    /**
     * Soumission du formulaire de création d'extrait
     */

    $("#form-clip-source").submit(function (event) {
        event.preventDefault();
        post_clip()
    });
});


/**
 * -- Fonctions
 */

/**
* Instructions à executer au chargement de la page.
*/
function init_on_landing() {
    const source_url = $("#sources").find('option:selected').attr("name")
    $("#video-source").prop('src', source_url)
    $("#video-clip").prop('src', source_url)
    $("#source_name").val(source_url)

    fetch_clips_of_current_source(source_url)
    fetch_clip_markers_of_current_source(source_url)
}

/**
 * Retourne vrai si un input text du formulaire est focus, faux sinon
 * @returns bool
 */
function any_input_text_is_focused(event) {
    return $("#title").is(":focus") || $("#description").is(":focus")
}

/**
 * Déclenche le visionnage de la traine
 */
function preview_trail() {
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
}

/**
 * Post le formulaire pour créer l'extrait et traiteement de la réponse.
 */
function post_clip() {
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
            $("div.errors").html('')
            $("div.success").html("L'extrait a été ajouté avec succès !")
            $("#list-clips-on-current-source").append(response.extrait)
        }

    }).fail(function () {
        $("div.errors").html('Hmm, il semblerait qu\'il y ait eu un problème de connexion. Veuillez rééssayer s\'il vous plaît.')
    }).always(function () {
        window.cancelAnimationFrame(spinner_ascii.requestID)
        $("#btn-submit-clip").prop('innerHTML', '<div class="shortcut">Shift+Enter</div> Cut !')
        $("#btn-submit-clip").prop("disabled", false)
    })
}

/**
 * Lance la vidéo de prévisualisation de l'extrait si elle est en pause, et vice versa.
 * @returns void
 */
function play_preview() {
    $("#btn_preview").prop('innerHTML', '<div class="shortcut"> p</div> Pause')
    const src = $("#video-source").prop('src')

    const timecode_start = $("#timecode_start").val()
    const timecode_end = $("#timecode_end").val()

    const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)
    const timecode_end_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)

    //Si les timecodes sont invalides.
    if (timecode_end_in_sec <= timecode_start_in_sec) {
        $("div.errors").html("<p>Impossible de prévisualiser l'extrait : le timecode de fin doit être plus grand que le timecode de début</p>")
        $("#timecode_start").addClass('error')
        $("#timecode_end").addClass('error')
        const preview_video_is_playing = $("#video-clip").prop('currentTime') > 0 & !$("#video-clip").prop('paused')
        return
    }

    $("div.errors").html('')
    $("#timecode_start").removeClass('error')
    $("#timecode_end").removeClass('error')


    const src_timecodes = src + `#t=${timecode_start_in_sec},${timecode_end_in_sec}`
    const $html_video_clip = $("#video-clip")
    $html_video_clip.prop('src', src_timecodes)
    $html_video_clip.trigger('play')

    /**
     * Gestion de l'option de loop.
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

/**
    * Play/Pause la vidéo de preview de l'extrait
    * @returns 
    */
function play_pause_preview() {

    const preview_video_is_playing = $("#video-clip").prop('currentTime') > 0 & !$("#video-clip").prop('paused')

    //Si pas en lecure, play, sinon pause
    if (preview_video_is_playing) {
        $("#video-clip").trigger('pause')
        $("#btn_preview").prop('innerHTML', '<div class="shortcut"> p</div> Prévisualiser')

    } else {
        play_preview()
    }
}

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
 * Play la vidéo source si en pause et inversement
 */
function play_pause_video_source() {
    const is_playing = $("#video-source").prop('currentTime') > 0 && !$("#video-source").prop('paused')
    if (is_playing) {
        $("#video-source").trigger('pause')
        $("#btn_play_pause").prop('innerHTML', '<div class="shortcut">s</div> Play')

    } else {
        $("#btn_play_pause").prop('innerHTML', '<div class="shortcut">s</div> Pause')
        $("#video-source").trigger('play')
    }
}

/**
 * Met à jour le timecode de départ avec le temps courant du player video source
 */
function set_timecode_start(start_in_sec) {

    console.log(start_in_sec)

    if (start_in_sec) {
        console.log('here')
        $("#video-source").prop("currentTime", start_in_sec)
        return
    }

    const timecode_seconds = $("#video-source").prop("currentTime")
    const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
    $("#timecode_start").val(hh_mm_ss_lll)
    update_clip_duration()
    //Si preview est en cours, la relancer avec nouvelle valeur de timecode start
    const is_playing = $("#video-clip").prop('currentTime') > 0 && !$("#video-clip").prop('paused')
    if (is_playing) {
        play_preview()
    }
}


/**
* Initialise la lite des extraits sur la source
*/
function fetch_clips_of_current_source(source_url) {

    if (source_url === '')
        return

    const data = { source: source_url }

    //Clear previous clips
    $("#list-clips-on-current-source").empty()
    $.post('/api/v1/list-clips', data).done(function (response) {

        //Si le formulaire est rejeté on récupere les erreurs et on les affiche
        if (typeof response !== 'string' && '' !== response && 'errors' in response) {
            const errors = response.errors
            let items = []
            for (const input in errors) {
                items.push("<li>" + errors[input].message + "</li>")
            }
            $("div.errors").html('<ul>' + items.join('') + '</ul>')
        } else {
            const clips = response.extrait
            $("#list-clips-on-current-source").append(clips)
        }

    }).fail(function () {
        $("div.errors").html('Hmm, il semblerait qu\'il y ait eu un problème de connexion. Veuillez rééssayer s\'il vous plaît.')
    }).always(function () {

    })
}

/**
 * Fetch les marqueurs de l'utilisateur connecté enregistrée pour la vidéo source
 * @param {string} source_url 
 */
function fetch_clip_markers_of_current_source(source_url) {
    $.post('/api/v1/markers', {
        action: 'fetch',
        source_name: $("#sources").find('option:selected').attr("name"),
    }).done(function (response) {

    })

}

/**
 * Définit un markeur à la position courante du lecteur. Ajoute un listeneur sur le markeur pour servir de lien vers la vidéo (qui se déclenche a la position définie par le marker)
 */
function add_marker() {

    const class_btn_delete_marker = 'btn-delete-marker'
    const currentTime = $("#video-source").prop('currentTime')
    const currentTime_sec = parseInt(currentTime)

    const li = `<li id="${currentTime_sec}" class="marker"><span class="time">${currentTime_sec}</span> <button class="${class_btn_delete_marker}">Supprimer</button></li>`

    //Avoid doublon
    if ($(`li#${currentTime_sec}`).length > 0)
        return

    //Envoyer une requete pour ajouter le marqueur.
    $.post('/api/v1/markers', {
        action: 'add',
        source_name: $("#sources").find('option:selected').attr("name"),
        position_in_sec: currentTime_sec
    }).done(function (response) {

        //Si le formulaire est rejeté on récupere les erreurs et on les affiche
        if (typeof response !== 'string' && '' !== response && 'errors' in response) {
            const errors = response.errors
            let items = []
            for (const input in errors) {
                items.push("<li>" + errors[input].message + "</li>")
            }
            $("div.errors").html('<ul>' + items.join('') + '</ul>')
            return
        }

        $("#list-markers").append(li)
        const $li_appended = $("#list-markers").children("li:last-child")

        //Event listener : click sur le marqueur OU click sur supprimer.
        $li_appended.click(function (event) {

            const delete_marker_btn_clicked = event.originalEvent.target.className === class_btn_delete_marker

            if (delete_marker_btn_clicked) {
                remove_marker(this, currentTime_sec)
                return
            }
            play_source_video_at_marker_position(this)
        })

        //Clean error messages.
        $("div.errors").html('')
        $("div.success").html("Le marqueur a bien été enregistré")


    }).fail(function () {
        $("div.errors").html('Hmm, il semblerait qu\'il y ait eu un problème de connexion. Veuillez rééssayer s\'il vous plaît.')
    })
}

/**
 * Déplace le curseur à la position du marqueur et lance la vidéo si elle est en pause.
 */
function play_source_video_at_marker_position(marker) {

    const content = $(marker)[0].innerText
    //On récupere le début dela position du texte 'Supprimer'
    const pos = content.indexOf('S')
    //On récupere uniquement le temps en seconde
    const time_part = content.substring(0, pos)
    const currentTime = parseFloat(time_part)
    $("#video-source").prop('currentTime', currentTime)
    const source_video_is_playing = $("#video-source").prop('currentTime') > 0 & !$("#video-source").prop('paused')
    if (!source_video_is_playing)
        $("#video-source").trigger('play')
}

/**
 * Supprime un markeur
 */
function remove_marker(marker, currentTime_sec) {
    $.post('/api/v1/markers', {
        action: 'remove',
        source_name: $("#sources").find('option:selected').attr("name"),
        position_in_sec: currentTime_sec
    }).done(function (response) {
        //Si le formulaire est rejeté on récupere les erreurs et on les affiche
        if (typeof response !== 'string' && '' !== response && 'errors' in response) {
            const errors = response.errors
            let items = []
            for (const input in errors) {
                items.push("<li>" + errors[input].message + "</li>")
            }
            $("div.errors").html('<ul>' + items.join('') + '</ul>')
            return
        }
        console.log(response)
        $(marker).remove()
    })
}

/**
* Met à jour le timecode de fin avec le temps courant du player video source
*/
function set_timecode_end() {
    const timecode_seconds = $("#video-source").prop("currentTime")
    const hh_mm_ss_lll = seconds_to_hh_mm_ss_lll(timecode_seconds)
    $("#timecode_end").val(hh_mm_ss_lll)
    update_clip_duration()

    //Si preview est en cours, la relancer avec nouvelle valeur de timecode end
    const is_playing = $("#video-clip").prop('currentTime') > 0 && !$("#video-clip").prop('paused')
    if (is_playing) {
        play_preview()
    }
}

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
    const miliseconds_formatted_tmp = miliseconds < 100 ? '0' + miliseconds : miliseconds
    const miliseconds_formatted = miliseconds < 10 ? '0' + miliseconds_formatted_tmp : miliseconds_formatted_tmp

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
        spinner_ascii.element.innerHTML = 'Traitement en cours, veuillez patienter ' + spinner_ascii.frames[frame];
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


/**
 * Pas utilisé pour le moment, sert juste de documentation.
 */
    //  const keyboard_controls = {
    //     rewind_5_s: {
    //         key: 'Q',
    //         code: 81,
    //         shiftKey: true
    //     },
    //     rewind_1_s: {
    //         key: 'q',
    //         code: 81,
    //         shiftKey: false
    //     },
    //     forward_1_s: {
    //         key: 'd',
    //         code: 68,
    //         shiftKey: false
    //     },
    //     forward_5_s: {
    //         key: 'D',
    //         code: 68,
    //         shiftKey: true
    //     },
    //     play_pause: {
    //         key: 'p',
    //         code: 80,
    //         shiftKey: false
    //     },
    //     clip: {
    //         key: 'Enter',
    //         code: 13,
    //         shiftKey: true
    //     },
    //     clip_start: {
    //         key: 'a',
    //         code: 65,
    //         shiftKey: false
    //     },
    //     clip_end: {
    //         key: 'a',
    //         code: 90,
    //         shiftKey: false
    //     }
    // }