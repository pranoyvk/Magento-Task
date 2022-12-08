<?php
 
namespace CustomCommand\ImportCustomerData\Test\Unit\Console;
 
use Magento\Framework\Filesystem\Driver\File;
use CustomCommand\ImportCustomerData\Console\ImportJson;

class ImportJsonTest extends \PHPUnit\Framework\TestCase
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
        $this->sampleClass = new ImportJson($driverFile);
    }
    
    /**
     * @dataProvider additionProvider
     */
    public function testImportData($path, $expected)
    {
        $this->assertSame($expected, $this->sampleClass->importData($path));
    }
    
    /**
     * @dataProvider dataProv
     */
    public function testRecursiveChangeKey($arr, $set, $expected)
    {
        $this->assertSame($expected, $this->sampleClass->recursiveChangeKey($arr, $set));
    }
    public function additionProvider(): array
    {

        return [
            ["path" => "/home/z0377@ad.ziffity.com/sample.json",
             "expected" => [
                [
                    "firstname"=> "Emma",
                    "lastname"=>"Watson",
                    "email"=>"emma.watson@example.com"
                ],
                [
                    "firstname" => "Ramy",
                    "lastname"=> "Devi",
                    "email"=> "ramy.devi@example.com"
                ],
                [
                    "firstname"=> "Guna",
                    "lastname"=> "Vinayag",
                    "email"=> "guna.vinayag@example.com"
                ],
                [
                    "firstname"=>"Maari",
                    "lastname"=>"Son",
                    "email"=>"maari.son@example.com"
                ],
                [
                    "firstname"=> "Manoj",
                    "lastname"=> "Kumar",
                    "email"=> "manoj.kumar@example.com"
                ]
                ]
            ],
        ];
    }
    public function dataProv(): array
    {
        return [
            [
               'arr' => ['fname'=> 'adssa','lname'=>'ssasad','emailadress'=>'asdsa@gmail.com'],
               'set' => ['fname' => 'firstname', 'lname' => 'lastname', 'emailadress' => 'email'],
               'expected' => [
                            'firstname' => 'adssa',
                            'lastname' => 'ssasad',
                            'email' => 'asdsa@gmail.com',
                            
                ],
            ],
            [
                'arr' => [['fname'=> 'adssa','lname'=>'ssasad','emailadress'=>'asdsa@gmail.com'],
                          ['fname'=> 'adssa','lname'=>'ssasad','emailadress'=>'asdsa@gmail.com']],
                'set' => ['fname' => 'firstname', 'lname' => 'lastname', 'emailadress' => 'email'],
                'expected' => [
                    [
                        'firstname' => 'adssa',
                        'lastname' => 'ssasad',
                        'email' => 'asdsa@gmail.com',
                    ],
                    [
                        'firstname' => 'adssa',
                        'lastname' => 'ssasad',
                        'email' => 'asdsa@gmail.com',
                    ]
                 ],
             ]
        ];
    }
}
