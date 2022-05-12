jQuery(function ($) {

    if ($("input[name='source_url']").val() == '')
        $("iframe").hide()

    // Your jQuery code here, using the $
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

});