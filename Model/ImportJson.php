<?php

declare(strict_types = 1);

namespace CustomCommand\ImportCustomerData\Model;

use Magento\Framework\Exception\LocalizedException;
use CustomCommand\ImportCustomerData\Console\ImportCustomerDataInterface;

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

            foreach ($arrayConvertion as $key => &$value) {
                $value['firstname'] = $value['fname'];
                unset($value['fname']);
                $value['lastname'] = $value['lname'];
                unset($value['lname']);
                $value['email'] = $value['emailaddress'];
                unset($value['emailaddress']);
            }
            return $arrayConvertion;
        }
        throw new LocalizedException(__("File does not exist"));
    }
}
