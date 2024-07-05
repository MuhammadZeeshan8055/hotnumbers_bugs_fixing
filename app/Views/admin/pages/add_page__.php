<div class="container">
<form method="post" class="form">

    <?php
    $data = [
      'id'=>0,
      'title'=>'',
      'meta_title'=>'',
      'meta_keywords'=>'',
      'meta_description'=>'',
      'page_slug'=>'',
      'banner_img'=>'',
       'carousel_images'=>'',
      'banner_title'=>'',
      'banner_content'=>'',
      'content'=>'',
      'content_components'=>'',
      'status'=>'',
    ];
    $mode = 'add';
    if(!empty($page_data)) {
        $data = [
            'id'=>$page_data['id'],
            'title'=>$page_data['page_title'],
            'meta_title'=>$page_data['meta_title'],
            'meta_keywords'=>$page_data['meta_keywords'],
            'meta_description'=>$page_data['meta_description'],
            'page_slug'=>$page_data['page_slug'],
            'banner_img'=>$page_data['banner_images'],
            'carousel_images'=>$page_data['carousel_images'],
            'banner_title'=>$page_data['banner_title'],
            'banner_content'=>$page_data['banner_content'],
            'content'=>$page_data['content'],
            'content_components'=>$page_data['content_components'],
            'status'=>$page_data['status'],
        ];
        $mode = 'edit';
    }
    ?>
    <br>

    <div class="books_listing">
        <h3><?php echo !empty($page_data) ? 'Edit':'Add' ?> Page</h3>
    </div>

    <br>
    <?php
    $page_url = '';
    if(!empty($data['id'])) {
        ?>
        <div class="input_field">
            <div style="padding: 12px 5px">
                <a target="_blank" href="<?php echo site_url($data['page_slug']) ?>" class="btn back">View Page</a>
            </div>
        </div>
        <?php
        $page_url = site_url($data['page_slug']);
    }
    ?>

    <div class="datatable" style="padding-top: 0;">
        <div class="books_listing">
            <div class="row">
            <div class="col-md-12">


            <fieldset>
            <div class="field-title">Meta Tags</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input_field">
                        <label>Page Title</label>
                        <input data-slug="#slug" name="page_title" value="<?php echo $data['title'] ?>">
                    </div>

                    <div class="input_field">
                        <label>URL Slug</label>
                        <input id="slug" name="page_slug" value="<?php echo $data['page_slug'] ?>">
                    </div>

                    <div class="input_field">
                        <label>Meta Title</label>
                        <input name="meta[title]" value="<?php echo $data['meta_title'] ?>">
                    </div>

                    <div class="input_field">
                        <label>Status</label>
                        <select name="status" class="select2" value="<?php echo $data['status'] ?>">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                        <div class="input_field pt-10">
                            <label>Meta Description</label>
                            <div>
                                <textarea rows="2" name="meta[description]"><?php echo $data['meta_description'] ?></textarea>
                            </div>
                        </div>

                        <div class="input_field pt-10">
                            <label>Meta Keywords</label>
                            <div>
                                <textarea rows="2" name="meta[keywords]"><?php echo $data['meta_keywords'] ?></textarea>
                            </div>
                        </div>
                </div>


            </div>
            </fieldset>

            </div>

                <div class="col-md-12">
                    <fieldset>
                        <div class="field-title">Top Banner</div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input_field">
                                    <label>Images</label>
                                    <?php
                                    upload_media_box([
                                        'input_name'=>'banner_img',
                                        'images'=>explode(',',$data['banner_img']),
                                        'replacemedia'=>1
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="input_field">
                                    <label>Title</label>
                                    <input name="banner_title" value="<?php echo $data['banner_title'] ?>">
                                </div>

                                <div class="input_field">
                                    <label>Content</label>
                                    <div>
                                        <textarea name="banner_content" rows="9"><?php echo htmlentities(stripslashes($data['banner_content'])) ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="books_listing">
            <fieldset>
                <div class="field-title">Image Carousel</div>
                <div class="image-carousel-3">
                    <?php
                    upload_media_box([
                        'input_name'=>'carousel_images',
                        'images'=>explode(',',$data['carousel_images']),
                        'multiple'=>true
                    ],false);
                    ?>
                </div>
                <br>
            </fieldset>
        </div>

        <div class="books_listing">
            <fieldset>
                <div class="field-title">Content</div>

                <div style="padding: 15px 15px 30px">
                    <?php
                    function componentHtml($html=[]) {
                        $data = [
                            'component_id'=>'',
                            'component_title'=>'',
                            'component_text'=>''
                        ];
                        if(!empty($html['component_title'])) {
                            $data = [
                                'component_id'=>$html['component_id'],
                                'component_title'=>$html['component_title'],
                                'component_text'=>$html['component_text']
                            ];
                        }

                        $componentHtml = '<div class="component-row">
                                    <div class="text_left">
                                        <div class="form-row input_field">
                                            <label>ID</label>
                                            <input class="component_id_input copyText" style="cursor: pointer" title="Click to copy component ID" data-text="['.$data['component_id'].']" type="text" name="component[component_id][]" value="'.$data['component_id'].'" readonly>
                                        </div>
                                        
                                        <div class="form-row input_field">
                                            <label>Title</label>
                                            <input type="text" name="component[component_title][]" value="'.$data['component_title'].'">
                                        </div>
                                        <br>
                                        <br>
                                        <div class="form-row input_field">
                                        '.upload_media_box([
                                            'textarea'=>'#component-editor-{{id}}',
                                            'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;Add Media',
                                            'multiple'=>true,
                                            'return'=>true
                                        ],false).'
                                            <br>
                                            <label>Content</label>
                                            <div id="component-editor-{{id}}">
                                                <textarea cols="3" type="text" name="component[component_text][]" style="width: 100%">'.$data['component_text'].'</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>';
                        return $componentHtml;
                    }
                    ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="input_field">
                                <h4 style="color: #000;font-weight: 600;margin-bottom: -20px;">Templates</h4>
                                <br>
                                <br>
                                <div id="components">
                                    <?php
                                    if(!empty($data['content_components'])){
                                        $components = json_decode($data['content_components'],true);
                                        foreach($components as $component) {
                                            echo componentHtml($component);
                                        }
                                    }
                                    ?>
                                </div>

                                <div>
                                    <a href="#" class="btn back add-component">Add Component</a>
                                </div>
                                <br>

                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input_field">
                                <div>
                                    <br>
                                    <div class="text_left" style="display: inline-block">
                                        <?php
                                        upload_media_box([
                                            'textarea'=>'#content-editor',
                                            'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;Add Media',
                                            'multiple'=>true
                                        ],false);
                                        ?>
                                    </div>
                                    <br>
                                    <br>
                                    <div id="content-editor">
                                        <textarea style="height: 800px" class="editor" <?php echo $page_url ? 'data-page-url="'.$page_url.'"':'' ?> data-plugins="autoresize,visualblocks" name="content"><?php echo htmlentities(stripslashes($data['content'])) ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>

        <script>
            $(function() {
                $('.add-component').on('click', function(e) {
                    e.preventDefault();
                    let html = `<?php echo componentHtml() ?>`;
                    const rid = Math.random().toString().substr(4);
                    const _ID = 'component_'+rid;
                    html = html.replaceAll('{{id}}',rid);
                    $('#components').append(html);
                    $('#components > div:last-child .component_id_input').val(_ID);
                });
            })
        </script>



        <div class="pt-20">
            <input type="hidden" name="operation" value="<?php echo $mode ?>">
            <input type="hidden" name="page_id" value="<?php echo $data['id'] ?>">
            <button type="submit" class=" btn save">Save changes</button>
        </div>


    </div>
</form>
</div>
