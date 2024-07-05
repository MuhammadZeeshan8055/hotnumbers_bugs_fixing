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
    $mode = 'add';
    if(!empty($page_data)) {
        $data = [
            'id'=>$page_data['id'],
            'title'=>$page_data['page_title'],
            'meta_title'=>$page_data['meta_title'],
            'meta_keywords'=>$page_data['meta_keywords'],
            'meta_description'=>$page_data['meta_description'],
            'status' => $page_data['status'],
            'page_slug'=>$page_data['page_slug'],
            'banner_img'=>$page_data['banner_images'],
            'carousel_images'=>$page_data['carousel_images'],
            'banner_title'=>$page_data['banner_title'],
            'banner_content'=>$page_data['banner_content'],
            'content'=>$page_data['content'],
            'content_subtitle'=>$page_data['content_subtitle'],
            'content_footnote'=>$page_data['content_footnote'],
            'content_components'=>$page_data['content_components'],
            'video_title'=>$page_data['video_title'],
            'video_footnote'=>$page_data['video_footnote'],
            'video_embed_code'=>$page_data['video_embed_code'],
            'video_autoplay'=>$page_data['video_autoplay'],
            'video_loop'=>$page_data['video_loop'],
            'content_carousel'=>$page_data['content_carousel'],
            'content_product_title'=>$page_data['content_product_title'],
            'content_product_subtitle'=>$page_data['content_product_subtitle'],
            'content_product_btn_text'=>$page_data['content_product_btn_text'],
            'content_product_cats'=>$page_data['content_product_cats'],
            'content_product_limit'=>$page_data['content_product_limit']
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
                    <?php echo view('admin/pages/widgets/banner',[
                            'id'=>'banner',
                            'label'=> 'Top banner',
                            'banner_img' => $data['banner_img'],
                            'banner_title' => $data['banner_title'],
                            'banner_content' => $data['banner_content']
                    ]); ?>
                </div>
            </div>
        </div>

        <div class="books_listing">
            <?php echo view('admin/pages/widgets/carousel',[
                'id'=>'main_carousel',
                'label'=> 'Main Carousel',
                'carousel_images' => $data['banner_img']
            ]); ?>
        </div>

        <div class="books_listing">
            <?php echo view('admin/pages/widgets/content',[
                'id'=>'main_content',
                'label'=> 'Content',
                'carousel_images' => $data['banner_img'],
                'title' => $data['content_title'],
                'subtitle' => $data['content_subtitle'],
                'footnote' => $data['content_footnote'],
                'content' => $data['content']
            ]); ?>

            <?php echo view('admin/pages/widgets/video',[
                'id'=>'video',
                'label'=> 'Video',
                'title' => $data['video_title'],
                'footnote' => $data['video_footnote'],
                'embed_code' => $data['video_embed_code'],
                'video_autoplay' => $data['video_autoplay'],
                'video_loop' => $data['video_loop']
            ]); ?>

        </div>

        <?php echo view('admin/pages/widgets/carousel',[
            'id'=>'content_carousel',
            'label'=> 'Content Carousel',
            'carousel_images' => $data['content_carousel']
        ]); ?>

        <?php echo view('admin/pages/widgets/trio_product',[
            'id'=>'content_product',
            'label'=> 'Trio product carousel',
            'title' => $data['content_product_title'],
            'subtitle' => $data['content_product_subtitle'],
            'button_text' => $data['content_product_btn_text'],
            'content' => $data['content_product_text'],
            'product_cats' => $data['content_product_cats'],
            'limit' => $data['content_product_limit'],
            'footnote' => $data['content_product_footnote']
        ]); ?>

        <?php echo view('admin/pages/widgets/content',[
            'id'=>'content_product',
            'label'=> 'Trio product carousel',
            'title' => $data['content_product_title'],
            'subtitle' => $data['content_product_subtitle'],
            'button_text' => $data['content_product_btn_text'],
            'content' => $data['content_product_text'],
            'product_cats' => $data['content_product_cats'],
            'limit' => $data['content_product_limit'],
            'footnote' => $data['content_product_footnote']
        ]); ?>



        <div class="pt-20">
            <input type="hidden" name="operation" value="<?php echo $mode ?>">
            <input type="hidden" name="page_id" value="<?php echo $data['id'] ?>">
            <button type="submit" class=" btn save">Save changes</button>
        </div>


    </div>
</form>
</div>
