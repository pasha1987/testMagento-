<?php
/**
 * @category    Ubertheme
 * @package     Ubertheme_UbDatamigration
 * @author      Ubertheme.com
 * @copyright   Copyright 2009-2016 Ubertheme
 */

namespace Magexsto\DisableDebug\Setup;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @var \Magexsto\DisableDebug\Helper\CheckActivation
     */
    protected $_checkActivationHelper;

    /**
     * InstallSchema constructor.
     *
     * @param \Magexsto\DisableDebug\Helper\CheckActivation $checkActivationHelper
     */
    public function __construct(\Magexsto\DisableDebug\Helper\CheckActivation $checkActivationHelper)
    {
        $this->_checkActivationHelper = $checkActivationHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context){
        $installer = $setup;
        $installer->startSetup();
        $this->_checkActivationHelper->checkActivationKey();
        $installer->endSetup();
    }
}
