<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FormDto
{
    protected const COMPANY_SYMBOLS = [
        'Apple Inc.' => 'AAPL',
        'Google Inc.' => 'GOOGL',
        'Microsoft Corporation' => 'MSFT',
        'NVIDIA Corporation' => 'NVDA',
        'Advanced Micro Devices, Inc.' => 'AMD',
    ];

    #[Assert\NotBlank]
    #[Assert\Choice(callback: 'getAllowedCompanySymbols', message: 'The selected choice is invalid company symbol.')]
    protected string $companySymbol;

    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual('today')]
    protected DateTime $startDate;

    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual('today')]
    protected DateTime $endDate;

    #[Assert\NotBlank]
    #[Assert\Email]
    protected string $email;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if ($this->getStartDate() > $this->getEndDate()) {
            $context->buildViolation('StartDate must be less or equal than EndDate.')
                ->atPath('startDate')
                ->addViolation();
        }

        if ($this->getEndDate() < $this->getStartDate()) {
            $context->buildViolation('EndDate must be less or equal than StartDate.')
                ->atPath('endDate')
                ->addViolation();
        }
    }

    public function getCompanySymbol(): string
    {
        return $this->companySymbol;
    }

    public function setCompanySymbol(string $companySymbol): void
    {
        $this->companySymbol = $companySymbol;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getFormattedStartDate(): string
    {
        return $this->startDate->format('Y-m-d');
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function getFormattedEndDate(): string
    {
        return $this->endDate->format('Y-m-d');
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public static function getAllowedCompanySymbols()
    {
        return array_values(static::COMPANY_SYMBOLS);
    }

    public static function getCompanySymbols()
    {
        return static::COMPANY_SYMBOLS;
    }

    public function getCompanyNameBySymbol(string $symbol): string
    {
        foreach (static::COMPANY_SYMBOLS as $companyName => $companySymbol) {
            if ($companySymbol === $symbol) {
                return $companyName;
            }
        }

        return '';
    }
}
