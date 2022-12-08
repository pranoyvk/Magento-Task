<?php

declare(strict_types = 1);

namespace CustomCommand\ImportCustomerData\Console;

interface ImportCustomerDataInterface
{
    /**
     * Import data from Customer table
     *
     * @param string $path
     */
    public function importData(string $path);
}
