jQuery(function ($) {
    // Your jQuery code here, using the $


    /**
     * Init le src de la video source avec la valeur dans le select
     */
    const source_url = $("#sources").find('option:selected').attr("name")
    console.log(source_url)
    $("#video-source").attr('src', source_url)

    /**
     * Mettre à jour le src de la video source quand le select source change
     */
    $("#sources").on('select', function () {
        const path = this.find('option:selected').attr("name");
        console.log(path)
        if (path == '') {
            $("#video-source").hide()
        }
        else {
            $("#video-source").attr('src', path)
            $("#video-source").show()
        }
    })
});