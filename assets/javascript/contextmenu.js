$(function() {

   contextMenuInit = function() {
       var $doc = $( '[data-contextmenu]' );
       $doc.each(function() {
           $context_attr = $(this).data('contextmenu');
           $context_menu_html = $($context_attr);
           $($context_attr).remove();
           $('body').append($context_menu_html);
       }).promise().done(function() {
           $doc.each(function() {

               $context_attr = $(this).data('contextmenu');

               $context = $($context_attr).find(".context:not(.sub)");

               $(this).on( "contextmenu", function(e) {

                   $context = $($(this).data('contextmenu')).find(".context:not(.sub)");

                   var $window = $( window ),
                       $sub = $context.find(".sub");
                   if($sub.length) {
                       $sub.removeClass("oppositeX oppositeY");
                   }

                   e.preventDefault();
                   var w = $context.width();
                   var h = $context.height();
                   var x = e.clientX;
                   var y = e.clientY;
                   var ww = $window.width();
                   var wh = $window.height();
                   var padx = 0;
                   var pady = 0;
                   var fx = x;
                   var fy = y;
                   var hitsRight = ( x + w >= ww - padx );
                   var hitsBottom = ( y + h >= wh - pady );

                   if ( hitsRight ) {
                       fx = ww - w - padx;
                   }

                   if ( hitsBottom ) {
                       fy = wh - h - pady;
                   }

                   $context
                       .css({
                           left: fx - 1,
                           top: fy - 1
                       });

                   if($sub.length) {
                       var sw = $sub.width();
                       var sh = $sub.height();
                       var sx = $sub.offset().left;
                       var sy = $sub.offset().top;

                       var subHitsRight = ( sx + sw - padx >= ww - padx );
                       var subHitsBottom = ( sy + sh - pady >= wh - pady );

                       if( subHitsRight ) {
                           $sub.addClass("oppositeX");
                       }

                       if( subHitsBottom ) {
                           $sub.addClass("oppositeY");
                       }
                   }



                   $context.addClass("is-visible");

                   $(document).on("mousedown", function(e) {
                       var $tar = $( e.target );
                       if( !$tar.is( $context ) &&
                           !$tar.closest(".context").length) {
                           $context.removeClass("is-visible");
                           $doc.off( e );
                       }
                   });

               });

               $context.on("mousedown touchstart", "li:not(.nope)", function(e) {
                   if( e.which === 1 ) {
                       var $item = $(this);
                       $item.removeClass("active");
                       setTimeout( function() {
                           $item.addClass("active");
                       },10);
                   }
               });
           })
       });
   }

    contextMenuInit();

});












