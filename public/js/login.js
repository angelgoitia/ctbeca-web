$(document).ready(function() {
    $('#loading').removeClass("show");
    $('#loading').addClass("hide");
    var $sections = $('.form-section');

    function navigateTo(index){
        $sections.removeClass('current').eq(index).addClass('current');
        var arTheEnd = index >= $sections.length -1;
        $('.form-navigation [type=submit]').toggle(arTheEnd);
    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }

    $sections.each(function(index, section){
        $(section).find(':input').attr('data-parsley-group', 'block-'+index);
    })

    navigateTo(0);

    $('.submit').click(function(e){
        e.preventDefault();
        $('.contact-form').parsley().whenValidate({
            group: 'block-' + curIndex()
        }).done(function(){
            $("#login-form").submit();
            if($('#password').val().length >0){
                $('.submit').hide();
                $('#loading').removeClass("hide");
                $('#loading').addClass("show");
            }
        });
    });
});