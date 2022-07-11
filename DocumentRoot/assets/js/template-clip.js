jQuery(function ($) {


    /**
     * Variable globale : url de la vidéo source en cours d'édition
     */
    var source_url
    var youtube_player;

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
        source_url = $(this).find(":selected").attr('id')
        $("#video-source").prop('src', source_url)
        fetch_clips_of_current_source(source_url)
        fetch_clip_drafs_of_current_source(source_url)
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
        play_pause()
    })

    $("#btn_goto_and_play_start").click(function () {
        goto_and_play_start()
    })

    $("#btn_goto_and_play_end").click(function () {
        goto_and_play_end()
    })

    $("#btn_play_500ms_before_start").click(function () {
        preview_before_start()
    })

    $("#btn_play_500ms_after_end").click(function () {
        preview_after_end()
    })

    $("#btn_save_clip_draft").click(function () {
        save_clip_draft()
    })

    $("#btn_preview").click(function () {
        play_pause_preview()
    })

    /**
     * Soumission du formulaire de création d'extrait
     */
    $("#form-clip-source").submit(function (event) {
        event.preventDefault();
        post_clip()
    });

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

        if ('i' === key && !shiftKey) {
            set_timecode_start()
            return
        }

        if ('o' === key && !shiftKey) {
            set_timecode_end()
            return
        }

        if ('p' === key && !shiftKey) {
            play_pause_preview()
            return
        }

        if ('m' === key && !shiftKey) {
            save_clip_draft()
            return
        }

        if ('a' === key && !shiftKey) {
            goto_and_play_start()
            return
        }
        if ('z' === key && !shiftKey) {
            goto_and_play_end()
            return
        }

        if ('w' === key && !shiftKey) {
            preview_before_start()
            return
        }
        if ('x' === key && !shiftKey) {
            preview_after_end()
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
            play_pause()
            return
        }

        if (13 === keyCode && shiftKey) {
            post_clip()
            return
        }
    })


});

/**
 * -- Fonctions
 */


/**
* Instructions à executer au chargement de la page.
* - initialisation du player embed youtube
* - recuperation des clips sur la source
* - recuperation des brouillons de clips sur la source
*/
function init_on_landing() {

    source_url = current_source()

    const video_id = youtube_video_id(source_url)

    init_youtube_player(video_id)

    fetch_clips_of_current_source(source_url)
    fetch_clip_drafs_of_current_source(source_url)
}

/**
 * @see https://developers.google.com/youtube/iframe_api_reference
 */
function init_youtube_player(video_id) {

    var tag = document.createElement('script');

    tag.src = 'https://www.youtube.com/iframe_api';

    var first_script_tag = document.getElementsByTagName('script')[0];

    first_script_tag.parentNode.insertBefore(tag, first_script_tag);

    window.onYouTubeIframeAPIReady = function () {

        youtube_player = new YT.Player('youtube-player',
            {
                videoId: video_id,
                height: '647',
                width: '1150',
                playerVars: {
                    'autohide': 0,
                    'cc_load_policy': 0,
                    'controls': 2,
                    'disablekb': 1,
                    'iv_load_policy': 3,
                    'modestbranding': 1,
                    'rel': 0,
                    'listType': 'playlist',
                    'showinfo': 0
                },
                events: {
                    'onError': onError,
                    'onReady': onReady,
                    'onStateChange': onStateChange
                }
            });

        // Add private data to the YouTube object
        youtube_player.personalPlayer = {
            'currentTimeSliding': false,
            'errors': []
        };
    }
}

function onError() {
    console.log('Error')
}

function onReady() {
    console.log('Ready')
}

function onStateChange() {
    console.log('State change')
}

function youtube_video_id(url) {
    const pos = url.indexOf('=')
    return url.substring(pos + 1)
}

function preview_before_start(tail_duration_in_sec = 2) {
    //Decocher la loop si cochée (sinon bug)
    $('#checkbox_loop_preview').prop('checked', false)

    const source_url = current_source()

    const timecode_start = $("#timecode_start").val()

    const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)

    const timecode_start_in_sec_delay = timecode_start_in_sec - tail_duration_in_sec

    if (timecode_start_in_sec_delay < 0)
        return

    const src_timecodes = source_url + `#t=${timecode_start_in_sec_delay},${timecode_start_in_sec}`

    $("#video-source").prop('src', src_timecodes)
    playvideo()
}

function preview_after_end(tail_duration_in_sec = 2) {

    //Decocher la loop si cochée (sinon bug)
    $('#checkbox_loop_preview').prop('checked', false)

    const timecode_end = $("#timecode_end").val()

    const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)
    const timecode_end_in_sec = timecode_start_in_sec + tail_duration_in_sec

    const src_timecodes = current_source() + `#t=${timecode_start_in_sec},${timecode_end_in_sec}`
    $("#video-source").prop('src', src_timecodes)
    playvideo()
}

/**
 * Lance la vidéo si elle est en pause
 * @returns 
 */
function playvideo() {
    if (video_is_playing())
        return
    $("#btn_play_pause").prop('innerHTML', '<div class="shortcut">s</div> pause')
    $("#video-source").trigger('play')
}


/**
 * Déplace le curseur au timecode début et reprend la lecture de la vidéo si elle était en cours
 * @returns 
 */
function goto_and_play_start() {

    const is_playing = video_is_playing()
    const timecode_start = $("#timecode_start").val()
    const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)
    const src_timecodes = current_source() + `#t=${timecode_start_in_sec}`
    $("#video-source").prop('src', src_timecodes)
    if (is_playing)
        playvideo()
}

function current_source() {
    return $("#sources").find(":selected").attr('data-url')
}

/**
 * Déplace le curseur au timecode fin et reprend la lecture de la vidéo si elle était en cours
 * @returns 
 */
function goto_and_play_end() {
    const is_playing = video_is_playing()
    const timecode = $("#timecode_end").val()
    const timecode_in_sec = hh_mm_ss_lll_to_seconds(timecode)
    const src_timecodes = current_source() + `#t=${timecode_in_sec}`
    $("#video-source").prop('src', src_timecodes)
    if (is_playing)
        playvideo()
}

/**
 * Retourne vrai si un input text du formulaire est focus, faux sinon
 * @returns bool
 */
function any_input_text_is_focused(event) {
    return $("#title").is(":focus") || $("#description").is(":focus")
}

/**
 * Retourne vrai si la vidéo est en train de jouer, faux sinon
 * @returns bool
 */
function video_is_playing() {
    return $("#video-source").prop('currentTime') > 0 && !$("#video-source").prop('paused')
}

/**
 * Retourne vrai si les timecods sont valides, faux sinon
 * @returns bool
 */
function are_timecodes_valid() {

    const timecode_start = $("#timecode_start").val()
    const timecode_end = $("#timecode_end").val()

    const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)
    const timecode_end_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)

    return timecode_start_in_sec < timecode_end_in_sec;
}

/**
 * Play la vidéo source si en pause et inversement
 */
function play_pause() {
    if (video_is_playing()) {
        $("#btn_play_pause").prop('innerHTML', '<div class="shortcut">s</div> play')
        $("#video-source").trigger('pause')
    } else {
        $("#btn_play_pause").prop('innerHTML', '<div class="shortcut">s</div> pause')
        $("#video-source").trigger('play')
    }
}

/**
 * Lance la prévisualisation de l'extrait
 * @returns 
 */
function play_pause_preview() {

    if (!are_timecodes_valid()) {
        $("div.success").html('')
        $("div.errors").html("<p>Impossible de prévisualiser l'extrait : le timecode de fin doit être plus grand que le timecode de début</p>")
        $("#timecode_start").addClass('error')
        $("#timecode_end").addClass('error')
        return
    }

    $("div.errors").html('')
    $("#timecode_start").removeClass('error')
    $("#timecode_end").removeClass('error')

    const timecode_start = $("#timecode_start").val()
    const timecode_end = $("#timecode_end").val()

    const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)
    const timecode_end_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)

    const src_timecodes = current_source() + `#t=${timecode_start_in_sec},${timecode_end_in_sec}`
    $("#video-source").prop('src', src_timecodes)

    /**
     * Gestion de l'option de loop.
     */
    $("#video-source").on('timeupdate', function () {
        if ($('#checkbox_loop_preview').is(':checked')) {
            loop_video(this, timecode_start_in_sec, timecode_end_in_sec)
        }
        else {
            if (has_reached_end(timecode_start_in_sec, timecode_end_in_sec))
                this.pause()
        }
    })

    play_pause()
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
        $("div.success").html('')
        $("div.errors").html('Hmm, il semblerait qu\'il y ait eu un problème de connexion. Veuillez rééssayer s\'il vous plaît.')
    }).always(function () {

    })
}

/**
 * Fetch les brouillons de clips de l'utilisateur enregistrés pour la vidéo source
 * @param {string} source_url 
 */
function fetch_clip_drafs_of_current_source(source_url) {

    const class_btn_delete_draft = 'btn-delete-draft'
    const class_btn_load_draft = 'btn-load-draft'

    $("#list-markers").empty()

    $.post('/api/v1/markers', {
        action: 'fetch',
        source_name: $("#sources").find('option:selected').attr("id"),
    }).done(function (response) {

        response.markers.forEach(marker => {

            const li = marker_markup(
                marker.id,
                marker.timecode_start_in_sec,
                marker.timecode_end_in_sec,
                marker.title,
                class_btn_delete_draft,
                class_btn_load_draft)

            $("#list-markers").append(li)

            const $li_appended = $("#list-markers").children("li:last-child")

            item_draft_add_listeners($li_appended)
        })
    });
}

function item_draft_add_listeners($item_draft) {

    const class_btn_delete_draft = 'btn-delete-draft'
    const class_btn_load_draft = 'btn-load-draft'

    //Event listener : click sur le marqueur OU click sur supprimer.
    $item_draft.click(function (event) {

        const delete_marker_btn_is_clicked = event.originalEvent.target.className === class_btn_delete_draft
        const load_marker_btn_is_clicked = event.originalEvent.target.className === class_btn_load_draft

        if (delete_marker_btn_is_clicked) {
            remove_marker(this)
            return
        }

        if (load_marker_btn_is_clicked) {
            load_marker(this)
            return
        }
    })
}

function load_marker(item_draft) {

    const data = {
        timecode_start: $(item_draft).attr('data-timecodestart'),
        timecode_end: $(item_draft).attr('data-timecodeend'),
        title: $(item_draft).attr('data-title')
    }

    $("#timecode_start").val(seconds_to_hh_mm_ss_lll(data.timecode_start))
    $("#timecode_end").val(seconds_to_hh_mm_ss_lll(data.timecode_end))
    update_clip_duration()
    $("textarea#title").val(data.title)

    $("div.success").html('Le brouillon a été chargé')
    $("div.errors").html('')
}

function marker_markup(uid, timecode_start, timecode_end, title, class_btn_delete, class_btn_load) {
    return `<li id="${uid}" data-title="${title}" data-timecodestart="${timecode_start}"  data-timecodeend="${timecode_end}" class="marker"> 
    <span>${title}</span> 
    <span class="time">${timecode_start}</span> - 
    <span class="time">${timecode_end}</span> 
    <button class="${class_btn_load}">Charger le brouillon</button>
    <button class="${class_btn_delete}">Supprimer</button>
    </li>`
}

/**
 * Définit un markeur à la position courante du lecteur. Ajoute un listeneur sur le markeur pour servir de lien vers la vidéo (qui se déclenche a la position définie par le marker)
 */
function save_clip_draft() {

    const class_btn_delete_draft = 'btn-delete-draft'
    const class_btn_load_draft = 'btn-load-draft'


    const timecode_start = $("#timecode_start").val()
    const timecode_end = $("#timecode_end").val()
    const timecode_start_in_sec = hh_mm_ss_lll_to_seconds(timecode_start)
    const timecode_end_in_sec = hh_mm_ss_lll_to_seconds(timecode_end)
    const title = $("textarea#title").val()

    //Envoyer une requete pour ajouter le marqueur.
    $.post('/api/v1/markers', {
        action: 'add',
        source_name: current_source(),
        timecode_start_in_sec: timecode_start_in_sec,
        timecode_end_in_sec: timecode_end_in_sec,
        title: title
    }).done(function (response) {

        //Si le formulaire est rejeté on récupere les erreurs et on les affiche
        if (typeof response !== 'string' && '' !== response && 'errors' in response) {
            const errors = response.errors
            let items = []
            for (const input in errors) {
                items.push("<li>" + errors[input].message + "</li>")
            }
            $("div.success").html('')
            $("div.errors").html('<ul>' + items.join('') + '</ul>')
            return
        }

        const marker_id = response.data

        const li = marker_markup(
            marker_id,
            timecode_start_in_sec,
            timecode_end_in_sec, title,
            class_btn_delete_draft,
            class_btn_load_draft)

        $("#list-markers").append(li)

        const $li_appended = $("#list-markers").children("li:last-child")

        item_draft_add_listeners($li_appended)

        //Clean error messages.
        $("div.errors").html('')
        $("div.success").html("Le brouillon a bien été enregistré")


    }).fail(function () {
        $("div.success").html('')
        $("div.errors").html('Hmm, il semblerait qu\'il y ait eu un problème de connexion avec le serveur. Ré-essayez svp.')
    })
}

/**
 * Supprime un brouillon
 */
function remove_marker(marker) {

    $.post('/api/v1/markers', {
        action: 'remove',
        marker_id: $(marker).prop('id'),
    }).done(function (response) {
        //Si le formulaire est rejeté on récupere les erreurs et on les affiche
        if (typeof response !== 'string' && '' !== response && 'errors' in response) {
            const errors = response.errors
            let items = []
            for (const input in errors) {
                items.push("<li>" + errors[input].message + "</li>")
            }
            $("div.success").html('')
            $("div.errors").html('<ul>' + items.join('') + '</ul>')
            return
        }
        $(marker).remove()
        $("div.errors").html('')
        $("div.success").html('Le brouillon a bien été supprimé')
    })
}



/**
 * Met à jour le timecode de départ avec le temps courant du player video source
 */
function set_timecode_start(start_in_sec) {
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

/**
 * Post le formulaire pour créer l'extrait et traiteement de la réponse.
 */
function post_clip() {
    //Disable le bouton, message traitement en cours + spinner ascii
    $("#btn-submit-clip").prop("disabled", true)
    window.requestAnimationFrame(spinner_ascii.step);


    const data = {
        timecode_start: $("#timecode_start").val(),
        timecode_end: $("#timecode_end").val(),
        title: $("#title").val(),
        description: $("#description").val(),
        token: $("#token").val(),
        source_name: $("#sources").find('option:selected').attr("id"),
        PHPSESSID: PHPSESSID
    }

    $.post('/api/v1/clip-source', data).done(function (response) {

        //Si le formulaire est rejeté on récupere les erreurs et on les affiche
        if (typeof response !== 'string' && '' !== response && 'errors' in response) {
            const errors = response.errors
            let items = []
            for (const input in errors) {
                items.push("<li>" + errors[input].message + "</li>")
            }
            $("div.success").html('')
            $("div.errors").html('<ul>' + items.join('') + '</ul>')
        } else {
            $("div.errors").html('')
            $("div.success").html("L'extrait a été ajouté avec succès !")
            $("#list-clips-on-current-source").append(response.extrait)
        }

    }).fail(function () {
        $("div.success").html('')
        $("div.errors").html('Hmm, il semblerait qu\'il y ait eu un problème de connexion. Veuillez rééssayer s\'il vous plaît.')
    }).always(function () {
        window.cancelAnimationFrame(spinner_ascii.requestID)
        $("#btn-submit-clip").prop('innerHTML', '<div class="shortcut">Shift+Enter</div> Cut !')
        $("#btn-submit-clip").prop("disabled", false)
    })
}