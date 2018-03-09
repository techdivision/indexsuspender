<?php
/**
 * Copyright (c) 2018 TechDivision GmbH
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
 * @copyright  Copyright (c) 2018 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class ListCommand extends Command
{
    const COMMAND_NAME = 'suspend:index:list';

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
        $this->setDescription('List current index suspender ids');
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
        $suspenderIds = $this->indexSuspenderCollection->getAllIds();

        if (count($this->indexSuspenderCollection)) {
            $info = sprintf(
                '<comment>Delta indexes are currently suspended by ids: %s</comment>',
                implode(', ', $suspenderIds)
            );
        } else {
            $info = '<info>Delta indexes are currently not suspended.</info>';
        }

        $output->writeln($info);
    }
}
