<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sjmb\EnvironmentInfo\Helper\Config;
use Sjmb\EnvironmentInfo\Model\Config\Source\Mode;

/**
 * Unit tests for Config helper.
 */
class ConfigTest extends TestCase
{
    private const string XML_PATH_MODE = 'sjmb_env_info/general/mode';
    private const string XML_PATH_DISPLAY_TYPE = 'sjmb_env_info/general/display_type';
    private const string XML_PATH_ALLOWED_DOMAINS = 'sjmb_env_info/general/allowed_domains';

    /**
     * @var Config
     */
    private Config $subject;

    /**
     * @var ScopeConfigInterface|MockObject
     */
    private ScopeConfigInterface|MockObject $scopeConfigMock;

    /**
     * @var SerializerInterface|MockObject
     */
    private SerializerInterface|MockObject $serializerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);

        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock->method('getScopeConfig')->willReturn($this->scopeConfigMock);

        $this->subject = new Config($contextMock, $this->serializerMock);
    }

    /**
     * Test that getMode() returns the configured integer value from scopeConfig.
     *
     * @return void
     */
    public function testGetModeReturnsIntFromScopeConfig(): void
    {
        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(self::XML_PATH_MODE, ScopeInterface::SCOPE_STORE, null)
            ->willReturn('1');

        $result = $this->subject->getMode();

        $this->assertSame(1, $result);
    }

    /**
     * Test that getMode() casts the scopeConfig string value to int.
     *
     * @return void
     */
    public function testGetModeCastsValueToInt(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn('2');

        $result = $this->subject->getMode();

        $this->assertIsInt($result);
        $this->assertSame(2, $result);
    }

    /**
     * Test that isDisabled() returns true when mode is 0.
     *
     * @return void
     */
    public function testIsDisabledReturnsTrueWhenModeIsZero(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn((string) Mode::DISABLED);

        $this->assertTrue($this->subject->isDisabled());
    }

    /**
     * Test that isDisabled() returns false when mode is not 0.
     *
     * @return void
     */
    public function testIsDisabledReturnsFalseWhenModeIsNotZero(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn((string) Mode::ENABLED);

        $this->assertFalse($this->subject->isDisabled());
    }

    /**
     * Test that isEnabled() returns true when mode is 1.
     *
     * @return void
     */
    public function testIsEnabledReturnsTrueWhenModeIsOne(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn((string) Mode::ENABLED);

        $this->assertTrue($this->subject->isEnabled());
    }

    /**
     * Test that isEnabled() returns false when mode is not 1.
     *
     * @return void
     */
    public function testIsEnabledReturnsFalseWhenModeIsNotOne(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn((string) Mode::DISABLED);

        $this->assertFalse($this->subject->isEnabled());
    }

    /**
     * Test that isSelectedDomain() returns true when mode is 2.
     *
     * @return void
     */
    public function testIsSelectedDomainReturnsTrueWhenModeIsTwo(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn((string) Mode::SELECTED_DOMAIN);

        $this->assertTrue($this->subject->isSelectedDomain());
    }

    /**
     * Test that isSelectedDomain() returns false when mode is not 2.
     *
     * @return void
     */
    public function testIsSelectedDomainReturnsFalseWhenModeIsNotTwo(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn((string) Mode::DISABLED);

        $this->assertFalse($this->subject->isSelectedDomain());
    }

    /**
     * Test that getDisplayType() returns the string value from scopeConfig.
     *
     * @return void
     */
    public function testGetDisplayTypeReturnsStringFromScopeConfig(): void
    {
        $expectedType = 'topbar';

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(self::XML_PATH_DISPLAY_TYPE, ScopeInterface::SCOPE_STORE, null)
            ->willReturn($expectedType);

        $result = $this->subject->getDisplayType();

        $this->assertSame($expectedType, $result);
    }

    /**
     * Test that getDisplayType() casts null to empty string.
     *
     * @return void
     */
    public function testGetDisplayTypeCastsNullToEmptyString(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn(null);

        $result = $this->subject->getDisplayType();

        $this->assertSame('', $result);
    }

    /**
     * Test that getAllowedDomains() returns empty array when scopeConfig value is null.
     *
     * @return void
     */
    public function testGetAllowedDomainsReturnsEmptyArrayWhenValueIsNull(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->with(self::XML_PATH_ALLOWED_DOMAINS, ScopeInterface::SCOPE_STORE, null)
            ->willReturn(null);

        $this->serializerMock->expects($this->never())->method('unserialize');

        $result = $this->subject->getAllowedDomains();

        $this->assertSame([], $result);
    }

    /**
     * Test that getAllowedDomains() returns empty array when scopeConfig value is empty string.
     *
     * @return void
     */
    public function testGetAllowedDomainsReturnsEmptyArrayWhenValueIsEmptyString(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn('');

        $this->serializerMock->expects($this->never())->method('unserialize');

        $result = $this->subject->getAllowedDomains();

        $this->assertSame([], $result);
    }

    /**
     * Test that getAllowedDomains() returns empty array when scopeConfig value is not a string.
     *
     * @return void
     */
    public function testGetAllowedDomainsReturnsEmptyArrayWhenValueIsNotString(): void
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->willReturn(['already', 'an', 'array']);

        $this->serializerMock->expects($this->never())->method('unserialize');

        $result = $this->subject->getAllowedDomains();

        $this->assertSame([], $result);
    }

    /**
     * Test that getAllowedDomains() unserializes and returns the array when value is a valid JSON string.
     *
     * @return void
     */
    public function testGetAllowedDomainsReturnsUnserializedArrayWhenValueIsValidString(): void
    {
        $serializedValue = '[{"domain":"example.com"},{"domain":"other.com"}]';
        $expectedDomains = [
            ['domain' => 'example.com'],
            ['domain' => 'other.com'],
        ];

        $this->scopeConfigMock
            ->method('getValue')
            ->with(self::XML_PATH_ALLOWED_DOMAINS, ScopeInterface::SCOPE_STORE, null)
            ->willReturn($serializedValue);

        $this->serializerMock
            ->expects($this->once())
            ->method('unserialize')
            ->with($serializedValue)
            ->willReturn($expectedDomains);

        $result = $this->subject->getAllowedDomains();

        $this->assertSame($expectedDomains, $result);
    }

    /**
     * Test that getAllowedDomains() passes custom scope type and code to scopeConfig.
     *
     * @return void
     */
    public function testGetAllowedDomainsPassesScopeTypeAndCodeToScopeConfig(): void
    {
        $scopeType = ScopeInterface::SCOPE_WEBSITE;
        $scopeCode = 'base';
        $serializedValue = '[{"domain":"example.com"}]';

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(self::XML_PATH_ALLOWED_DOMAINS, $scopeType, $scopeCode)
            ->willReturn($serializedValue);

        $this->serializerMock
            ->method('unserialize')
            ->willReturn([['domain' => 'example.com']]);

        $this->subject->getAllowedDomains($scopeType, $scopeCode);
    }
}
