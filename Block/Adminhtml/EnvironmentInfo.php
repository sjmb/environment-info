<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Block\Adminhtml;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Sjmb\EnvironmentInfo\Helper\Config;
use Sjmb\EnvironmentInfo\Model\Config\Source\DisplayType;

/**
 * Block responsible for rendering the environment info indicator in the admin.
 */
class EnvironmentInfo extends Template
{
    /** @var array<string, string> Maps display type values to their template identifiers */
    private const array TEMPLATE_MAP = [
        DisplayType::TOPBAR => 'Sjmb_EnvironmentInfo::topbar.phtml',
        DisplayType::CORNER => 'Sjmb_EnvironmentInfo::corner.phtml',
        DisplayType::ALERT  => 'Sjmb_EnvironmentInfo::alert.phtml',
    ];

    /**
     * @param Context $context
     * @param Config $config
     * @param RequestInterface $request
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly Config $config,
        private readonly RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Returns the current HTTP hostname.
     *
     * @return string
     */
    public function getHostname(): string
    {
        return (string) $this->request->getServer('HTTP_HOST');
    }

    /**
     * Determines the template to render based on configuration before output.
     *
     * @return Template
     */
    #[\Override]
    protected function _beforeToHtml(): Template
    {
        if ($this->config->isDisabled()) {
            $this->setTemplate('');
            return parent::_beforeToHtml();
        }

        if ($this->config->isSelectedDomain() && !$this->isCurrentHostnameAllowed()) {
            $this->setTemplate('');
            return parent::_beforeToHtml();
        }

        $displayType = $this->config->getDisplayType();
        $template = self::TEMPLATE_MAP[$displayType] ?? self::TEMPLATE_MAP[DisplayType::TOPBAR];
        $this->setTemplate($template);

        return parent::_beforeToHtml();
    }

    /**
     * Checks whether the current hostname is in the configured allowed domains list.
     *
     * @return bool
     */
    private function isCurrentHostnameAllowed(): bool
    {
        $hostname = $this->getHostname();
        $allowedDomains = $this->config->getAllowedDomains();

        foreach ($allowedDomains as $row) {
            if (isset($row['domain']) && $row['domain'] === $hostname) {
                return true;
            }
        }

        return false;
    }
}
