<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Source model for Display Mode configuration field.
 */
class Mode implements OptionSourceInterface
{
    public const int DISABLED = 0;
    public const int ENABLED = 1;
    public const int SELECTED_DOMAIN = 2;

    /**
     * Returns array of options for the Display Mode select field.
     *
     * @return array<int, array<string, int|string>>
     */
    #[\Override]
    public function toOptionArray(): array
    {
        return [
            ['value' => self::DISABLED, 'label' => __('Disabled')],
            ['value' => self::ENABLED, 'label' => __('Enabled')],
            ['value' => self::SELECTED_DOMAIN, 'label' => __('Selected Domains')],
        ];
    }
}
