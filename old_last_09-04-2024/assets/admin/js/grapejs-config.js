grapesjs.plugins.add('disable-device-input', function(editor, options) {
    // remove the devices switcher
    editor.getConfig().showDevices = false;
    // remove the view code button
    var codeButton = editor.Panels.getButton("options", "export-template");
    codeButton.collection.remove(codeButton);
});

const blocks_config = [
    {
        id: 'block-h1-title',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 3V21M18 12H7M18 3V21M4 21H8M4 3H8M16 21H20M16 3H20" stroke="#fafafa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>H1</b>',
        content: `<h1>Sample Title</h1>`,
        category: 'Heading',
        attributes: {
            title: 'Insert H1'
        }
    },
    {
        id: 'block-h2-title',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 3V21M18 12H7M18 3V21M4 21H8M4 3H8M16 21H20M16 3H20" stroke="#fafafa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>  <b>H2</b>',
        content: `<h2>Lorem Ipsum</h2>`,
        category: 'Heading',
        attributes: {
            title: 'Insert H2'
        }
    },
    {
        id: 'block-h3-title',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 3V21M18 12H7M18 3V21M4 21H8M4 3H8M16 21H20M16 3H20" stroke="#fafafa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>H3</b>',
        content: `<h3>Lorem Ipsum</h3>`,
        category: 'Heading',
        attributes: {
            title: 'Insert H3'
        }
    },
    {
        id: 'block-h4-title',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 3V21M18 12H7M18 3V21M4 21H8M4 3H8M16 21H20M16 3H20" stroke="#fafafa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>H4</b>',
        content: `<h4>Lorem Ipsum</h4>`,
        category: 'Heading',
        attributes: {
            title: 'Insert H4'
        }
    },
    {
        id: 'block-h5-title',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 3V21M18 12H7M18 3V21M4 21H8M4 3H8M16 21H20M16 3H20" stroke="#fafafa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>H5</b>',
        content: `<H5>Lorem Ipsum</H5>`,
        category: 'Heading',
        attributes: {
            title: 'Insert H5'
        }
    },
    {
        id: 'block-h6-title',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 3V21M18 12H7M18 3V21M4 21H8M4 3H8M16 21H20M16 3H20" stroke="#fafafa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>H6</b>',
        content: `<H6>Lorem Ipsum</H6>`,
        category: 'Heading',
        attributes: {
            title: 'Insert H6'
        }
    },
    {
        id: 'block-text',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 3V21M9 21H15M19 6V3H5V6" stroke="#fcfcfc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>Text</b>',
        content: `<p>Lorem Ipsum</p>`,
        category: 'Content',
        attributes: {
            title: 'Insert Text'
        }
    },
    {
        id: 'block-image',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 3V21M9 21H15M19 6V3H5V6" stroke="#fcfcfc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>Text</b>',
        content: `<p>Lorem Ipsum</p>`,
        category: 'Content',
        attributes: {
            title: 'Insert Text'
        }
    },
    {
        id: 'block-row',
        label: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 3V21M9 21H15M19 6V3H5V6" stroke="#fcfcfc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> <b>Row</b>',
        components: {
            type: 'row'
        },
        category: 'Content',
        attributes: {
            title: 'Insert Text'
        }
    }

];

grapesjs.plugins.add('grapesjs-preset-webpage');

document.querySelectorAll('.email-editor').forEach((element)=>{
    const config = {
        container: element,
        width: 'auto',
        storageManager: {
            type: 'local',
            autosave: false,
        },
        plugins: ['grapesjs-preset-webpage'],
        pluginsOpts: {
            'grapesjs-preset-webpage': {
                // options
            }
        },
        blockManager: {
           // blocks: blocks_config,
        },
        canvas: {
            styles: [
                'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css'
            ],
            scripts: [
                'https://code.jquery.com/jquery-3.3.1.slim.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
                'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'
            ],
        }
    };

    const editor = grapesjs.init(config);
});


