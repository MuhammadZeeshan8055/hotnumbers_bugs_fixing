<div class="container">
    <?php admin_page_title($title); ?>

    <div class="books_page featured featured_page datatable  ">


        <form class="mt-60"
              action="<?php echo base_url(ADMIN . '/blog/add_post') ?>"
              method="post"
              enctype="multipart/form-data">


            <?php if (session('msg')) :
                message_notice(session('msg'));
            endif;

            ?>


            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12">
                    <div class="input_field">
                        <?php
                        $images = !empty($post_row['img']) ? array_values(explode(',',$post_row['img'])) : [];
                        upload_media_box([
                            'input_name'=>'img',
                            'replacemedia'=>1,
                            'images' =>  $images
                        ]);
                        ?>
                    </div>
                    <br>
                    <br>
                </div>



                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input_field">
                                <label>Post title</label>
                                <input data-slug="#slug" type="text" name="title"
                                       value="<?php echo !empty($post_row['title']) ? $post_row['title'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="input_field">
                                <label>Post
                                    date <?php echo !empty($post_row['post_date']) ? date('d-m-Y', strtotime($post_row['post_date'])) : date('Y-m-d'); ?></label>
                                <input type="date" name="post_date"
                                       value="<?php echo !empty($post_row['post_date']) ? date('d-m-Y', strtotime($post_row['post_date'])) : date('Y-m-d'); ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input_field">
                                <label>Scheduled post date
                                    :<?php echo !empty($post_row['scheduled_date']) ? date('d-m-Y', strtotime($post_row['scheduled_date'])) : date('Y-m-d'); ?></label>
                                <input type="date" name="scheduled_date"
                                       value="<?php echo !empty($post_row['scheduled_date']) ? date('d-m-Y', strtotime($post_row['scheduled_date'])) : date('Y-m-d'); ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="input_field">
                                <label>Post URL </label>
                                <input id="slug" type="text" name="slug"
                                       value="<?php echo !empty($post_row['slug']) ? $post_row['slug'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="input_field">
                                <label>Post category </label>
                                <select name="category" class="select2" value="<?php echo !empty($post_row['category']) ? $post_row['category'] : '' ?>">
                                    <option value="">Select category</option>
                                    <?php if(!empty($categories)) {
                                        foreach($categories as $category) {
                                            ?>
                                            <option value="<?php echo $category->id ?>"><?php echo $category->name ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="input_field">
                                <label>Post status </label>
                                <select name="post_status" value="<?php echo !empty($post_row['post_status']) ? $post_row['post_status'] : 'publish' ?>">
                                    <option value="publish">Publish</option>
                                    <option value="unpublish">Unpublish</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="input_field">
                                <label>Post type </label>
                                <select name="post_type" value="<?php echo !empty($post_row['post_type']) ? $post_row['post_type'] : 'post' ?>">
                                    <option value="post">Post</option>
                                    <option value="gig_event">Gig/Event</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-12 mt-20  ckeditor">


                    <div class="input_field ">
                        <label>Post content</label>
                        <br>
                        <br>
                        <?php
                        $label =  !empty($media_button_label) ? $media_button_label : 'Add Media';
                        upload_media_box([
                            'textarea'=>'#content-editor-1',
                            'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;'. $label . '</i>',
                            'multiple'=>false,
                            'input_name'=>''
                        ],false);
                        ?>
                        <br>
                        <textarea id="content-editor-1" name="content" class="editor" rows="10"><?php echo !empty($post_row['content']) ? $post_row['content'] : '' ?></textarea>
                    </div>
                </div>
                <div class="col-lg-12 btn_bar flex-end ">
                    <input type="hidden" name="post_id" value="<?php echo !empty($post_row['post_id']) ? $post_row['post_id'] : '' ?>">
                    <button type="submit" class=" btn save">Save changes</button>
                </div>




        </form>

    </div>
</div>