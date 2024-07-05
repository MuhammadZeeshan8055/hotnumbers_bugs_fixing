<div class="header">
    <div class="row">
        <div class="col-md-6" style="padding-left: 0">
            <div class="form-row">
                <form class="form pt-5 pb-5 media_gallery_upload" method="post" action="<?php echo base_url('admin/media-library/upload-media') ?>" enctype="multipart/form-data">
                    <input type="file" name="upload_files[]" required multiple accept="image/*" style="width: 210px;">
                    <button name="file_upload" class="btn btn-sm btn-primary bg-red" type="submit">Upload</button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="pull-right head-search">

                <div class="d-inline-block">
                    <div class="gallery_footer animated fadeInUp <?php echo !empty($selected_medias) ? 'open':'' ?>">
                            <span id="item_selected_count">
                                <?php if(!empty($selected_medias)) {
                                    $mcount = explode(',',$selected_medias);
                                    $mtext = count($mcount) == 1 ? 'item':'items';
                                    echo count($mcount).' '.$mtext.' selected';
                                } ?>
                            </span>
                        <a href="#" class="btn btn-secondary btn-sm back save">Select</a>
                    </div>
                </div>
                <div class="d-inline-block">
                    <form class="form pt-5 pb-5" onsubmit="return search_gallery_media(this)" method="post" action="<?php echo base_url('admin/media-library/search-media') ?>" enctype="multipart/form-data">
                        <div class="form_input">
                            <input type="search" name="search" class="form-control" placeholder="Search.." id="search">
                            <button type="submit" name="search" value="1"><img width="16" src="data:image/png;base64,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"></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

</div>
<br>
<div class="media-library-viewer">
    <div class="library-filter">

    </div>
    <div class="library-view">

        <?php
        $media = model('Media');
        if(!empty($media_files)) {
            $full_view_icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAmdJREFUWEftl0tPE1EUgL+ZjpTWliJSRIxGSBEE4ws1GnbEhS8MhMTEH+DGnTv9Ae6IiRsTw9ZE41ZNXLFTok184BMxIBoIUkofU9pOOw/Tp0gLHdRSTbibuZmce853njNXoMJLqLB9Kg9wbfB2e7XV9kgURGk9o6EbuhpXYmeFq4NDXfvbWh5fPNdTv54Adx8Oz4+OTZzaACiIQCQJr+fKk4wDDeDYlNG9YgpCCjyZKQ9AdxO4rBsA/1MEUmPSZgVRBKc1s9d0CC5CKPprnbjsULsZLCLEFJAV0PXM3siKmq6Bj2GokjKGbVXFCzIQASk7O1UVtjiKy8USGRBFhb01JorQr4BfL08X1ItQV6oLUgBzKgjZz5SYfc7Mhnj+corWFjedbdtNE6Z9McAwoEGCraUA5uOwkEsa8G06wFPvJOMTvrzR1mY3p092UOOsTr9LJjXef/iK98VnQuEoA30n2L2roQCyToD6zJGVB1EKwKfqvBubZcQ7ic8fKeptdZVEZ/s2Fvx+Rt9+IZFQ83Kp6HUd8tDfexy7PesyYArg0/cYN+89Q44opsKcVOIsygE0TSuQdzps9J45xpHDnnRKTQG8mgpz486IKeM5IcMwiC6GiUflnz23RIOnpZGBvm46GmtLp+B3AHK21KRCJBxEU5MFDkiShetX+tm307V6DYzPRhl68AZZjqT7d61LEATsNglLrn2WKLh0/iB7mpyrA0wHNW4NFy+8tcIsl7/c42BHreUfB/DJOve9ywb9n7qePX/hqB23U1w9An/JVkk1Gz+l+QhU/GJSMlllFqj83bDMDpZU/wO12cRs/LtknAAAAABJRU5ErkJggg==';

            $clipboard_icon = 'data:image/png;base64,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';

            $new_tab_icon = 'data:image/png;base64,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';

            $trash_icon = 'data:image/png;base64,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';

            ?>
            <div class="media-gallery-rows library-view">
                <?php
                $selectedIDs = !empty($selected_medias) ? explode(',',$selected_medias) : [];
                foreach($media_files as $media_file){
                    $src_thumb = $media->get_media_src($media_file->id,'','thumbnail');
                    $src = $media->get_media_src($media_file->id);
                    ?>
                    <div class="media-row <?php echo in_array($media_file->id,$selectedIDs) ? 'selected':'' ?>" data-page="<?php echo $page ?>" data-id="<?php echo $media_file->id ?>">
                        <div class="media-toolbar">
                            <ul class="context">
                                <li title="Full view" onclick="$('#media-image-<?php echo $media_file->id ?>').trigger('click');"><div>
                                        <img src="<?php echo $full_view_icon ?>">
                                    </div></li>

                                <li title="Copy to clipboard"><div class="copyText" data-text="<?php echo base_url('assets/images/site-images/'.$media_file->path) ?>"><img src="<?php echo $clipboard_icon ?>"> </div></li>

                                <li title="Open in new tab"><a onclick="$('ul.context.is-visible').removeClass('is-visible');" style="color: #000;" target="_blank" href="<?php echo base_url('assets/images/site-images/'.$media_file->path) ?>"><img src="<?php echo $new_tab_icon ?>"></a></li>

                                <li title="Delete">
                                    <a id="media-remove-<?php echo $media_file->id ?>" class="media-remove" data-id="<?php echo $media_file->id ?>" data-page="<?php echo !empty($_GET['page']) ? $_GET['page'] : 1 ?>">
                                        <img src="<?php echo $trash_icon ?>"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="media-image">
                            <a id="media-image-<?php echo $media_file->id ?>" data-fancybox="gallery" data-title="<?php echo $media_file->name ?>" data-path="<?php echo $media_file->name ?>" href="<?php echo $src ?>">
                                <img ondrag="return false;" alt="<?php echo $media_file->name ?>" src="<?php echo urldecode($src_thumb)?>"></a>
                        </div>
                        <div class="media-caption">
                            <div class="left">
                                <h3></span> <span class="name"><?php echo $media_file->name ?></span></h3>
                                <div class="text-center media-size"><?php echo filesize_text($media_file->size) ?></div>
                            </div>
                            <div class="right">
                                <h3>ID:&nbsp;<span><?php echo $media_file->id ?></span></h3>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <input type="hidden" id="media-library-selected-ids" value="<?php echo !empty($selected_medias) ? $selected_medias : '' ?>">

            <?php
            $count_per_page = !empty($limit) && !empty($filecount) ? round($filecount/$limit) : 0;
            $pagi_show = $count_per_page > 5 ? 5 : $count_per_page;
            ?>
            <div class="pagination-holder">
                <ul class="pagination">
                    <?php
                    $total_show = 0;
                    for($i=1; $i<=$count_per_page; $i++) {

                        ?>
                        <li><a onclick="mediaBrowserPagination(<?php echo $i; ?>,this); return false" class="<?php echo $page == $i ? 'active':'' ?>" href="?page=<?php echo $i ?>"><?php echo $i ?></a> </li>
                        <?php

                    } ?>
                </ul>
            </div>


            <?php
        }else {
            echo '<div class="media-gallery-rows library-view text-center"><h4>No media found</h4></div>';
        }
        ?>

    </div>


</div>


<style>
    .library-view {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        gap: 10px;
    }
    .library-view > .media-row {
        width: 18%;
        text-align: center;
        padding-bottom: 0;
        padding-top: 0;
        position: relative;
        margin: 1%;
        background-color: #eeeeee21;
        border-radius: 0px;
        min-width: 150px;
        border: 1px solid #e9e9e9;
    }
    .library-view .media-caption {
        margin-top: -5px;
        display: flex;
        padding: 0 5px 5px;
    }
    .library-view .media-caption h3 {
        font-size: 12px;
        display: flex;
        color: #000;
        font-weight: 500;
    }
    .library-view .media-caption .left {
        width: 100%;
        text-align: left;
        padding-left: 8px;
    }
    .library-view .media-caption .left span {
        display: inline-block;
        vertical-align: top;
        width: auto;
    }
    .library-view .media-caption .left span.name {
        width: auto;
        display: inline-block;
        display: -webkit-inline-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
        font-size: 12px;
        padding-left: 0px;
        padding-right: 5px;
        line-break: anywhere;
        color: #000;
    }
    .library-view .media-caption .right {
        width: 60px;
        text-align: right;
        position: relative;
    }
    .library-view .media-caption .media-size {
        padding-left: 0;
        font-size: 10px;
        margin-bottom: 5px;
        text-align: left;
        margin-top: 4px;
        color: #565656;
    }
    .library-view > .media-row input {
        width: 50%;
    }
    .library-view > .media-row .media-image {
        height: auto;
        overflow: hidden;
        padding: 10px;
        align-items: center;
        justify-content: center;
    }
    .library-view > .media-row .media-image img {
        display: inline-block;
        max-width: 100%;
        height: 150px;
        border-radius: 0px;
        width: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
        background-color: #eee;
        color: transparent;
    }
    .library-view > .media-row .media-image a {
        height: 95%;
        display: block;
    }
    .library-view > .media-row .menu-toggle {
        cursor: pointer;
        display: inline-block;
    }


    .library-view > .media-row [id*=context_menu_].active .context {
        display: block;
        font-size: 14px;
        line-height: 15px;
    }

    .media-browser-popup .media-gallery-rows > .media-row {
        width: 19%;
    }

    img.fancybox-image {
        border-radius: 5px;
    }



    .head-search {
        float: right;
        position: relative;
    }
    .head-search button {
        background-color: transparent;
        border: none;
        font-size: 14px;
        position: absolute;
        right: 0;
        top: 0;
        padding: 12px 25px 4px 7px;
        cursor: pointer;
    }
    .head-search input {
        width: 280px;
        font-family: inherit;
        padding: 6px 14px 8px;
        border-radius: 4px;
    }
    .library-view .media-toolbar {
        position: absolute;
        right: 0;
        left: 5px;
        background-color: rgba(255,255,255,0.80);
        width: calc(100% - 30px);
        top: 8px;
        padding: 3px 0 0;
        opacity: 0;
        transition: all 0.3s ease;
        backdrop-filter: blur(3px);
        margin: 10px;
        border-radius: 0px;
    }
    .library-view .media-toolbar .context li img {
        width: 32px;
        height: auto;
        padding: 4px;
        border-radius: 0;
        fill: #fff;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        border-radius: 0px;
    }
    .btn.back {
        padding: 12px 33px 11px;
    }
    .library-view .media-toolbar .context li img:hover {
        border: 1px solid #d8262f;
    }

    .library-view > .media-row .media-toolbar .context {
        border-radius: 0px;
    }

    .library-view > .media-row .context li {
        display: inline-block;
        vertical-align: middle;
        padding: 0 1px;
        cursor: pointer;
        font-size: 18px;
    }

    .library-view > .media-row:hover .media-toolbar {
        opacity: 1;
        top: 4px;
    }
    .library-view > .media-row .media-image img {
        transition: all 0.3s ease;
    }

    .library-view > .media-row:hover .media-image img {
        box-shadow: 0 4px 12px 0px rgba(0,0,0,0.08);
    }

    .library-view > .media-row:hover .media-remove {
        display: block;
    }
    input, select {
        font-family: inherit;
        padding: 6px 18px;
        border: 1px solid #f7f7f7;
        font-size: 16px;
    }
    .media-name {
        font-size: 12px;
        padding: 0 10px;
        overflow: hidden;
        display: flex;
    }
    .media-name .name {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 10px;
        margin: 2px 0;
        font-weight: normal;
    }
    .media-name span {
        font-weight: 600;
        display: inline-block;
        min-width: 34px;
    }

    ul.pagination {
        margin: auto;
        margin-top: 35px;
        margin-bottom: 0;
    }

    .pagination-holder {
        overflow-x: auto;
        width: 100%;
        padding-bottom: 10px;
    }

    ul.pagination li {
        display: inline-block;
        vertical-align: top;
    }
    ul.pagination li a {
        padding: 5px;
        border: 1px solid #eee;
        margin: 2px;
        display: block;
        width: 40px;
        text-align: center;
        font-size: 14px;
        border-radius: 5px;
    }

    ul.pagination li a:not(.active):hover {
        color: #d8262f;
    }
    ul.pagination li a.active {
        background-color: #d8262f;
        color: #fff;
    }
    .swal2-content {
        padding: 0;
    }
    .swal2-header {
        padding: 0 1.8em 0 8px;
    }
</style>