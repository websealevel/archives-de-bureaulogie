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

            console.log(data)
            //Si le formulaire est rejeté on récupere les erreurs et on les affiche. A faire.

        }).fail(function () {
            console.log('fail')
        })
    });

    //Server Send Event protocol : écouter les téléchargements en cours
    const evtSource = new EventSource("sse-download-source");
    evtSource.onmessage = function (event) {


        const json_data = JSON.parse(event.data)

        console.log(json_data)
        
        const content = json_data['content']

        if (false === content)
            return

        const downloads = json_data['active_downloads']

        if (typeof downloads === 'undefined'){
            return
        }

        downloads.forEach(download => {

            //Si l'élément existe déjà
            if ($("ul#active_downloads li#" + download.id).length) {

                //Mettre à jour la progression et la vitesse

                $progress = $("ul#active_downloads li#" + download.id + " div.dl-progress #bar")

                $div_speed = $("ul#active_downloads li#" + download.id + " div.dl-speed span")

                $progress.width(download.progression + '%')
                $progress.html(download.progression + '%')
                $div_speed.html(download.speed)

            } else {
                //Sinon, creer un nouvel item
                $("ul#active_downloads").append(
                    '<li class="w3-container" id="' + download.id + '">' +
                    '<div class="dl-filename">' + download.filename + '</div>' +
                    '<div class="dl-progress">' +
                    '<div class="w3-light-grey">' +
                    '<div id="bar" class= "w3-container w3-green w3-center" style="width:' + download.progression + '%">' +
                    download.progression + '%' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="dl-speed">Vitesse de téléchargement : <span>' + download.speed + '</span></div>' +
                    '</li>'
                )
            }
        });
    }

});