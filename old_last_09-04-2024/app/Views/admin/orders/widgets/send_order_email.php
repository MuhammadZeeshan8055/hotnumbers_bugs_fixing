
<form method="post">
    <div>
        <select id="order_email_action" name="order_email_action" class="select2" style="width: 100%;" data-search="false">
            <option selected>Choose an email..</option>
            <option value="invoice">Invoice / Order details</option>
            <option value="cancelled">Cancelled order</option>
            <option value="processing">Processing order</option>
            <option value="ready_to_ship">Ready to ship</option>
            <option value="completed">Completed order</option>
        </select>
    </div>

    <div class="pt-10">
        <button type="submit" name="send_order_email" value="1" class="btn btn-secondary" style="display: block; width: 100%">Save order & Send</button>
    </div>
</form>