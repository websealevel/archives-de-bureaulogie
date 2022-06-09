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

    $("#submit").on('click', function (event) {

        event.preventDefault();

        const data = $('form#form-download').serialize()

        $.ajax({
            async: true,
            method: "POST",
            url: '/api/v1/download-source',
            data: data,
            success: function (data) {

                //Si le formulaire est rejeté on récupere les erreurs et on les affiche. Ou si une erreur au lancement du dl. A faire.

                //Si réussi, on récuperer le nom du fichier avec le lien pour visualiser la source (par exemple). A faire

                console.error(data)
                const errors = data.errors
                const items = errors.map((error) => "<li>" + error.message + "</li>")

                $("div.errors").html('<ul>' + items.toString() + '</ul>')
            },
            dataType: 'json',
            error: function (e) {
                $("div.errors").html('<p>Une erreur est survenue</p>')
                console.error(e)
            }
        });
        //Indiquer que le téléchargement a été lancé (feedback pour l'user)
    })

    //Server Send Event protocol (SSE).

    Ouverture d'une connexion avec le serveur
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

            console.log(download)

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
})

