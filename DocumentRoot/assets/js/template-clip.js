jQuery(function ($) {

    /**
     * Set le src de la video source a partir de la source sélectionnée
     */
    const source_url = $("#sources").find('option:selected').attr("name")
    console.log(source_url)
    $("#video-source").attr('src', source_url)

    /**
     * Init la liste des extraits associés à la source
     */

    /**
     * Evenement quand le select de source change
     */
    $("#sources").on('select', function () {

        const path = this.find('option:selected').attr("name");

        // Mettre a jour la source du tag video source.
        $("#video-source").attr('src', path)

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
        console.log(hh_mm_ss_lll)
        $("#timecode_start").val(hh_mm_ss_lll)
    })

    $("#btn_clip_end").click(function () {
        console.log('btn_clip_end')
    })

    /**
     * Soumission du formulaire de création d'extrait
     */
    //Demander un nouveau téléchargement
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