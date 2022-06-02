<?php

// set mollie api key
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey(env('MOLLIE_API_KEY'));