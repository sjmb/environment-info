<?php
/**
 * @copyright Copyright (c) 2026 SJMB (https://sjmb.pl)
 */
declare(strict_types=1);

namespace Sjmb\EnvironmentInfo\Test\Unit\Model\Config\Backend;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Sjmb\EnvironmentInfo\Model\Config\Backend\Domains;

/**
 * Unit tests for Domains backend config model.
 */
class DomainsTest extends TestCase
{
    /**
     * @var Domains
     */
    private Domains $subject;

    /**
     * @var SerializerInterface|MockObject
     */
    private SerializerInterface|MockObject $serializerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->serializerMock = $this->createMock(SerializerInterface::class);

        $eventManagerMock = $this->createMock(ManagerInterface::class);

        $reflection = new ReflectionClass(Domains::class);
        $this->subject = $reflection->newInstanceWithoutConstructor();

        $serializerProperty = $reflection->getProperty('serializer');
        $serializerProperty->setValue($this->subject, $this->serializerMock);

        $eventManagerProperty = $reflection->getParentClass()
            ->getParentClass()
            ->getProperty('_eventManager');
        $eventManagerProperty->setValue($this->subject, $eventManagerMock);
    }

    /**
     * Test that beforeSave() serializes the array value and removes the __empty key.
     *
     * @return void
     */
    public function testBeforeSaveSerializesArrayAndRemovesEmptyKey(): void
    {
        $inputArray = [
            '__empty' => '',
            '0' => ['domain' => 'example.com'],
            '1' => ['domain' => 'other.com'],
        ];
        $expectedArray = [
            '0' => ['domain' => 'example.com'],
            '1' => ['domain' => 'other.com'],
        ];
        $serializedValue = '[{"domain":"example.com"},{"domain":"other.com"}]';

        $this->subject->setData('value', $inputArray);

        $this->serializerMock
            ->expects($this->once())
            ->method('serialize')
            ->with($expectedArray)
            ->willReturn($serializedValue);

        $this->subject->beforeSave();

        $this->assertSame($serializedValue, $this->subject->getData('value'));
    }

    /**
     * Test that beforeSave() serializes an array value that has no __empty key.
     *
     * @return void
     */
    public function testBeforeSaveSerializesArrayWithoutEmptyKey(): void
    {
        $inputArray = [['domain' => 'example.com']];
        $serializedValue = '[{"domain":"example.com"}]';

        $this->subject->setData('value', $inputArray);

        $this->serializerMock
            ->expects($this->once())
            ->method('serialize')
            ->with($inputArray)
            ->willReturn($serializedValue);

        $this->subject->beforeSave();

        $this->assertSame($serializedValue, $this->subject->getData('value'));
    }

    /**
     * Test that beforeSave() does not call serializer when value is a string.
     *
     * @return void
     */
    public function testBeforeSaveDoesNotSerializeWhenValueIsString(): void
    {
        $this->subject->setData('value', 'already-a-string');

        $this->serializerMock->expects($this->never())->method('serialize');

        $this->subject->beforeSave();

        $this->assertSame('already-a-string', $this->subject->getData('value'));
    }

    /**
     * Test that beforeSave() does not call serializer when value is null.
     *
     * @return void
     */
    public function testBeforeSaveDoesNotSerializeWhenValueIsNull(): void
    {
        $this->subject->setData('value', null);

        $this->serializerMock->expects($this->never())->method('serialize');

        $this->subject->beforeSave();
    }

    /**
     * Test that _afterLoad() unserializes the stored string value.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testAfterLoadUnserializesStringValue(): void
    {
        $serializedValue = '[{"domain":"example.com"}]';
        $expectedArray = [['domain' => 'example.com']];

        $this->subject->setData('value', $serializedValue);

        $this->serializerMock
            ->expects($this->once())
            ->method('unserialize')
            ->with($serializedValue)
            ->willReturn($expectedArray);

        $method = new ReflectionMethod(Domains::class, '_afterLoad');
        $method->invoke($this->subject);

        $this->assertSame($expectedArray, $this->subject->getData('value'));
    }

    /**
     * Test that _afterLoad() does not call serializer when value is empty string.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testAfterLoadDoesNotUnserializeWhenValueIsEmptyString(): void
    {
        $this->subject->setData('value', '');

        $this->serializerMock->expects($this->never())->method('unserialize');

        $method = new ReflectionMethod(Domains::class, '_afterLoad');
        $method->invoke($this->subject);

        $this->assertSame('', $this->subject->getData('value'));
    }

    /**
     * Test that _afterLoad() does not call serializer when value is null.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testAfterLoadDoesNotUnserializeWhenValueIsNull(): void
    {
        $this->subject->setData('value', null);

        $this->serializerMock->expects($this->never())->method('unserialize');

        $method = new ReflectionMethod(Domains::class, '_afterLoad');
        $method->invoke($this->subject);

        $this->assertNull($this->subject->getData('value'));
    }

    /**
     * Test that _afterLoad() does not call serializer when value is not a string.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testAfterLoadDoesNotUnserializeWhenValueIsArray(): void
    {
        $alreadyArray = [['domain' => 'example.com']];

        $this->subject->setData('value', $alreadyArray);

        $this->serializerMock->expects($this->never())->method('unserialize');

        $method = new ReflectionMethod(Domains::class, '_afterLoad');
        $method->invoke($this->subject);

        $this->assertSame($alreadyArray, $this->subject->getData('value'));
    }
}
