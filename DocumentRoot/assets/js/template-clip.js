jQuery(function ($) {
    // Your jQuery code here, using the $

    
    //Remplir auto la source de l'iframe quand l'url est copié/collée dans l'input
    $("#video-source").on('change', function () {
        const url = this.val();
        console.log(url)
        if (url == '') {
            $("#video-source").hide()
        }
        else {
            $("#video-source").attr('src', url)
            $("#video-source").show()
        }
    })
});