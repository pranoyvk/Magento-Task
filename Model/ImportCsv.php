<?php

declare(strict_types = 1);

namespace CustomCommand\ImportCustomerData\Model;

use Magento\Framework\Exception\LocalizedException;
use CustomCommand\ImportCustomerData\Console\ImportCustomerDataInterface;

class ImportCsv implements ImportCustomerDataInterface
{
    /**
     * @var Csv
     */
    protected $csv;

    /**
     * @var File
     */
    protected $driverFile;

    /**
     * @param File $driverFile
     * @param Csv $csv
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\File\Csv $csv,
    ) {
        $this->driverFile = $driverFile;
        $this->csv = $csv;
    }

    /**
     * Import data from the file
     *
     * @param string $path
     * @return array
     */
    public function importData(string $path): array
    {
        if ($this->driverFile->isExists($path)) {
            $fileData = array_slice((array)$this->csv->getData($path), 1);
            foreach ($fileData as $key => &$value) {
                $value['firstname'] = $value[0];
                unset($value[0]);
                $value['lastname'] = $value[1];
                unset($value[1]);
                $value['email'] = $value[2];
                unset($value[2]);
            }
            return $fileData;
        }
        throw new LocalizedException(__("File not Exist"));
    }
}
