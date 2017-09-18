<?php

namespace Magexsto\DisableDebug\Helper;

use Magento\Setup\Exception;

/**
 * Class CheckActivation
 *
 * @package Magexsto\DisableDebug\Helper
 */
class CheckActivation extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $_reader;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;

    /**
     * CheckActivation constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Module\Dir\Reader $reader
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(\Magento\Framework\App\Helper\Context $context,
                                \Magento\Framework\Module\Dir\Reader $reader,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Framework\HTTP\Client\Curl $curl)
    {
        $this->_reader = $reader;
        $this->_storeManager = $storeManager;
        $this->_curl = $curl;
        parent::__construct($context);
    }

    /**
     * Check the activation key for the extension
     *
     * @throws Exception
     * @return bool
     */
    public function checkActivationKey()
    {
        if (!file_exists($this->_reader->getModuleDir('', 'Magexsto_DisableDebug') . DIRECTORY_SEPARATOR . 'key.txt')) {
            throw new Exception("\r Install the activation key, please.");
        }
        $checkKeyResponse = $this->_sendCheckKeyRequest(
            file_get_contents($this->_reader->getModuleDir('', 'Magexsto_DisableDebug') . DIRECTORY_SEPARATOR . 'key.txt')
        );
        if($checkKeyResponse->success !== true) {
            if(isset($checkKeyResponse->error_message)) {
                throw new Exception("\r" . $checkKeyResponse->error_message);
            } else {
                throw new Exception("\rYou have some problem with activation key");
            }
        }
       return true;
    }

    /**
     * Send request to check the token (key)
     *
     * @param $key
     * @return \stdClass mixed
     */
    protected function _sendCheckKeyRequest($key)
    {
        $headers = array(
            "Content-Type: application/json;charset=\"utf-8\"",
            "Accept: application/json",
        );
        $data = array(
            'url' => $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB),
            'ip' => '192.168.0.103',
            'token' => $key,
        );
        $this->_curl->setHeaders($headers);
        $this->_curl->post($this->getCheckKeyUrl(),$data);
        return json_decode($this->_curl->getBody());
    }


    /**
     * Get url for checking the key (token)
     *
     * @return string
     */
    protected function getCheckKeyUrl()
    {
        return "http://magexsto.com/api/checkToken/";
    }
}