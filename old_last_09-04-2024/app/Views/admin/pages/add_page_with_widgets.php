<div class="container">
<form method="post" class="form">

    <?php
    $data = [
      'id'=>0,
      'title'=>'',
      'meta_title'=>'',
      'meta_keywords'=>'',
      'meta_description'=>'',
      'meta_image'=>'',
      'page_slug'=>'',
      'banner_img'=>'',
      'carousel_images'=>'',
      'banner_title'=>'',
      'banner_content'=>'',
      'content'=>'',
      'content_title'=>'',
      'content_subtitle'=>'',
      'content_footnote'=>'',
      'video_title'=>'',
      'video_footnote'=>'',
      'video_embed_code'=>'',
      'video_autoplay'=>'',
      'video_loop'=>'',
      'status'=>'',
      'content_carousel'=>'',
        'content_product_title'=>'',
        'content_product_subtitle'=>'',
        'content_product_btn_text'=>'',
        'content_product_cats'=>'',
        'content_product_limit'=>'',
        'content_product_text'=>'',
        'content_product_footnote'=>'',
    ];
    $contents = [];
    $mode = 'add';
    if(!empty($page_data)) {
        $data = [
            'id'=>$page_data['id'],
            'title'=>$page_data['page_title'],
            'meta_title'=>$page_data['meta_title'],
            'meta_keywords'=>$page_data['meta_keywords'],
            'meta_description'=>$page_data['meta_description'],
            'meta_image'=>$page_data['meta_image'],
            'status' => $page_data['status'],
            'page_slug'=>$page_data['page_slug']
        ];

        $contents = !empty($page_data['content']) ? json_decode($page_data['content'],true) : '';

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
            <div class="header">
                <div class="field-title">Meta Tags</div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Page Title</label>
                        <input data-slug="#slug" name="page_title" value="<?php echo $data['title'] ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input_field">
                        <label>URL Slug</label>
                        <input id="slug" name="page_slug" value="<?php echo $data['page_slug'] ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Status</label>
                        <select name="status" class="select2" value="<?php echo $data['status'] ?>">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
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

                <div class="col-md-6">
                    <div class="input_field pt-10" style="width: 380px">
                        <label>Meta Image</label>
                        <div>
                            <?php
                                echo upload_media_box([
                                    'input_name'=>'meta[image]',
                                    'replacemedia'=>1,
                                    'images' =>  !empty($data['meta_image']) ? [$data['meta_image']] : ''
                                ]);
                            ?>
                        </div>
                    </div>
                </div>


            </div>
            </fieldset>

            </div>

            </div>
        </div>

        <?php
        $widgets = [
            'banner'=> [
                'template'=>'banner',
                'name'=>'Top Banner'
            ],
            'carousel' => [
                'template' => 'carousel',
                'name' => 'Carousel'
            ],
            'content' => [
                'template' => 'content',
                'name' => 'Content',
                'media_button_label' => 'Add Media'
            ],
            'product_boxes' => [
                'template' => 'product_boxes',
                'name' => 'Product boxes'
            ],
            'triple_box_row' => [
                'template' => 'triple_box_row',
                'name' => 'Triple box row'
            ],
            'video' => [
                'template' => 'video',
                'name' => 'Product video'
            ],
            'map' => [
                'template' => 'map',
                'name' => 'Map'
            ],
            'posts_loop' => [
                'template' => 'posts_loop',
                'name' => 'Posts Loop'
            ],
            'quote_box' => [
                'template' => 'quote_box',
                'name' => 'Quote box'
            ]
        ];
        ?>

        <div class="widget_listing pt-20">

                <?php
                $idx = 0;
                if(!empty($contents)) {
                    foreach($contents as $idx=>$content) {
                        $id = key($content);
                        $content[$id]['id'] = $id;
                        $content[$id]['index'] = $idx;
                        $widget_data = $widgets[$id];
                        $content[$id]['widget_label'] = $widget_data['name'];
                        $content[$id]['idx'] = $idx;

                        //pr($content[$id],false);
                        ?>
                        <div class="_content">
                            <?php
                            echo view('admin/pages/widgets/'.$id,$content[$id]);
                            ?>
                        </div>
                        <?php
                        $idx++;
                    }
                }

                ?>


        </div>

        <datalist id="widget_classes">
            <option value="three_col">
            <option value="two_col">
            <option value="line-break">
        </datalist>


        <datalist id="widget_paddings">
            <option value="130px 0 0">
            <option value="95px 0 0">
            <option value="55px 0 0">
            <option value="80px 0 80px">
            <option value="80px 0 0">
        </datalist>



        <div class="page_footer">

            <div class="d-inline-block" style="vertical-align: middle;">
                <div class="widget_append">

                    <div class="text-center" style="vertical-align: top;display: table;width: auto;float: none;margin: auto;">
                        <select onchange="select_page_widget(this)" id="select-widgets" class="select2" name="page_widgets" style="min-width: 200px">
                            <option value="">Add Widget</option>
                            <?php foreach($widgets as $id=>$widget) {
                                ?>
                                <option value="<?php echo $id ?>" data-template="<?php echo $widget['template'] ?>"><?php echo $widget['name'] ?></option>
                                <?php
                            } ?>
                        </select>
                    </div>



                    <script>
                        function select_page_widget(input) {
                            if(input.value) {
                                let widget_id = input.value;
                                const option = input.querySelector(":scope option[value='"+widget_id+"']");
                                const widget_label = option.innerText;
                                const template = option.getAttribute('data-template');
                                let widget_count = document.querySelectorAll('.widget_listing > ._'+widget_id).length;
                                const rid = '_'+Math.random().toString().substr(6);

                                let widget_class = widget_id+'_'+widget_count;

                                let listing = document.querySelector('.widget_listing');
                                listing.innerHTML += `<div id="${rid}" class="_${widget_id}"><fieldset><div class="label">Loading..</div></fieldset></div>`;
                                const idx = listing.childElementCount-1;

                                const url = '<?php echo site_url() ?>admin/pages/getwidgetview/' + template;

                                fetch(url).then(res => res.text()).then(res => {
                                    let data = res.replaceAll('{{id}}','content['+idx+']['+widget_id+']');
                                    data = data.replaceAll('{{label}}',widget_label);
                                    document.querySelector('.widget_listing > #'+rid).innerHTML = data;
                                    document.querySelector('.widget_listing > #'+rid+' [name*=widget_order]').value = idx;
                                    tinymce_init();
                                });

                                input.value = '';
                                input.dispatchEvent(new Event('change'));
                            }
                        }

                        function closeWidget(element) {
                            if(confirm('Remove this element?')) {
                                element.closest('._content').remove();
                            }
                            document.querySelectorAll('.widget_listing > ._content').forEach((element,idx)=>{
                                element.querySelector(':scope .widget_order').value = idx;
                            });
                        }
                    </script>

                </div>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;

            <div class="d-inline-block" style="vertical-align: middle;">
                <a target="_blank" href="<?php echo site_url($data['page_slug']) ?>" class="btn back">View Page</a>
            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;
            <div class="d-inline-block" style="vertical-align: middle;">
            <div>
                <input type="hidden" name="operation" value="<?php echo $mode ?>">
                <input type="hidden" name="page_id" value="<?php echo $data['id'] ?>">
                <button type="submit" class=" btn save">Save changes</button>
            </div>
            </div>
        </div>


    </div>
</form>
</div>
