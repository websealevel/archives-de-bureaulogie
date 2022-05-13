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

    $("#form-download").submit(function (event) {

        event.preventDefault();

        const data = $('form#form-download').serialize() + '&PHPSESSID=' + PHPSESSID

        $.post('/api/v1/download-source', data).done(function (data) {
            console.log(data)

            //Si le formulaire est rejeté on récupere les erreurs

            //Si le formulaire est validé, on récupere un status code nous disant qu'on peut y aller

            //On ouvre une connexion SSE avec le serveur. Celui ci va scanner tous les téléchargement pending associés à notre compte (grace à notre PHPSESSID) et les lancer

            //Ouverture de la connexion SSE pour lancer les téléchargements et récupérer la progression des downloads.
            
            // const evtSource = new EventSource("sse-download-source");
            // evtSource.onmessage = function (event) {
            //     const newElement = document.createElement("li");
            //     const eventList = document.getElementById("list");

            //     newElement.textContent = "message: " + event.data;
            //     eventList.appendChild(newElement);
            // }

            // evtSource.onerror = function (err) {
            //     console.error("EventSource failed:", err);
            // };

        }).fail(function () {
            console.log('fail')
        })
    });


});