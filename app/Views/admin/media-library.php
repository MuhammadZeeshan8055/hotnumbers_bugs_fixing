<div class="container">

    <?php admin_page_title('Media Library'); ?>

    <div class="featured_page">
        <div class="books_page featured">
            <div class="upload_media_images_gallery">


                <fieldset class="mt-40">
                    <?php echo view('admin/includes/media-library-viewer',['media_files'=>$media_files]) ?>
                </fieldset>
            </div>
        </div>
    </div>

</div>

