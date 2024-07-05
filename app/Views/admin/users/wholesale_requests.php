<div class="container">

    <div class="admin_title_row">
        <div class="d-inline-block ">
            <?php admin_page_title('Wholesale accounts') ?>
        </div>
        &nbsp;
        <div class="d-inline-block">
            <a class="add_btn d-inline-block" href="<?php echo base_url(ADMIN . '/users/add?role=wholesale_customer') ?>">+ Add User</a>
        </div>

    </div>

    <div class="datatable">

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
                    <th width="200">Full name</th>
                    <th width="200">Company name</th>
                    <th width="200">Telephone</th>
                    <th>Email</th>
                    <th>Message <i class="lni lni-question-circle" title="Click to view full message"></i></th>
                    <th data-sortable="false">Notes</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th data-sortable="false" width="180">Actions</th>
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

            <script>
                let showMessage = (element)=> {
                    const text = element.innerText;
                    const title = element.dataset['title'];
                    Swal.fire({
                        title: title,
                        html: '<div style="max-width: 450px;text-align: left"><p>'+text+'</p></div>',
                        showCloseButton: true,
                        showConfirmButton: false,
                        showClass: {
                            popup: 'animated windowIn'
                        },
                        hideClass: {
                            popup: 'animated windowOut'
                        }
                    });
                }

                let showNotes = (element)=> {
                    const uid = element.dataset.req_id;
                    const name = element.dataset.req_name;
                    const url = site_url+"/admin/ajax/wholesale-customer-notes/"+uid;

                    Swal.showLoading()

                    fetch(url).then(res=>res.json()).then(res=>{
                        if(res.success) {
                            let add_note_html = `<div style="width: 750px">
                                    <form method="post" action="${site_url}/admin/wholesale-account-note-add">
                                    <div class="chat-list">
                                        ${res.data}
                                    </div>
                                    </form>
                                    <div class="add-note-box">
                                    <form method="post" action="${site_url}/admin/wholesale-account-note-add">
                                    <div class="input-group">
                                        <label class="text-left">Add Note</label>
                                        <div>
                                            <textarea placeholder="" rows="5" class="form-control" name="note_text" required=""></textarea>
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <input type="hidden" name="add_order_note" value="${uid}">
                                        <input type="hidden" name="author_name" value="${name}">
                                        <button class="btn btn-primary">Add Note</button>
                                    </div>
                                    </form>
                                    </div>
                            </div>`;

                            Swal.fire({
                                title: "Wholesale customer notes",
                                html:  add_note_html,
                                showCloseButton: true,
                                showConfirmButton: false,
                                showClass: {
                                    popup: 'animated windowIn'
                                },
                                hideClass: {
                                    popup: 'animated windowOut'
                                }
                            });
                        }
                    })

                }
            </script>
        </div>
    </div>
</div>
