<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use Sjmb\EnvironmentInfo\Model\Config\Source\Mode;

/**
 * Unit tests for Mode source model.
 */
class ModeTest extends TestCase
{
    /**
     * @var Mode
     */
    private Mode $subject;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->subject = new Mode();
    }

    /**
     * Test that the DISABLED constant equals 0.
     *
     * @return void
     */
    public function testDisabledConstantIsZero(): void
    {
        $this->assertSame(0, Mode::DISABLED);
    }

    /**
     * Test that the ENABLED constant equals 1.
     *
     * @return void
     */
    public function testEnabledConstantIsOne(): void
    {
        $this->assertSame(1, Mode::ENABLED);
    }

    /**
     * Test that the SELECTED_DOMAIN constant equals 2.
     *
     * @return void
     */
    public function testSelectedDomainConstantIsTwo(): void
    {
        $this->assertSame(2, Mode::SELECTED_DOMAIN);
    }

    /**
     * Test that toOptionArray() returns exactly three options.
     *
     * @return void
     */
    public function testToOptionArrayReturnsThreeOptions(): void
    {
        $result = $this->subject->toOptionArray();

        $this->assertCount(3, $result);
    }

    /**
     * Test that toOptionArray() contains the Disabled option with value 0.
     *
     * @return void
     */
    public function testToOptionArrayContainsDisabledOption(): void
    {
        $result = $this->subject->toOptionArray();

        $this->assertSame(Mode::DISABLED, $result[0]['value']);
    }

    /**
     * Test that toOptionArray() contains the Enabled option with value 1.
     *
     * @return void
     */
    public function testToOptionArrayContainsEnabledOption(): void
    {
        $result = $this->subject->toOptionArray();

        $this->assertSame(Mode::ENABLED, $result[1]['value']);
    }

    /**
     * Test that toOptionArray() contains the Selected Domains option with value 2.
     *
     * @return void
     */
    public function testToOptionArrayContainsSelectedDomainOption(): void
    {
        $result = $this->subject->toOptionArray();

        $this->assertSame(Mode::SELECTED_DOMAIN, $result[2]['value']);
    }

    /**
     * Test that every option in toOptionArray() has value and label keys.
     *
     * @return void
     */
    public function testToOptionArrayOptionsHaveValueAndLabelKeys(): void
    {
        $result = $this->subject->toOptionArray();

        foreach ($result as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
        }
    }
}
