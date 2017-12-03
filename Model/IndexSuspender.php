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

namespace TechDivision\IndexSuspender\Model;

use TechDivision\IndexSuspender\Api\IndexSuspenderInterface;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @copyright  Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class IndexSuspender implements IndexSuspenderInterface
{
    /** @var FileLock  */
    private $fileLock;
    /** @var  string */
    private $type;

    /**
     * @param FileLock $fileLock
     */
    public function __construct(FileLock $fileLock)
    {
        $this->fileLock = $fileLock;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param object $object
     * @return $this
     */
    public function setTypeByObjectClass(object $object)
    {
        $className = get_class($object);
        $this->type = str_replace('\\', '_', $className);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function suspend()
    {
        $this->fileLock->lock($this->type);
    }

    /**
     * @inheritdoc
     */
    public function resume()
    {
        $this->fileLock->release();
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->fileLock->isLocked();
    }
}
