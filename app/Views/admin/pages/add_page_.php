<style>
    .pollSlider {
        position: absolute;
        height: 100%;
        background: white;
        width: 232px;
        right: 0px;
        margin-right: -239px;
        z-index: 100;
        height: 90%;
        bottom: 0;
    }

    #pollSlider-button {
        position: fixed;
        width: 100px;
        height: 50px;
        right: 0px;
        background: #d4d4d5de;
        top: 300px;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 33px;
        cursor: pointer;
    }

    .page_input {
        width: 100%;
        max-width: 90%;
        margin: 0 auto;
        margin-top: 19px;
    }

    .page_input div {
        padding: 5px 0;
    }

    .page_input input, .page_input textarea {
        width: 94%;
        border-radius: 5px;
        padding: 6px 5px;
        border: 1px solid #ccc;
    }
</style>

<div id="gjs"></div>
<form id="my-form">
    <input type="hidden" name="page_id" value="<?php echo $pageid; ?>">
    <div class="pollSlider">
        <div class="page_input">
            <div>
                <label>Page Title</label>
                <input type="text" value="<?php echo $page_data['page_title'] ?>" name="page_title"/>
            </div>
            <div>
                <label>Meta Keyword</label>
                <input type="text" value="<?php echo $page_data['page_keyword'] ?>" name="page_keyword"/>
            </div>
            <div>
                <label>Meta Description</label>
                <textarea rows="10" cols="25"
                          name="page_description"><?php echo $page_data['page_description'] ?></textarea>
            </div>
            <div class="input_check">

                <input type="checkbox" name="show_in_menu" value="1" <?php echo $page_data['show_in_menu']?'checked':'' ?> >
                <label>Show in Menu</label>

            </div>
        </div>

    </div>
    <div id="pollSlider-button">
        <i class="icon-angle-left"></i>
    </div>
    <div id="gjs"></div>
    <div class="submit-page-btn">
        <div>
            <button type="button" onclick="$('.fa-floppy-o').click();">Submit</button>
        </div>
    </div>

</form>


<script type="text/javascript">
    const projectID = '<?php echo $pageid; ?>';

    const projectEndpoint = `<?php echo site_url('') . ADMIN?>/pages/`;

    var editor = grapesjs.init
    ({

        height: '100%',
        fromElement: false,
        clearOnRender: true,
        container: '#gjs',
        plugins: ['gjs-preset-webpage'],
        storageManager: {
            type: 'remote',
            contentTypeJson: true,
            stepsBeforeSave: 1,
            storeComponents: true,
            storeStyles: true,
            storeHtml: true,
            storeCss: true,
            headers: {
                'Content-Type': 'application/json'
            },
            json_encode:{
                "gjs-html": [],
                "gjs-css": [],
            },
            options: {
                remote: {
                    urlLoad: projectEndpoint + `getresult/${projectID}`,
                    urlStore: projectEndpoint + "setResult",
                    // The `remote` storage uses the POST method when stores data but
                    // the json-server API requires PATCH.
                    fetchOptions: opts => (opts.method === 'POST' ? {method: 'PATCH'} : {}),
                    // As the API stores projects in this format `{id: 1, data: projectData }`,
                    // we have to properly update the body before the store and extract the
                    // project data from the response result.
                    //onStore: data => ({ id: 1, data })
                    onStore: () => {

                    }
                }
            }
        },
    });

    editor.Panels.addButton
    ('options',
        [{
            id: 'save-db',
            className: 'fa fa-floppy-o',
            command: 'save-db',
            attributes: {title: 'Save DB'}
        }]
    );

    // Add the command
    editor.Commands.add
    ('save-db',
        {
            run: function (editor, sender) {
                sender && sender.set('active', 0); // turn off the button
                editor.store();
                let  $formdata =  new FormData(document.querySelector('#my-form'))
                var htmldata = editor.getHtml();
                var cssdata = editor.getCss();
                $formdata.set('html',htmldata);
                $formdata.set('css',cssdata);



                let timerInterval
                Swal.fire({
                    title: 'Please wait...',
                    html: 'It will close in <b></b> milliseconds.',
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        fetch('<?php echo base_url(ADMIN . '/pages/savePostData')?>', {
                            method: 'POST',
                            body: $formdata
                        })
                            .then((response) => response.json())
                            .then((myBlob) => {
                                myImage.src = URL.createObjectURL(myBlob);
                            });

                        // Swal.showLoading()
                        // const b = Swal.getHtmlContainer().querySelector('b')
                        // timerInterval = setInterval(() => {
                        //     b.textContent = Swal.getTimerLeft()
                        // }, 100)

                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {

                        console.log('I was closed by the timer');

                    }
                })
            }
        });
    editor.loadProjectData()
</script>


</body>
</html>