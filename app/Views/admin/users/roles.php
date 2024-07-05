<div class="container">
    <div class="datatable featured featured_page ">
        <div class="flex_space">
            <h3 class="label">
                User Roles
            </h3>
            <a class="btn back" href="<?php echo base_url(ADMIN . "/users") ?>" class="add_banner"><i
                        class="icon-left-small"></i> Back</a>
        </div>

        <div class="mt-60">
            <form method="post" style="max-width: 1100px">
                <fieldset>
                    <div class="header">
                        <div class="field-title">Manage user roles</div>
                    </div>

                    <div id="user_roles">
                        <?php foreach($roles as $i=>$role) { ?>
                            <div class="row mt-20 role-row">
                                <div class="col-md-12">
                                    <div class="d-inline-block">
                                        <div class="input_field">
                                            <label>ID</label>
                                            <input name="role[id][<?php echo $i ?>]" readonly value="<?php echo $role->role ?>" title="Double click to edit" placeholder="ID">
                                        </div>
                                    </div>

                                    <div class="d-inline-block">
                                        <div class="input_field">
                                            <label>Value</label>
                                            <input name="role[value][<?php echo $i ?>]" value="<?php echo $role->name ?>" placeholder="Value">
                                        </div>
                                    </div>
                                    <div class="d-inline-block">
                                        <div class="input_field checkbox">
                                            <input type="checkbox" name="role[status][<?php echo $i ?>]" <?php echo $role->status ? 'checked':'' ?> value="1">
                                            <label>Active</label>
                                        </div>
                                    </div>
                                    &nbsp;
                                    &nbsp;
                                    <?php /*<div class="d-inline-block">
                                        <div class="input_field" style=" padding: 7px 0;">
                                            <a href="?delete=<?php echo $role->role ?>" class="btn btn-primary btn-sm" onclick="return confirm('Are you sure to delete this role?')">Delete</a>
                                        </div>
                                    </div>*/ ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="row mt-60">
                        <div class="col-md-12">
                            <input type="hidden" name="save_roles" value="1">
                            <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                            &nbsp;
                            &nbsp;
                            <?php /*<button type="button" onclick="add_role_input()" class="btn btn-secondary btn-sm">Add Role</button>*/ ?>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>

        <script>
            const add_role_input = ()=> {
                const html = `<div class="row mt-20 role-row">
                            <div class="col-md-12">
                                <div class="d-inline-block">
                                    <div class="input_field">
                                        <label>ID</label>
                                        <input name="role[id][]" value="" title="Double click to edit" placeholder="ID">
                                    </div>
                                </div>

                                <div class="d-inline-block">
                                    <div class="input_field">
                                      <label>Value</label>
                                        <input name="role[value][]" value="" placeholder="Role value">
                                    </div>
                                </div>
                                <div class="d-inline-block">
                                    <div class="input_field checkbox">
                                        <input type="checkbox" name="role[status][]" value="1" checked>
                                        <label>Active</label>
                                    </div>
                                </div>
                                &nbsp;
                                &nbsp;
                                <div class="d-inline-block">
                                    <div class="input_field" style=" padding: 7px 0;">
                                        <a href="#" class="btn btn-primary btn-sm" onclick="remove_role(this)">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                $('#user_roles').append(html);
            }

            const remove_role = (input)=> {
                $(input).closest('.role-row').remove();
            }
        </script>



    </div>
</div>