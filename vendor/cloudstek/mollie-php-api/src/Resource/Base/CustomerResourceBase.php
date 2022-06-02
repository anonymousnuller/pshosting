<?php

namespace Mollie\API\Resource\Base;

use Mollie\API\Mollie;
use Mollie\API\Model\Customer;
use Mollie\API\Model\Mandate;
use Mollie\API\Model\Subscription;
use Mollie\API\Resource\Base\ResourceBase;

abstract class CustomerResourceBase extends ResourceBase
{
    /** @var string Customer ID */
    protected $customer;

    /** @var string Customer mandate ID */
    protected $mandate;

    /** @var string $subscription Customer subscription ID */
    protected $subscription;

    /**
     * Constructor
     *
     * @param Mollie $api Mollie API reference
     * @param Customer|string $customer
     */
    public function __construct(Mollie $api, $customer = null)
    {
        parent::__construct($api);

        // Store customer ID (if any)
        $this->customer = isset($customer) ? $this->getCustomerID($customer) : null;
    }

    /**
     * Get customer ID from string or customer object
     *
     * For example:
     * <code>
     * <?php
     *      $mollie = new Mollie('api_key');
     *      $customer = $mollie->customer('cst_test')->get() // call using global defined customer
     *      $customer = $mollie->customer()->get('cst_test') // call using local defined customer
     *      $customer = $mollie->customer()->get()           // No global or local customer defined
     * ?>
     * </code>
     * @param Customer|string $customer
     * @return string
     */
    protected function getCustomerID($customer = null)
    {
        return $this->getResourceID($customer, Customer::class, $this->customer);
    }

    /**
     * Get mandate ID from string or mandate object
     *
     * For example:
     * <code>
     * <?php
     *      $mollie = new Mollie('api_key');
     *      $mandate = $mollie->customer('cst_test')->mandate('mdt_test')->get() // call using global defined mandate
     *      $mandate = $mollie->customer('cst_test')->mandate()->get('cst_test') // call using local defined mandate
     *      $mandate = $mollie->customer('cst_test')->mandate()->get()           // No global or local mandate defined!
     * ?>
     * </code>
     * @param Mandate|string $mandate
     * @return string
     */
    protected function getMandateID($mandate = null)
    {
        return $this->getResourceID($mandate, Mandate::class, $this->mandate);
    }

    /**
     * Get subscription ID from string or subscription object
     *
     * For example:
     * <code>
     * <?php
     *      $mollie = new Mollie('api_key');
     *      $subscription = $mollie->customer('cst_test')->subscription('mdt_test')->get() // call using global defined subscription
     *      $subscription = $mollie->customer('cst_test')->subscription()->get('cst_test') // call using local defined subscription
     *      $subscription = $mollie->customer('cst_test')->subscription()->get()           // No global or local subscription defined
     * ?>
     * </code>
     * @param Subscription|string $subscription
     * @return string
     */
    protected function getSubscriptionID($subscription = null)
    {
        return $this->getResourceID($subscription, Subscription::class, $this->subscription);
    }
}
