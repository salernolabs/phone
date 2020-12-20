# Phone Number Formatter Utility

This is just a teeny tiny tested library for parsing, formatting, and validating phone numbers.

It is mostly aimed towards US formatting as many countries and customs have their own form of formatting phone numbers.

## Installation

Use composer

    composer require salernolabs/phone
    
Instantiate a class as necessary!

## General Usage

    $phone = new \SalernoLabs\Phone\PhoneNumber('1234567890');
    echo $phone->formatNumber();
    
If all works as expected this will output `1 (123) 456-7890`

You can also just `echo (string)$phone;`

It implements `\Serializable` so it should be serializable.

## Class Documentation

The class requires a valid phone number string to be entered as a constructor parameter. It is parsed at construction time. It may throw `\InvalidArgumentException` if it is unable to properly parse the phone number.  

| Method | Inputs | Outputs |
| ----------- | -------- | -------- |
| constructor | string Phone Number (required) | - |
| getCountryCode | - | The parsed country code |
| getAreaCode | - | The parsed area code, empty string if none |
| getNumber | - | The parsed local phone number |
| getExtension | - | The parsed extension number if available |
| serialize | - | Returns the serialized representation of the number |
| unserialize | string serialized version | The unserialized version of the number |
 

## Contributing

Submit pull requests or fork it if you want. You are, after all, the Homerun Hitter of your Phone Number Formatter (sorry Chef John).
