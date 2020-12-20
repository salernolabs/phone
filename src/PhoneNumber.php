<?php

namespace SalernoLabs\Phone;

/**
 * This class is an attempt at formatting user input into readable/parseable US phone numbers.
 * It will also try to be able to format itself from its own output for serialization purposes.
 * @package SalernoLabs\PhoneFormat
 */
class PhoneNumber implements \Serializable
{
    /** @var string  */
    private const DEFAULT_COUNTRY_CODE = '1';

    /**
     * @var string
     */
    private $areaCode;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $extension;

    /**
     * PhoneFormat constructor.
     * @param string $phoneNumber The phone number input
     */
    public function __construct(string $phoneNumber)
    {
        // First pull off any extension
        $extensionPosition = strpos($phoneNumber, 'x');
        if ($extensionPosition !== false) {
            $this->extension = substr($phoneNumber, $extensionPosition + 1);
            $phoneNumber = substr($phoneNumber, 0, $extensionPosition);
        }

        $length = strlen($phoneNumber);
        $numbers = [];
        // First grab all the numbers and remove any other input cruft
        // Add them in backwards
        for ($i = $length - 1; $i >= 0; --$i) {
            if (is_numeric($phoneNumber[$i])) {
                $numbers[] = $phoneNumber[$i];
            }
        }

        $numberCount = count($numbers);
        if ($numberCount < 7) {
            throw new \InvalidArgumentException('The phone number appears to be too short');
        }

        // Go through pushing the number where it needs to go
        $actualPhoneNumber = '';
        $areaCode = '';
        $countryCode = '';
        foreach ($numbers as $i => $number) {
            if ($i < 7) {
                $actualPhoneNumber = $number . $actualPhoneNumber;;
            } elseif ($i < 10) {
                $areaCode = $number . $areaCode;
            } else {
                $countryCode = $number . $countryCode;
            }
        }

        // Store the values
        $this->areaCode = $areaCode;
        $this->number = $actualPhoneNumber;
        if ($countryCode) {
            $this->countryCode = $countryCode;
        } else {
            $this->countryCode = self::DEFAULT_COUNTRY_CODE;
        }
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getAreaCode(): string
    {
        return $this->areaCode;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Format the number for display
     * @return string
     */
    public function formatNumber(): string
    {
        $number = sprintf(
            '%s-%s',
            substr($this->number, 0, 3),
            substr($this->number, 3)
        );

        if ($this->hasAreaCode()) {
            // If we have an area code we can do a long form phone number
            $number = sprintf(
                '%s (%s) %s',
                $this->getCountryCode(),
                $this->getAreaCode(),
                $number
            );
        }

        if ($this->hasExtension()) {
            $number = sprintf(
                '%s x%s',
                $number,
                $this->getExtension()
            );
        }

        // Otherwise fall back to local format
        return $number;
    }

    /**
     * String type conversion
     * @return string
     */
    public function __toString(): string
    {
        return $this->formatNumber();
    }

    /**
     * Returns true if an area code is specified
     * @return bool
     */
    public function hasAreaCode(): bool
    {
        return !empty($this->areaCode);
    }

    /**
     * Returns true if a number has an extension set
     * @return bool
     */
    public function hasExtension(): bool
    {
        return !empty($this->extension);
    }

    /**
     * Allow this to serialize
     * @return string
     */
    public function serialize(): string
    {
        return $this->formatNumber();
    }

    /**
     * Unserialize
     * @param string $serialized The serialized representation of this class
     */
    public function unserialize($serialized): void
    {
        $this->__construct($serialized);
    }
}
