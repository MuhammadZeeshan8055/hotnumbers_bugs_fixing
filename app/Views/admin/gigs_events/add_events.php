<div class="container">
    <div class="datatable featured featured_page  gigs_events">
        <div class="flex_space">
            <h4> Gigis & Events</h4>
            <a class="btn back" href="#" onclick="history.back()" class="add_banner"><i
                        class="icon-left-small"></i> Back</a>
        </div>
        <form class="mt-30"
              action="<?php echo base_url(ADMIN . '/gigs_events/add') ?><?php echo $gig_row['gig_id'] ? '/' . $gig_row['gig_id'] : '' ?>"
              method="post" enctype="multipart/form-data">
            <input type="hidden" name="gig_id" value="<?php echo $gig_row['gig_id'] ?>">
            <?php if (session('msg')) :
                message_notice(session('msg'));
            endif ?>

            <div class="row " style="align-items: flex-end;">
                <div class="col-md-4">
                    <div class="input_field">
                        <div class="upload_img_banner flex-center"
                             style="background-image: url('<?php echo base_url('assets/images/site-images/gigs') . '/' . $gig_row['img'] ?>')">
                            <div class="input_file">
                                <input type="file"
                                       name="img" <?php echo(!empty($gig_row['img']) ? '' : 'required') ?> >
                                <i class="icon-up-circled2"></i>
                                <span> Upload image</span>
                                <input type="hidden" name="img"
                                       value="<?php echo $gig_row['img'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input_field">
                                        <label>Event Start Date</label>
                                        <input type="date" name="event_date"
                                               value="<?php echo $gig_row['event_date'] ?>" required>
                                    </div>
                                </div>
                                <div class=" col-lg-12">
                                    <div class="input_field">
                                        <label>Start Time </label>
                                        <input type="time" name="start_time"
                                               value="<?php echo  $gig_row['start_time']; ?>"
                                               required
                                               >
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input_field">
                                        <label>Event End Date </label>
                                        <input type="date" name="event_end"
                                               value="<?php echo $gig_row['event_end']; ?>"
                                               required>
                                    </div>
                                </div>
                                <div class=" col-lg-12">
                                    <div class="input_field">
                                        <label class="fadeInRight">End Time</label>
                                        <input type="time" name="end_time"
                                               value="<?php echo$gig_row['end_time']; ?>" required >
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class=" col-lg-12">
                            <div class="input_field url">
                                <label>YouTube Video (Optional)</label>
                                <span>Add YouTube Link e.g 'https://www.youtube.com/embed/sjCw3-YTffo'</span>
                                <input type="url" name="url"
                                       value="<?php echo  $gig_row['url']; ?>"  >
                            </div>
                        </div>


                    </div>


                </div>


                <div class="col-lg-12 mt-20  ckeditor">
                    <div class="input_field ">
                        <label>Description</label>
                        <!--                        <div id="editor1"></div>-->
                        <textarea id="editor1" name="description"
                                  rows="10"><?php echo $gig_row['description'] ?></textarea>
                    </div>
                </div>

                <div class="col-md-12 btn_bar text_right">
                    <button type="submit" class=" btn save">Save changes</button>
                </div>
        </form>


    </div>
</div>