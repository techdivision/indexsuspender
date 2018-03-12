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

namespace TechDivision\IndexSuspender\Controller\Adminhtml\Cache;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use TechDivision\IndexSuspender\Model\SuspendManager;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\App\AbstractAction;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 */
class CleanSuspender extends AbstractAction
{
    /**
     * @var SuspendManager
     */
    private $suspendManager;

    /**
     * CleanSuspender constructor.
     *
     * @param Context $context
     * @param SuspendManager $suspendManager
     */
    public function __construct(
        Context $context,
        SuspendManager $suspendManager
    )
    {
        parent::__construct($context);
        $this->suspendManager = $suspendManager;
    }

    /**
     * Clean suspender
     *
     * @return Redirect
     */
    public function execute()
    {
        try {
            $this->suspendManager->resumeAll();
            $this->messageManager->addSuccess(__('The suspender was cleaned. Reindex will start as soon as possible.'));
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('An error occurred while clearing the suspender.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('indexer/indexer/list');
    }
}