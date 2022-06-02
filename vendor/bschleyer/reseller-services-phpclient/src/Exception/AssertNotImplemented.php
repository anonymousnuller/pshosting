<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * PDX-License-Identifier: BSD-2-Clause
 */

namespace ResellerServices\Exception;

use Exception;

class AssertNotImplemented extends Exception
{
    public function __construct()
    {
        parent::__construct('This function does not exist in the Sandbox! It will be added Soon!', 0, null);
    }
}