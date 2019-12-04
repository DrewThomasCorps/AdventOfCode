<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-04
 * Time: 17:30
 */

class PasswordChecker
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function isValid(): bool
    {
        if (strlen($this->password) !== 6) {
            return false;
        }
        if ($this->containsDecreasingDigits()) {
            return false;
        }
        if (!$this->containsRepeatingDigit()) {
            return false;
        }
        return true;
    }

    private function containsDecreasingDigits(): bool
    {
        $integers = str_split($this->password);
        for ($i = 1; $i < count($integers); $i++) {
            if ($integers[$i] < $integers[$i - 1]) {
                return true;
            }
        }
        return false;
    }

    private function containsRepeatingDigit()
    {
        $integers = str_split($this->password);
        for ($i = 1; $i < count($integers); $i++) {
            if ($integers[$i] == $integers[$i-1]) {
                return true;
            }
        }
        return false;
    }

}