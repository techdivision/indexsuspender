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

use Magento\Framework\App\ObjectManagerFactory;
use Magento\Indexer\Console\Command\IndexerReindexCommand as CoreIndexerReindexCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TechDivision\IndexSuspender\Model\DeltaIndexSuspender;
use TechDivision\IndexSuspender\Model\DeltaIndexSuspenderFactory;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Console
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class IndexerReindexCommand extends CoreIndexerReindexCommand
{
    /** @var  DeltaIndexSuspender */
    private $deltaIndexSuspender;

    /**
     * @param ObjectManagerFactory $objectManagerFactory
     * @param DeltaIndexSuspenderFactory $deltaIndexSuspenderFactory
     */
    public function __construct(
        ObjectManagerFactory $objectManagerFactory,
        DeltaIndexSuspenderFactory $deltaIndexSuspenderFactory
    ) {
        parent::__construct($objectManagerFactory);
        $this->deltaIndexSuspender = $deltaIndexSuspenderFactory->create();
    }

    /**
     * @return DeltaIndexSuspender
     */
    public function getDeltaIndexSuspender()
    {
        return $this->deltaIndexSuspender;
    }

    /**
     * Suspend all delta indexers during magento indexer:reindex command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->deltaIndexSuspender->suspend();
        $returnCode = parent::execute($input, $output);
        $this->deltaIndexSuspender->resume();

        return $returnCode;
    }
}
