if (typeof tinymce !== 'undefined') {
   tinymce_init = function(element='textarea.editor:not(.init)') {
       $(function() {

           $(element).each(function() {
               const rid = 'mce__'+Math.random().toString().substr(5);
               this.id = rid;
               let config = {
                   selector: '#'+rid,
                   toolbar_sticky:true,
                   toolbar: "undo redo removeformat fullscreen code visualblocks | casechange blocks a11ycheck fontsize forecolor backcolor | bold italic underline |  alignleft aligncenter alignright alignjustify |  bullist numlist checklist outdent indent ",
                   font_size_formats:'10px 12px 14px 16px 18px 20px 22px',
                   convert_urls: false,
                   setup: (editor)=>{

                   }
               };
               if(typeof $(this).data('plugins') !== "undefined") {
                   config.plugins = "code,fullscreen,table"+$(this).data('plugins')
               }
               tinymce.init(config);
               $(element).addClass('init');
           });
       })
   }

    tinymce_init();
}
