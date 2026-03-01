<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;
use Sjmb\EnvironmentInfo\Model\Config\Source\Mode;

class Config extends AbstractHelper
{
    private const string XML_PATH_MODE = 'sjmb_env_info/general/mode';
    private const string XML_PATH_DISPLAY_TYPE = 'sjmb_env_info/general/display_type';
    private const string XML_PATH_ALLOWED_DOMAINS = 'sjmb_env_info/general/allowed_domains';

    /**
     * @param Context $context
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct($context);
    }

    /**
     * Returns the configured display mode value.
     *
     * @param string $scopeType
     * @param mixed $scopeCode
     * @return int
     */
    public function getMode(string $scopeType = ScopeInterface::SCOPE_STORE, mixed $scopeCode = null): int
    {
        return (int) $this->scopeConfig->getValue(self::XML_PATH_MODE, $scopeType, $scopeCode);
    }

    /**
     * Returns true when the display mode is Disabled.
     *
     * @param string $scopeType
     * @param mixed $scopeCode
     * @return bool
     */
    public function isDisabled(string $scopeType = ScopeInterface::SCOPE_STORE, mixed $scopeCode = null): bool
    {
        return $this->getMode($scopeType, $scopeCode) === Mode::DISABLED;
    }

    /**
     * Returns true when the display mode is Enabled.
     *
     * @param string $scopeType
     * @param mixed $scopeCode
     * @return bool
     */
    public function isEnabled(string $scopeType = ScopeInterface::SCOPE_STORE, mixed $scopeCode = null): bool
    {
        return $this->getMode($scopeType, $scopeCode) === Mode::ENABLED;
    }

    /**
     * Returns true when the display mode is Selected Domain.
     *
     * @param string $scopeType
     * @param mixed $scopeCode
     * @return bool
     */
    public function isSelectedDomain(string $scopeType = ScopeInterface::SCOPE_STORE, mixed $scopeCode = null): bool
    {
        return $this->getMode($scopeType, $scopeCode) === Mode::SELECTED_DOMAIN;
    }

    /**
     * Returns the configured display type value.
     *
     * @param string $scopeType
     * @param mixed $scopeCode
     * @return string
     */
    public function getDisplayType(string $scopeType = ScopeInterface::SCOPE_STORE, mixed $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_DISPLAY_TYPE, $scopeType, $scopeCode);
    }

    /**
     * Returns the list of allowed domains from configuration.
     *
     * @param string $scopeType
     * @param mixed $scopeCode
     * @return array<int, array<string, string>>
     */
    public function getAllowedDomains(string $scopeType = ScopeInterface::SCOPE_STORE, mixed $scopeCode = null): array
    {
        $value = $this->scopeConfig->getValue(self::XML_PATH_ALLOWED_DOMAINS, $scopeType, $scopeCode);

        if (!$value || !is_string($value)) {
            return [];
        }

        return (array) $this->serializer->unserialize($value);
    }
}
