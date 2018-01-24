<?php
/**
 * Copyright (c) 2017 TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see http://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */

namespace TechDivision\IndexSuspender\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\Collection;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\CollectionFactory;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Console
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class ResumeAllCommand extends Command
{
    const COMMAND_NAME = 'suspend:index:resume-all';

    /** @var  Collection */
    private $indexSuspenderCollection;

    /**
     * @param CollectionFactory $collectionFactory
     * @param null|string $name
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        $name = null
    ) {
        parent::__construct($name);
        $this->indexSuspenderCollection = $collectionFactory->create();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Resume all currently suspended indexes.');
        $this->configureHelp();
        parent::configure();
    }

    /**
     * Add help information to command
     */
    private function configureHelp()
    {
        $help = sprintf(
            '<info>php bin/magento %s</info>',
            $this->getName()
        );
        $help .= PHP_EOL . PHP_EOL;

        $this->setHelp($help);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->indexSuspenderCollection->deleteAll();
            $output->writeln('<info>All index suspenders deleted.</info>');
        } catch (\Exception $exception) {
            $output->writeln(sprintf('<error>Could not delete index suspenders (%s)</error>', $exception->getMessage()));
        }
    }
}
