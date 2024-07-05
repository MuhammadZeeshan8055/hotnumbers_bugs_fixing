if (typeof CKEDITOR !== 'undefined') {

    CKEDITOR.editorConfig = function( config ) {
        config.toolbarGroups = [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ];

        config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Iframe,About';
    };

    $(function() {

        const showMediaDialog = ()=> {
            $('#add-media-btn .browse_media').click();
        }

        CKEDITOR.plugins.add('add_media',
            {
                init: function (editor) {
                    var pluginName = 'add_media';
                    editor.ui.addButton('AddMedia',
                        {
                            label: 'Add Media',
                            command: 'OpenWindow',
                            icon:   site_url+'/assets/images/picture_icon.png'
                        });
                    var cmd = editor.addCommand('OpenWindow', { exec: showMediaDialog });
                }
            });


        $('.text-editor').each(function() {
            const rid = this.id || 'mce__'+Math.random().toString().substr(5);
            this.id = rid;

            let config = {
                extraPlugins: ['imageresizerowandcolumn','autogrow','add_media'],
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] }
                ],
                removeButtons: 'Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Iframe,About,Image',
                imageResize: {
                    maxWidth : 800, maxHeight : 800
                },
            };

            CKEDITOR.replace( rid , config);
        });
    });
}

if (typeof tinymce !== 'undefined') {
    tinymce_init = function(element='textarea.editor:not(.init)') {
        $(function() {

            $(element).each(function() {
                const rid = 'mce__'+Math.random().toString().substr(5);
                //this.id = rid;
                const _this = this;
                let config = {
                    selector: '#'+_this.id,
                    toolbar_sticky:true,
                    toolbar: "undo redo | styleselect | fontselect | removeformat fullscreen code visualblocks | casechange blocks a11ycheck fontsize forecolor backcolor | bold italic underline |  alignleft aligncenter alignright alignjustify |  bullist numlist checklist outdent indent | insertMediaImage",
                    font_size_formats:'10px 12px 14px 16px 18px 20px 22px',
                    convert_urls: false,
                    setup: (editor)=>{
                        editor.ui.registry.addButton('insertMediaImage', {
                            icon: 'insert-time',
                            tooltip: 'Insert Current Date',
                            onAction: function() {
                                // $('#add-media-btn .browse_media').click();
                                const media_btn_ = $('#'+_this.id).data('media-btn');
                                $(media_btn_+' .browse_media').click();
                            }
                        });
                    }
                };
                if(typeof $(this).data('plugins') !== "undefined") {
                    config.plugins = "code,fullscreen,table,autogrow"+$(this).data('plugins')
                }

                tinymce.init(config);
                $(element).addClass('init');
            });
        })
    }

    tinymce_init();
}
