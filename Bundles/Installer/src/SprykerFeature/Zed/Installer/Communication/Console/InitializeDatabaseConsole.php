<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Installer\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Installer\Business\InstallerFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method InstallerFacade getFacade()
 */
class InitializeDatabaseConsole extends Console
{

    const COMMAND_NAME = 'setup:init-db';
    const DESCRIPTION = 'Fill the database with required data';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installerPlugins = $this->getFacade()->getInstaller();

        $messenger = $this->getMessenger();

        foreach ($installerPlugins as $installer) {
            $installer->setMessenger($messenger);
            $installer->install();
        }
    }

}
