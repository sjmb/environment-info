<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Test\Unit\Block\Adminhtml;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Sjmb\EnvironmentInfo\Block\Adminhtml\EnvironmentInfo;
use Sjmb\EnvironmentInfo\Helper\Config;
use Sjmb\EnvironmentInfo\Model\Config\Source\DisplayType;

/**
 * Unit tests for EnvironmentInfo block template resolution logic.
 */
class EnvironmentInfoTest extends TestCase
{
    /**
     * @var Config|MockObject
     */
    private Config|MockObject $configMock;

    /**
     * @var RequestInterface|MockObject
     */
    private RequestInterface|MockObject $requestMock;

    /**
     * @var Context|MockObject
     */
    private Context|MockObject $contextMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->configMock = $this->createMock(Config::class);

        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->addMethods(['getServer'])
            ->getMockForAbstractClass();

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Creates a new EnvironmentInfo block instance using the shared mocks.
     *
     * @return EnvironmentInfo
     */
    private function createBlock(): EnvironmentInfo
    {
        return new EnvironmentInfo(
            $this->contextMock,
            $this->configMock,
            $this->requestMock
        );
    }

    /**
     * Helper method to call the protected _beforeToHtml() method using reflection.
     *
     * @param EnvironmentInfo $block
     * @return void
     * @throws \ReflectionException
     */
    private function callBeforeToHtml(EnvironmentInfo $block): void
    {
        $method = new ReflectionMethod(EnvironmentInfo::class, '_beforeToHtml');
        $method->invoke($block);
    }

    /**
     * Test that getHostname() returns the HTTP_HOST server variable value.
     *
     * @return void
     */
    public function testGetHostnameReturnsHttpHostFromRequest(): void
    {
        $expectedHostname = 'example.com';

        $this->requestMock
            ->expects($this->once())
            ->method('getServer')
            ->with('HTTP_HOST')
            ->willReturn($expectedHostname);

        $block = $this->createBlock();

        $this->assertSame($expectedHostname, $block->getHostname());
    }

    /**
     * Test that getHostname() returns an empty string when HTTP_HOST is null (e.g. CLI context).
     *
     * @return void
     */
    public function testGetHostnameReturnsEmptyStringWhenHttpHostIsNull(): void
    {
        $this->requestMock
            ->expects($this->once())
            ->method('getServer')
            ->with('HTTP_HOST')
            ->willReturn(null);

        $block = $this->createBlock();

        $this->assertSame('', $block->getHostname());
    }

    /**
     * Test that _beforeToHtml() sets an empty template when the module is disabled.
     *
     * @return void
     */
    public function testBeforeToHtmlSetsEmptyTemplateWhenDisabled(): void
    {
        $this->configMock
            ->expects($this->once())
            ->method('isDisabled')
            ->willReturn(true);

        $this->configMock
            ->expects($this->never())
            ->method('isSelectedDomain');

        $block = $this->createBlock();
        $this->callBeforeToHtml($block);

        $this->assertSame('', $block->getTemplate());
    }

    /**
     * Test that _beforeToHtml() sets the topbar template when enabled and display type is topbar.
     *
     * @return void
     */
    public function testBeforeToHtmlSetsTopbarTemplateWhenDisplayTypeIsTopbar(): void
    {
        $this->configMock->method('isDisabled')->willReturn(false);
        $this->configMock->method('isSelectedDomain')->willReturn(false);
        $this->configMock->method('getDisplayType')->willReturn(DisplayType::TOPBAR);

        $block = $this->createBlock();
        $this->callBeforeToHtml($block);

        $this->assertSame('Sjmb_EnvironmentInfo::topbar.phtml', $block->getTemplate());
    }

    /**
     * Test that _beforeToHtml() sets the corner template when enabled and display type is corner.
     *
     * @return void
     */
    public function testBeforeToHtmlSetsCornerTemplateWhenDisplayTypeIsCorner(): void
    {
        $this->configMock->method('isDisabled')->willReturn(false);
        $this->configMock->method('isSelectedDomain')->willReturn(false);
        $this->configMock->method('getDisplayType')->willReturn(DisplayType::CORNER);

        $block = $this->createBlock();
        $this->callBeforeToHtml($block);

        $this->assertSame('Sjmb_EnvironmentInfo::corner.phtml', $block->getTemplate());
    }

    /**
     * Test that _beforeToHtml() sets the alert template when enabled and display type is alert.
     *
     * @return void
     */
    public function testBeforeToHtmlSetsAlertTemplateWhenDisplayTypeIsAlert(): void
    {
        $this->configMock->method('isDisabled')->willReturn(false);
        $this->configMock->method('isSelectedDomain')->willReturn(false);
        $this->configMock->method('getDisplayType')->willReturn(DisplayType::ALERT);

        $block = $this->createBlock();
        $this->callBeforeToHtml($block);

        $this->assertSame('Sjmb_EnvironmentInfo::alert.phtml', $block->getTemplate());
    }

    /**
     * Test that _beforeToHtml() falls back to topbar template when display type is unknown.
     *
     * @return void
     */
    public function testBeforeToHtmlFallsBackToTopbarTemplateWhenDisplayTypeIsUnknown(): void
    {
        $this->configMock->method('isDisabled')->willReturn(false);
        $this->configMock->method('isSelectedDomain')->willReturn(false);
        $this->configMock->method('getDisplayType')->willReturn('unknown_type');

        $block = $this->createBlock();
        $this->callBeforeToHtml($block);

        $this->assertSame('Sjmb_EnvironmentInfo::topbar.phtml', $block->getTemplate());
    }

    /**
     * Test that _beforeToHtml() sets empty template when mode is Selected Domain but hostname is not in allowed list.
     *
     * @return void
     */
    public function testBeforeToHtmlSetsEmptyTemplateWhenSelectedDomainAndHostnameNotAllowed(): void
    {
        $this->requestMock
            ->method('getServer')
            ->with('HTTP_HOST')
            ->willReturn('notallowed.example.com');

        $this->configMock->method('isDisabled')->willReturn(false);
        $this->configMock->method('isSelectedDomain')->willReturn(true);
        $this->configMock->method('getAllowedDomains')->willReturn([
            ['domain' => 'allowed.example.com'],
            ['domain' => 'other.example.com'],
        ]);

        $block = $this->createBlock();
        $this->callBeforeToHtml($block);

        $this->assertSame('', $block->getTemplate());
    }

    /**
     * Test that _beforeToHtml() sets the correct template when mode is Selected Domain and hostname is in allowed list.
     *
     * @return void
     */
    public function testBeforeToHtmlSetsTemplateWhenSelectedDomainAndHostnameIsAllowed(): void
    {
        $this->requestMock
            ->method('getServer')
            ->with('HTTP_HOST')
            ->willReturn('allowed.example.com');

        $this->configMock->method('isDisabled')->willReturn(false);
        $this->configMock->method('isSelectedDomain')->willReturn(true);
        $this->configMock->method('getAllowedDomains')->willReturn([
            ['domain' => 'other.example.com'],
            ['domain' => 'allowed.example.com'],
        ]);
        $this->configMock->method('getDisplayType')->willReturn(DisplayType::TOPBAR);

        $block = $this->createBlock();
        $this->callBeforeToHtml($block);

        $this->assertSame('Sjmb_EnvironmentInfo::topbar.phtml', $block->getTemplate());
    }
}
