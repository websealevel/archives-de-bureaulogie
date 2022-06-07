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
            $("iframe").attr('src', embed_url)
            $("iframe").show()
        }
    })

    //Demander un nouveau téléchargement
    $("#form-download").submit(function (event) {

        event.preventDefault();

        const data = $('form#form-download').serialize()

        $.post('/api/v1/download-source', data).done(function (data) {

            //Si le formulaire est rejeté on récupere les erreurs et on les affiche. A faire.
            console.log(data)

        }).fail(function () {
            console.log('fail')
            return
        })
    });

    //Server Send Event protocol (SSE).

    //Ouverture d'une connexion avec le serveur
    var evtSource = new EventSource("sse-download-source");


    //Erreur
    evtSource.onerror = function (err) {
        console.error("EventSource failed:", err);
    };

    //Met a jour le dom des téléchargements en cours
    evtSource.onmessage = function (event) {

        const json_data = JSON.parse(event.data)

        const content = json_data['content']

        //Il y a une erreur: soit le json est invalide ou pas d'autorization
        if (false === content) {
            console.error("Une erreur est survenue: " + json_data['message']);
            return
        }

        const downloads = json_data['active_downloads']

        if (typeof downloads === 'undefined') {
            return
        }

        downloads.forEach(download => {

            //Si l'élément existe déjà
            if ($("ul#active_downloads li#" + download.id).length) {

                //Mettre à jour la progression et la vitesse
                $progress = $("progress#id-" + download.id)
                $speed = $("ul#active_downloads li#" + download.id + " span.dl-speed")

                $progress.attr('value', download.progression)
                $speed.html(download.speed)

            } else {
                //Sinon, creer un nouvel item
                $("ul#active_downloads").append(
                    '<li id="' + download.id + '">' +
                    '<progress id=id-' + download.id + ' value="' + download.progression + '" max="100">' +
                    download.progression + '%</progress>' +
                    '<span class="dl-speed">' + download.speed + '</span>' +
                    '</li>'
                )
            }
        });
    }

});

