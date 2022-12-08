<?php

declare(strict_types = 1);

namespace CustomCommand\ImportCustomerData\Console;

use Magento\Framework\Exception\LocalizedException;

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
            return $this->changeKey(
                array_slice((array)$this->csv->getData($path), 1),
                [0 => 'firstname', 1 => 'lastname', 2 => 'email']
            );
        }
        throw new LocalizedException(__("File not Exist"));
    }

    /**
     * Match the key to data base table
     *
     * @param array $arr
     * @param array $set
     */
    private function changeKey($arr, $set)
    {
        if (is_array($arr) && is_array($set)) {
            $newArr = $tempArr = [];
            foreach ($arr as $key => $value) {
                foreach ($value as $k => $v) {
                    $tempArr[$set[$k]] = $v;
                }
                $newArr[$key] = $tempArr;
            }
            return $newArr;
        }
    }
}
