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

use Magento\Framework\App\Filesystem\DirectoryList;
use TechDivision\IndexSuspender\Exception\LockException;

/**
 * @category   TechDivision
 * @package    Index_Suspender
 * @copyright  Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class FileLock
{
    const LOCK_FILE_PREFIX = 'lock_';
    const LOCK_DIR = 'index_suspender_locks';

    /** @var  string */
    private $lockDir;
    /** @var  string */
    private $lockFile;
    /** @var  resource */
    private $handle;
    /** @var DirectoryList */
    private $directoryList;

    /**
     * @param DirectoryList $directoryList
     */
    public function __construct(DirectoryList $directoryList)
    {
        $this->directoryList = $directoryList;
        $this->initLockDir();
    }

    private function initLockDir()
    {
        $varDir = $this->directoryList->getPath(DirectoryList::VAR_DIR);
        $this->lockDir = $varDir . DIRECTORY_SEPARATOR . static::LOCK_DIR;
    }

    /**
     * @param $fileName
     * @throws LockException
     */
    public function lock($fileName)
    {
        $this->lockFile = static::LOCK_FILE_PREFIX . $fileName;
        $lockCreated = $this->acquireFileHandle()->lockFileHandle();

        if (!$lockCreated) {
            throw new LockException(
                __('Could not acquire lock. Try to use FileLock::isLocked() to check before getting lock.')
            );
        }
    }

    /**
     * Release lock for current index.
     */
    public function release()
    {
        $this->acquireFileHandle()->releaseFileLock();
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        $isLocked = false;

        if ($this->doesLockFileExist()) {
            $isLocked = $this->acquireFileHandle()->isFileHandleLocked();
        }

        return $isLocked;
    }

    /**
     * @return $this
     * @throws LockException
     */
    private function acquireFileHandle()
    {
        if (!$this->handle) {

            $handle = @fopen($this->lockDir . DIRECTORY_SEPARATOR . $this->lockFile, 'wb');
            if (!$handle) {
                throw new LockException(__('Could not acquire handle.'));
            }

            $this->handle = $handle;
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function lockFileHandle()
    {
        $fileLocked = false;

        if ($this->handle) {
            $fileLocked = flock($this->handle, LOCK_EX | LOCK_NB);
        }
        return $fileLocked;
    }

    /**
     * @return bool
     */
    private function doesLockFileExist()
    {
        return file_exists($this->lockDir . DIRECTORY_SEPARATOR . $this->lockFile);
    }

    /**
     * @return bool
     */
    private function isFileHandleLocked()
    {
        return !$this->lockFileHandle();
    }

    private function releaseFileLock()
    {
        flock($this->handle, LOCK_UN);
    }
}
