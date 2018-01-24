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

namespace TechDivision\IndexSuspender\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Helper
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class Config extends AbstractHelper
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
    }

    /**
     * @param string $path
     * @return string
     */
    public function getConfig($path)
    {
        return trim($this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE));
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getModuleConfig($path)
    {
        return $this->getConfig('techdivision_indexsuspender/' . $path);
    }

    /**
     * @return bool
     */
    public function getGeneralEnable()
    {
        return (bool)$this->getModuleConfig('general/enable');
    }

    /**
     * Check whether module and delta indexer feature are enabled.
     *
     * @return bool
     */
    public function getDeltaIndexerEnabled()
    {
        return $this->getModuleConfig('general/enable') && $this->getModuleConfig('general/enable_delta');
    }
}
