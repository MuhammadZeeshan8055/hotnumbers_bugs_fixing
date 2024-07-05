<form method="post">
                        <?php if(!empty($order_notes)) {
                        ?>
                        <div class="chat-list">
                            <?php foreach($order_notes AS $note) {
                                //echo $note['comment_date'];
                                //$date = date(env('datetime_full_format',strtotime($note['comment_date'])));
                                $date = _datetime_full($note['comment_date']);
                                $meta = $note['meta_data'];
                                $is_customer_note = 0;
                                foreach($meta as $v) {
                                    if($v['meta_key'] === "is_customer_note" && $v['meta_value'] == 1) {
                                        $is_customer_note = 1;
                                        break;
                                    }
                                }
                                ?>
                                <div class="chat-box">
                                    <div class="chat-meta">
                                        <div class="date"><?php echo $date ?></div> by <?php echo $note['comment_author'] ?>
                                    </div>
                                    <div class="chat-text">
                                        <?php echo urldecode($note['comment_content']) ?>
                                    </div>
                                    <div class="chat-actions">
                                        <small style="padding: 8px 0;float: left;opacity: 0.5;">
                                            <?php echo $is_customer_note ? 'Customer note':'Private note' ?>
                                        </small>
                                        <button name="order_note_delete" class="btn-primary btn btn-sm" value="<?php echo $note['comment_ID'] ?>" onclick="return confirm('Are you sure to delete this order note?')">Delete</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                 </div>
                <?php } ?>
                <div class="add-note-box">
                    <!--                                 -->
                    <form method="post">
                        <div class="input-group">
                            <label>Add Note</label>
                            <div>
                                <textarea placeholder="Add a note for your reference, or add a customer note (the user will be notified)." rows="5" class="form-control" name="note_text" required></textarea>
                            </div>
                        </div>
                        <div class="d-inline-block">
                            <div class="input_field mb-12 checkbox">
                                <input type="radio" class="checkbox" name="note_recipient" checked value="private">
                                <label class="inline">Private Note</label>
                            </div>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div class="d-inline-block">
                            <div class="input_field mb-12 checkbox">
                                <input type="radio" class="checkbox" name="note_recipient" value="customer">
                                <label class="inline">Note to customer</label>
                            </div>
                        </div>

                        <div class="input-group">
                            <input type="hidden" name="add_order_note" value="1">
                            <button class="btn btn-primary">Add Note</button>
                        </div>
                    </form>
                </div>