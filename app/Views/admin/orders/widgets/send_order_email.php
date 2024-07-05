<form method="post">
    <div>
        <select id="order_email_action" name="order_email_action" class="select2" style="width: 100%;" data-search="false">
            <option selected>Choose an email..</option>
            <option value="invoice">Invoice / Order details</option>
            <option value="cancelled">Cancelled order</option>
            <option value="processing">Processing order</option>
            <!-- <option value="ready_to_ship">Ready to ship</option> -->
            <option value="completed">Completed order</option>
        </select>
    </div>

    <div class="pt-10">
        <input type="hidden" name="send_order_email" value="1">
        <button type="button" onclick="send_order_email_form(this.form)" class="btn btn-secondary" style="display: block; width: 100%">Save order & Send</button>
    </div>
</form>

<script>
    const send_order_email_form = (form)=> {
        $(form).addClass('processing');
        $.post(location.href,$('#save_order_information_form').serialize(), function() {
            form.submit();
        });
    }
</script>