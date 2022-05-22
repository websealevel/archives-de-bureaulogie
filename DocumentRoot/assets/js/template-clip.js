jQuery(function ($) {
    // Your jQuery code here, using the $


    /**
     * Init le src de la video source avec la valeur dans le select
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

        console.log(path)

        // Mettre a jour la source du tag video source.
        $("#video-source").attr('src', path)

        // Mettre à jour la liste des extraits présents
        //Faire une requete et ajouter a la liste
    })


    /**
     * Soumission du formulaire de création d'extrait
     */
    //Demander un nouveau téléchargement
    $("#form-clip-source").submit(function (event) {

        event.preventDefault();

        const data = $('form#form-clip-source').serialize() + '&PHPSESSID=' + PHPSESSID

        $.post('/api/v1/clip-source', data).done(function (data) {

            console.log(data)
            //Si le formulaire est rejeté on récupere les erreurs et on les affiche. A faire.

        }).fail(function () {
            console.log('fail')
        })
    });
});