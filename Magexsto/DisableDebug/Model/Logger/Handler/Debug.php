<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magexsto\DisableDebug\Model\Logger\Handler;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Debug
 */
class Debug extends \Magento\Framework\Logger\Handler\Debug
{

    /**
     * {@inheritdoc}
     */
    public function write(array $record)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $storeCode = $storeManager->getStore()->getCode();
        if ($scopeConfig->getValue('dev/debug/turn_off', ScopeInterface::SCOPE_STORE, $storeCode) ) {
            return;
        }
        parent::write($record);
    }
}