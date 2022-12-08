<?php

declare(strict_types=1);

namespace CustomCommand\ImportCustomerData\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CustomCommand\ImportCustomerData\Console\ImportJson;
use CustomCommand\ImportCustomerData\Console\ImportCsv;
use Magento\Framework\Exception\LocalizedException;
use CustomCommand\ImportCustomerData\Model\SaveCustomerData;

class CustomCommand extends Command
{

    private const PROFILE = 'profile';

    /**
     * @var File
     */
    protected $driverFile;

    /**
     * @var ImportCsv
     */
    protected $importCsv;

    /**
     * @var ImportJson
     */
    protected $importJson;

    /**
     * @var SaveCustomerData
     */
    protected $saveData;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @param File             $driverFile
     * @param LoggerInterface  $logger
     * @param ImportCsv        $importCsv
     * @param ImportJson       $importJson
     * @param SaveCustomerData $saveData
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Psr\Log\LoggerInterface $logger,
        ImportCsv $importCsv,
        ImportJson $importJson,
        SaveCustomerData $saveData
    ) {
        $this->driverFile = $driverFile;
        $this->logger = $logger;
        $this->importCsv = $importCsv;
        $this->importJson = $importJson;
        $this->saveData = $saveData;
        parent::__construct();
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::PROFILE, // the option name
                '-s', // the <shortcut></shortcut>
                InputOption::VALUE_REQUIRED, // the option mode
                'Say the file extention' // the description
            ),
        ];
        $this->setName('customer:import');
        $this->setDescription('Set customer in our store');
        $this->setDefinition($options);
        $this->addArgument('sourcePath', InputArgument::REQUIRED, 'Give source path');
        parent::configure();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        try {
            $path = (string)$input->getArgument('sourcePath');
            $extension = $input->getOption('profile');
            if (!isset($extension)) {
                throw new LocalizedException(__("--profile is required"));
            }
            $arr = explode('.', $path);
            $realExtension = end($arr);
            if ($extension != $realExtension) {
                throw new LocalizedException(__("File is Undefined or Not Exist"));
            }
            switch ($extension) {
                case 'json':
                    $data = $this->importJson->importData($path);
                    $output->writeln('<info>SUCCESS</info>');
                    break;
                case 'csv':
                    $data = $this->importCsv->importData($path);
                    $output->writeln('<info>SUCCESS</info>');
                    break;
                default:
                    throw new LocalizedException(__("Undefined Method"));
            }
            $this->saveData->save($data);
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            $this->logger->critical($e->getMessage());
        }
    }
}
