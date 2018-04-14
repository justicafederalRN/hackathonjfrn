$('#search-button > div > button').click(function(e){
    e.preventDefault();
    $('input[name=search]').css({
      'display':'inherit'
    }).animate({
      'width':'100%'
    }, 500, function() {
      // Animation complete.
    })
    .focus()
    .on('focusout',function(){
        $(this)
        .animate({
          'width':'0px',
        }, 500, function(){
          $(this).css({
            'display':'none'
          });
        });
        $(this).off('focusout');
    });
});
