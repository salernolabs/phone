<?php

namespace SalernoLabs\Tests\Phone;

/**
 * Tests for PhoneNumber
 * @package SalernoLabs\Tests\Phone
 */
class PhoneNumberTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return \string[][]
     */
    public function providerPhoneNumberFormatting()
    {
        return [
            'local number' => [
                '2134563',
                '213-4563',
                null,
            ],
            'full number' => [
                '333-231-5541',
                '1 (333) 231-5541',
                null,
            ],
            'extension' => [
                '333-231-5541 x3415',
                '1 (333) 231-5541 x3415',
                null,
            ],
            'country code' => [
                '14-333-231-5541 x3415',
                '14 (333) 231-5541 x3415',
                null,
            ],
            'number too short' => [
                '1234',
                null,
                \InvalidArgumentException::class,
            ],
        ];
    }

    /**
     * Test phone number formatting
     * @param string $input The input string
     * @param string|null $expectedOutput The expected output
     * @param string|null $expectedException Any expected exception
     * @dataProvider providerPhoneNumberFormatting
     */
    public function testPhoneNumberFormatting(string $input, ?string $expectedOutput, ?string $expectedException)
    {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $phone = new \SalernoLabs\Phone\PhoneNumber($input);

        if ($expectedOutput) {
            $this->assertSame($expectedOutput, (string)$phone);
        }
    }

    /**
     * Test serialization
     */
    public function testSerialization()
    {
        $phone = new \SalernoLabs\Phone\PhoneNumber('444   23133_33');
        $x = serialize($phone);

        /**
         * @var \SalernoLabs\Phone\PhoneNumber $y
         */
        $y = unserialize($x);

        $this->assertInstanceOf(\SalernoLabs\Phone\PhoneNumber::class, $y);
        $this->assertSame('1', $y->getCountryCode());
        $this->assertSame('444', $y->getAreaCode());
        $this->assertSame('2313333', $y->getNumber());
    }
}
