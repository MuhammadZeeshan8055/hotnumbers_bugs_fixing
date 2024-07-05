<div class="container">
    <div class="datatable">
        <div>
            <div class="flex_space">
            <div> <h3 class="label">Wholesale account requests</h3></div>
            <a class="btn back" href="#" onclick="history.back()" class="add_banner"><i
                        class="icon-left-small"></i> Back</a>
            </div>
            <div class="row header no-gutters">
                <div>
                    <div class="d-inline-block text-left" style="vertical-align: middle;text-align: left">
                        <label>&nbsp;</label><br>
                        <a class="add_btn d-inline-block" href="<?php echo base_url(ADMIN . '/users/add') ?>">+ Add User</a>
                    </div>
                    &nbsp;
                </div>

            </div>

        </div>

        <div class="flex_space"></div>


        <div class="books_listing datatable">
            <?php
            $remote_url = '?get_data=1';
            if(!empty($_GET['role'])) {
                $remote_url .= "&role=".$_GET['role'];
            }
            ?>
            <table id="books_table" data-remote="<?php echo $remote_url ?>" class="ui data_table celled table responsive nowrap unstackable" style="width:100%">
                <thead>
                <tr>
                    <th>Wholesale account</th>
                    <th>Estimated coffee usage (KG)</th>
                    <th>Full name</th>
                    <th>Company name</th>
                    <th>Telephone</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /*foreach ($user_rows as $user_row) {

                    $user_roles = $userModel->get_user_roles($user_row->user_id);

                    ?>
                    <tr class="table_row_<?php echo $user_row->user_id ?>">
                        <td><img width="50" height="50"
                                 src="<?php echo base_url('assets/images/site-images/users/' . $user_row->img) ?>"></td>
                        <td> <?php echo $user_row->username ?></td>
                        <td><?php echo $user_row->display_name ?></td>
                        <td><?php echo !empty($user_roles) ? implode(', ',$user_roles) : '' ?></td>
                        <td><?php echo $user_row->email ?></td>
                        <td><a class="edit_row"
                               href="<?php echo base_url(ADMIN . '/users/add') ?>/<?php echo $user_row->user_id ?>"><i
                                        class="icon-edit-alt"></i> </a>
                            <a class="del_row edit_row"
                               onclick="del_item('<?php echo base_url(ADMIN . '/users/delete/') ?>/<?php echo $user_row->user_id ?>')"
                               href="javascript:void(0)"></i><i class="icon-trash"></i></a>
                        </td>

                    </tr>
                <?php
                }*/
                ?>


                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>
