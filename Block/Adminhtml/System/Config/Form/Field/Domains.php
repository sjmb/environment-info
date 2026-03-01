<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Dynamic rows field for Allowed Domains configuration.
 */
class Domains extends AbstractFieldArray
{
    /**
     * Prepares the single domain column for the dynamic rows table.
     *
     * @return void
     */
    #[\Override]
    protected function _prepareToRender(): void
    {
        $this->addColumn('domain', ['label' => __('Domain'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = (string) __('Add Domain');
    }
}
