<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Source model for Display Type configuration field.
 */
class DisplayType implements OptionSourceInterface
{
    public const string TOPBAR = 'topbar';
    public const string CORNER = 'corner';
    public const string ALERT = 'alert';

    /**
     * Returns array of options for the Display Type select field.
     *
     * @return array<int, array<string, string>>
     */
    #[\Override]
    public function toOptionArray(): array
    {
        return [
            ['value' => self::TOPBAR, 'label' => __('Topbar')],
            ['value' => self::CORNER, 'label' => __('Corner')],
            ['value' => self::ALERT, 'label' => __('Alert')],
        ];
    }
}
