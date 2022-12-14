<?php

declare(strict_types=1);

namespace RLTSquare\Unit3\Helper\Data;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path): string
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }
}
