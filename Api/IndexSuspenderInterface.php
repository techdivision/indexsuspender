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

namespace TechDivision\IndexSuspender\Api;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Api
 * @copyright  Copyright (c) 2018 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
interface IndexSuspenderInterface
{
    /**
     * Holds the table's primary key name
     */
    const INDEX_SUSPENDER_ID = 'index_suspender_id';

    /**
     * Suspends all related indexing processes.
     *
     * @return void
     */
    public function suspend();

    /**
     * Resumes all suspended indexing processes.
     *
     * @return void
     */
    public function resume();
}