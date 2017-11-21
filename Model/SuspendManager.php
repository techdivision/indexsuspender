<?php
/**
 * Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see http://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */

namespace TechDivision\IndexSuspender\Model;

use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\Collection;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\CollectionFactory;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Model
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class SuspendManager
{
    /** @var  Collection */
    private $deltaIndexSuspenderCollection;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->deltaIndexSuspenderCollection = $collectionFactory->create();
    }

    /**
     * @return bool
     */
    public function isDeltaIndexerSuspended()
    {
        $collection = $this->deltaIndexSuspenderCollection->load();

        return (bool)count($collection);
    }

    /**
     * Clears all suspenders from all processes.
     */
    public function resumeAll()
    {
        $this->deltaIndexSuspenderCollection->deleteAll();
    }
}
