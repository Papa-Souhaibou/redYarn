<?php


namespace App\Service;


use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class PasswordGenerator
{
    private $generator;

    public function __construct()
    {
        $this->generator = new ComputerPasswordGenerator();
        $this->generator->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false);
    }

    public function generate()
    {
        return $this->generator->generatePassword();
    }
}