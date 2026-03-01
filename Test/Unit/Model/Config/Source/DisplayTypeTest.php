<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */

declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use Sjmb\EnvironmentInfo\Model\Config\Source\DisplayType;

/**
 * Unit tests for DisplayType source model.
 */
class DisplayTypeTest extends TestCase
{
    /**
     * @var DisplayType
     */
    private DisplayType $subject;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->subject = new DisplayType();
    }

    /**
     * Test that the TOPBAR constant equals 'topbar'.
     *
     * @return void
     */
    public function testTopbarConstantValue(): void
    {
        $this->assertSame('topbar', DisplayType::TOPBAR);
    }

    /**
     * Test that the CORNER constant equals 'corner'.
     *
     * @return void
     */
    public function testCornerConstantValue(): void
    {
        $this->assertSame('corner', DisplayType::CORNER);
    }

    /**
     * Test that the ALERT constant equals 'alert'.
     *
     * @return void
     */
    public function testAlertConstantValue(): void
    {
        $this->assertSame('alert', DisplayType::ALERT);
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
     * Test that toOptionArray() contains the Topbar option.
     *
     * @return void
     */
    public function testToOptionArrayContainsTopbarOption(): void
    {
        $result = $this->subject->toOptionArray();

        $this->assertSame(DisplayType::TOPBAR, $result[0]['value']);
    }

    /**
     * Test that toOptionArray() contains the Corner option.
     *
     * @return void
     */
    public function testToOptionArrayContainsCornerOption(): void
    {
        $result = $this->subject->toOptionArray();

        $this->assertSame(DisplayType::CORNER, $result[1]['value']);
    }

    /**
     * Test that toOptionArray() contains the Alert option.
     *
     * @return void
     */
    public function testToOptionArrayContainsAlertOption(): void
    {
        $result = $this->subject->toOptionArray();

        $this->assertSame(DisplayType::ALERT, $result[2]['value']);
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
