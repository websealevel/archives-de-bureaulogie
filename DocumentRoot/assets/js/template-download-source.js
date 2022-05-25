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

        const data = $('form#form-download').serialize() + '&PHPSESSID=' + PHPSESSID

        $.post('/api/v1/download-source', data).done(function (data) {

            //Si le formulaire est rejeté on récupere les erreurs et on les affiche. A faire.
            console.log(data)

        }).fail(function () {
            console.log('fail')
        })
    });

    //Server Send Event protocol : écouter les téléchargements en cours
    //Met a jour le dom des téléchargements en cours
    const evtSource = new EventSource("sse-download-source");
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
                $progress = $("ul#active_downloads progress#" + download.id + "")
                $div_speed = $("ul#active_downloads li#" + download.id + " div.dl-speed")

                // $progress.html(download.progression + '%')
                // $div_speed.html(download.speed)

            } else {
                //Sinon, creer un nouvel item
                $("ul#active_downloads").append(
                    '<li id="' + download.id + '">' +
                    '<div class="dl-speed>' +
                    download.speed +
                    '</div>' +
                    '<progress id=' + download.id + ' value="32" max="100">' +
                    download.progression + '%' +
                    '</progress>' +
                    '</li>'
                )
            }
        });
    }

});

