<div class="container">
    <div class="datatable">
        <div>
            <div class="admin_title_row">
                <div class="d-inline-block ">
                    <?php admin_page_title('User Accounts') ?>
                </div>
                &nbsp;
                <div class="d-inline-block">
                    <a class="add_btn d-inline-block" href="<?php echo base_url(ADMIN . '/users/add') ?>">+ Add User</a>
                    <a class="add_btn d-inline-block" href="<?php echo base_url(ADMIN . '/user-roles') ?>">User Roles</a>
                </div>

            </div>

        </div>



        <div class="books_listing datatable">
            <?php
            $remote_url = '?get_data=1';
            if (!empty($_GET['role'])) {
                $remote_url .= "&role=" . $_GET['role'];
            }
            if (isset($_GET['status'])) {
                $remote_url .= "&status=" . $_GET['status'];
            }
            ?>

            <form method="get">
                <?php
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $k_arr = [];
                    foreach (explode('&', $_SERVER['QUERY_STRING']) as $string) {
                        $str = explode('=', $string);
                        $k = $str[0];
                        $v = $str[1];
                        if (!in_array($k, $k_arr) && $k !== 'role' && $k !== 'status') {
                            $k_arr[] = $k;
                ?>
                            <input type="hidden" name="<?php echo $k ?>" value="<?php echo $v ?>">
                <?php
                        }
                    }
                }
                ?>
                <div class="toolbar">
                    <div class="d-inline-block text-left" style="vertical-align: middle">
                        <div class="rel input_field">
                            <label>User type</label>
                            <div class="rel">
                                <select name="role" onchange="filterForm(this.form)" class="select2" value="<?php echo !empty($_GET['role']) ? $_GET['role'] : '' ?>">
                                    <option value="">All Users</option>
                                    <?php
                                    if (!empty($roles)) {
                                        foreach ($roles as $role) {
                                            $current = !empty($_GET['role']) && $_GET['role'] == $role->role ? 'active' : '';
                                    ?>
                                            <option value="<?php echo $role->id ?>" <?php echo $current ?>><?php echo ucfirst($role->name) ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    &nbsp;
                    <div class="d-inline-block text-left" style="vertical-align: middle">
                        <div class="rel input_field">
                            <label>User status</label>
                            <div class="rel">
                                <select name="status" onchange="filterForm(this.form)" data-search="false" class="select2" value="<?php echo isset($_GET['status']) ? $_GET['status'] : '' ?>" style="width: 130px">
                                    <option value="">Any</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-inline-block text-left" style="vertical-align: middle">
                    <div class="input_field">
                        <label>Bulk selection actions</label>
                        <div class="rel">
                            <select class="select2" data-search="false" onchange="select_bulk_action(this)">
                                <option value="">--</option>
                                <option value="<?php echo admin_url() ?>generate-user-csv/">Export as CSV</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                const filterForm = (form) => {
                    const formvals = $(form).serialize();
                    window.location = '<?php echo admin_url() ?>users?' + formvals;
                }
            </script>

            <div class="table-wrapper">
                <table id="books_table" data-remote="<?php echo $remote_url ?>" class="ui data_table celled table responsive nowrap unstackable" style="width:100%">
                    <thead>
                        <tr>
                            <th data-orderable="false" data-searching="false">
                                <div class="input_field inline-checkbox"><label><input type="checkbox" class="checkall"> </label></div>
                            </th>
                            <th>ID</th>
                            <th width="90">Status</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email address</th>
                            <th>Last login</th>
                            <th data-sortable="false" width="150">Actions</th>
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
</div>

<script>
    let select_bulk_action = (ele)=> {
        let selectedOrders = $('[name="product-row[]"]:checked');
        
        if(selectedOrders.length) {
            let selectedOption = $(ele).find('option[value="'+ele.value+'"]');
            if(selectedOption.attr('data-prompt')) {
                if(!confirm(selectedOption.attr('data-prompt'))) {
                    selectedOrders.val('');
                    selectedOrders.trigger('change');
                    return;
                }
            }
            let selectedOrderIds = selectedOrders.map((idx,element)=>{
                return element.value;
            });
            const Ids = selectedOrderIds.toArray().join();
            const action = ele.value+''+Ids;
            const win = window.open(action,'_blank');
            ele.value = '';
            select2_init();
            if(selectedOption.data('refresh')) {
                win.addEventListener('unload', function() {
                    location.reload();
                });
            }
        }else {
            ele.value = '';
            select2_init();
            notification('No User selected');
        }
    }
</script>