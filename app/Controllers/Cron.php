<?php

namespace App\Controllers;

class Cron extends BaseController
{
    public function subscriptions() {
        $subscriptionModel = model('SubscriptionModel');

        $current_day_renewals = $subscriptionModel->current_day_renewals();
        $expired_subscriptions = $subscriptionModel->get_expired("AND o.status='active'");

        if(!empty($current_day_renewals)) {
            foreach($current_day_renewals as $subscription) {
                $subscriptionModel->renew($subscription->order_id);
            }
        }

        if(!empty($expired_subscriptions)) {
            foreach($expired_subscriptions as $subscription) {
                $subscriptionModel->expire($subscription->order_id);
            }
        }

    }
}