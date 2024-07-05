<div class="container">

    <div class="datatable gigs_events">
        <div class="row header no-gutters" style="text-align:center;color:green">
            <a class="add_btn" href="<?php echo base_url(ADMIN . '/gigs_events/add') ?>">+ Add Gigs Events</a>
        </div>
        <div class="books_listing">
            <table id="books_table" class="ui celled data_table table responsive nowrap unstackable" style="width:100%">
                <thead>
                <tr>
                    <th>Preview image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($gigs_events as $gigs_event) { ?>

                    <tr class="table_row_<?php echo $gigs_event->gig_id ?>">
                        <td><img width="50" height="50"
                                 src="<?php echo base_url('assets/images/site-images/gigs/' . $gigs_event->img) ?>"></td>
                        <td> <?php echo $gigs_event->title ?></td>
                        <td><?php echo  limit($gigs_event->description,30);?></td>
                        <td> <?php echo $gigs_event->location ?></td>
                        <td> <?php echo $gigs_event->price ?></td>
                        <td>
                            <a class="edit_row"
                               href="<?php echo base_url(ADMIN . '/gigs_events/add/') ?>/<?php echo $gigs_event->gig_id ?>"></i><i
                                        class="icon-edit-alt"></i></a>
                            <a class="del_row edit_row"
                               onclick="del_item('<?php echo base_url(ADMIN . '/gigs_events/delete/') ?>/<?php echo $gigs_event->gig_id ?>')"
                               href="javascript:void(0)"></i><i class="icon-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>


                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>
