<?php
 
namespace CustomCommand\ImportCustomerData\Test\Unit\Console;

use Magento\Framework\Filesystem\Driver\File;
use CustomCommand\ImportCustomerData\Console\ImportCsv;
use Magento\Framework\File\Csv;
 
class ImportCsvTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var View
     */
    protected $sampleClass;
 
    /**
     * @var string
     */
    protected $expectedMessage;
 
    public function setUp() :void
    {
        $driverFile = new File();
        $csv = new Csv($driverFile);
        $this->sampleClass = new ImportCsv($driverFile, $csv);
    }
    
    /**
     * @dataProvider additionProvider
     */
    public function testImportData($path, $expected)
    {
        $this->assertSame($expected, $this->sampleClass->importData($path));
    }

    public function additionProvider(): array
    {

        return [
            ["path" => "/home/z0377@ad.ziffity.com/sample.csv",
             "expected" => [
                [
                    "firstname" => "Bob",
                    "lastname"=> "Smith",
                    "email" => "bob.smith@example.com"
                ],
                [
                    "firstname" => "Raj",
                    "lastname"=> "Kumar",
                    "email"=> "raj.kumar@example.com"
                ],
                [
                    "firstname"=> "Lavanya",
                    "lastname"=> "Chadra",
                    "email"=> "lavanya.chandra@example.com"
                ],
                [
                    "firstname"=> "Vignesh",
                    "lastname"=> "Muthu",
                    "email"=> "vignesh.muthu@example.com"
                ],
                [
                    "firstname"=> "John",
                    "lastname"=> "Kennedy",
                    "email"=> "john.kennedy@example.com"
                ]
                ]
            ],
        ];
    }
}
