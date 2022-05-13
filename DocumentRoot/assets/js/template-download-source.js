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

    //Checker s'il y a des téléchargements en attente associés à mon compte, les lance
    const evtSource = new EventSource("sse-download-source");

    //A la reception de message venant du serveur
    evtSource.onmessage = function (event) {

        const json_data = JSON.parse(event.data)
        const downloads = json_data['pending_downloads']

        downloads.forEach(download => {

            //Si l'élément existe déjà
            if ($("ul#pending-downloads li#" + download.id).length) {
                //Mettre à jour la progression
            } else {
                $("ul#pending-downloads").append(
                    '<li id="' + download.id + '">' +
                    'Téléchargement <div class="dl-filename">' + download.filename + '</div>' +
                    '<div class="dl-progress">'+
                    '<progress value="'+ download.progression +'" max="100"></progress>'+
                    '</div>'+
                    '<div>Vitesse de téléchargement : ' + download.speed +'</div>'+
                    '</li>'
                )
            }
            console.log(download.url, download.id)
        });

    }
    //Soumettre une nouvelle demande de téléchargement
    $("#form-download").submit(function (event) {

        event.preventDefault();

        const data = $('form#form-download').serialize() + '&PHPSESSID=' + PHPSESSID

        $.post('/api/v1/download-source', data).done(function (data) {


            console.log(data)
            console.log(data['statut'])

            //Si le formulaire est rejeté on récupere les erreurs et on les affiche. A faire.

        }).fail(function () {
            console.log('fail')
        })
    });


});