<?php

require_once 'TimeIntervalConverter.php';

class TimeIntervalConverterTest extends PHPUnit\Framework\TestCase {
    
    public function testConvertToHoursMinutesAndSeconds() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(3723000);
        $expected = [1, 2, 3];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_zeroSeconds() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(3720000);
        $expected = [1, 2, 0];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_zeroMinutes() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(3603000);
        $expected = [1, 0, 3];

        $this->assertEquals($actual, $expected);
    }
    
    public function testConvertToHoursMinutesAndSeconds_zeroHours() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(123000);
        $expected = [0, 2, 3];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_zero() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(0);
        $expected = [0, 0, 0];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_negative() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(-1000);
        $expected = [0, 0, -1];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_thousand() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(1000);
        $expected = [0, 0, 1];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_millisecondsSmallerThanASecond() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(999);
        $expected = [0, 0, 0];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_leadingZero() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds(01000);
        $expected = [0, 0, 0];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_numericalString() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds("1000");
        $expected = [0, 0, 1];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToHoursMinutesAndSeconds_nonNumericalString() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToHoursMinutesAndSeconds("ABC");
        $expected = [0, 0, 0];

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(1, 2, 3);
        $expected = 3723000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_zeroSeconds() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(1, 2, 0);
        $expected = 3720000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_zeroMinutes() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(1, 0, 3);
        $expected = 3603000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_zeroHours() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(0, 2, 3);
        $expected = 123000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_zero() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(0, 0, 0);
        $expected = 0;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_negative() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(-1, -2, 0);
        $expected = -3720000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_secondsGreaterThanMinute() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(0, 0, 61);
        $expected = 61000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_leadingZero() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(0, 0, 01);
        $expected = 1000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_numericalString() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(0, 0, "1");
        $expected = 1000;

        $this->assertEquals($actual, $expected);
    }

    public function testConvertToMilliseconds_nonNumericalString() {
        $timeIntervalConverter = new TimeIntervalConverter();
        $actual = $timeIntervalConverter->convertToMilliseconds(0, 0, "ABC");
        $expected = 0;

        $this->assertEquals($actual, $expected);
    }
}