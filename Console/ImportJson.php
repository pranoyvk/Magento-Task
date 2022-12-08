<?php

declare(strict_types = 1);

namespace CustomCommand\ImportCustomerData\Console;

use Magento\Framework\Exception\LocalizedException;

class ImportJson implements ImportCustomerDataInterface
{
    
    /**
     * @var File
     */
    protected $driverFile;

    /**
     * @param File $driverFile
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile
    ) {
        $this->driverFile = $driverFile;
    }

    /**
     * Import data from the file
     *
     * @param string $path
     * @return array
     */
    public function importData(string $path) :array
    {
        if ($this->driverFile->isExists($path)) {
            $fileDataStr = $this->driverFile->fileOpen($path, 'r');
            $arrayConvertion = json_decode($this->driverFile->fileRead($fileDataStr, 2000000), true);
            return (array) $this->recursiveChangeKey(
                $arrayConvertion,
                ['fname' => 'firstname', 'lname' => 'lastname', 'emailaddress' => 'email']
            );
        }
        throw new LocalizedException(__("File does not exist"));
    }

    /**
     * Match the key to data base table
     *
     * @param array $arr
     * @param array $set
     */
    public function recursiveChangeKey($arr, $set)
    {
        if (is_array($arr) && is_array($set)) {
            $newArr = [];
            foreach ($arr as $k => $v) {
                $key = array_key_exists($k, $set) ? $set[$k] : $k;
                $newArr[$key] = is_array($v) ? $this->recursiveChangeKey($v, $set) : $v;
            }
            return $newArr;
        }
        return $arr;
    }
}
