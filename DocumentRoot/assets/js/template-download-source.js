jQuery(function ($) {

    //Cacher initialement l'iframe
    if ($("input[name='source_url']").val() == '')
        $("iframe").hide()

    //Remplir auto la source de l'iframe quand l'url est copié/collée dans l'input
    $("input[name='source_url']").on('input', function () {
        const url = $(this).val()
        if (url == '') {
            $("iframe").hide()
        }
        else {
            const embed_url = url.replace('watch?v=', 'embed/');
            console.log(embed_url)
            $("iframe").show()
            $("iframe").attr('src', embed_url)
        }
    })

    //Checker s'il y a des process en cours de téléchargement

    //Poster le formulaire de téléchargement en ajax
    $("#form-download").submit(function (event) {
        event.preventDefault();
        const data = $('form#form-download').serialize() + '&PHPSESSID='+PHPSESSID
        $.post('/api/v1/download-source', data).done(function (data) {
            console.log(data.)
        }).fail(function () {
            console.log('fail')
        })
    });

    //Valider le formulaire

    //Si formulaire invalide retourne les erreurs

    //Si formulaire valide lance un process de téléchargement et retourne le pid

    //On peut annuler un process

});