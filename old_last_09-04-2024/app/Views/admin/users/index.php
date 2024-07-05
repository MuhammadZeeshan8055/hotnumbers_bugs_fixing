<div class="container">
    <div class="datatable">
        <div>
            <div class="admin_title_row">
                <?php admin_page_title('All Users') ?>
            </div>
            <div class="row header no-gutters">

                <div>
                    <div class="d-inline-block text-left" style="vertical-align: middle;text-align: left">
                        <label>&nbsp;</label><br>
                        <a class="add_btn d-inline-block" href="<?php echo base_url(ADMIN . '/users/add') ?>">+ Add User</a>
                        <a class="add_btn d-inline-block" href="<?php echo base_url(ADMIN . '/user-roles') ?>">User Roles</a>
                    </div>
                    &nbsp;
                    <div class="d-inline-block text-left" style="vertical-align: middle">
                        <div class="rel input_field">
                            <label>User type</label>
                            <div class="rel">
                                <select name="" class="select2" onchange="window.location='<?php echo base_url(ADMIN . '/users?role=') ?>'+this.value" value="<?php echo !empty($_GET['role']) ? $_GET['role'] : '' ?>">
                                    <option value="">All Users</option>
                                    <?php
                                    if(!empty($roles)) {
                                        foreach($roles as $role) {
                                            if($role->role !== "guest" ) {
                                                $current = !empty($_GET['role']) && $_GET['role'] == $role->role ? 'active':'';
                                                ?>
                                                <option value="<?php echo $role->role ?>" <?php echo $current ?>><?php echo ucfirst($role->name) ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
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
                    <th>ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email address</th>
                    <th>Last login</th>
                    <th data-sortable="false">Actions</th>
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
