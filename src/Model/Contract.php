<?php

namespace BelgacemTlili\Vacation\Model;

use Assert\Assertion;

/**
 * Class Contract contains data about employee contract
 *
 * @package BelgacemTlili\Vacation\Model
 */
class Contract
{
    /** @var int Minimum number of vacation days */
    const MINIMUM_VACATION_DAYS = 26;
    /** @var int number of seniority years where employee can get extra vacation days */
    const SENIORITY_YEARS = 5;
    /** @var int minimum age of employee in for employee seniority */
    const SENIORITY_MNIMIM_AGE = 30;
    /** @var array  possible contract starting days */
    const POSSIBLE_STARTING_DAYS = [1, 15];
    /** @var int */
    private $baseVacationDays;
    /** @var \DateTime */
    private $startDate;
    /** @var Employee */
    private $employee;
    /** @var int */
    private $currentYear;

    /**
     * Contract constructor.
     * @param \DateTime $startDate the contract start date
     *
     * @param int $baseVacationDays the contract vacations days it hs to respect the minimum number of vacation days
     */
    public function __construct(\DateTime $startDate, $baseVacationDays = null)
    {
        $this->startDate = $startDate;

        $this->baseVacationDays = $baseVacationDays ?: self::MINIMUM_VACATION_DAYS;

        $this->validate();
    }

    /**
     * @return int
     */
    public function getBaseVacationDays()
    {
        return $this->baseVacationDays;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return int
     */
    private function getStartYear()
    {
        return (int)$this->startDate->format('Y');
    }

    /**
     * @return int
     */
    private function getStartMonth()
    {
        return (int)$this->startDate->format('m');
    }

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param Employee $employee
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
        if (null === $employee->getContract()) {
            $employee->setContract($this);
        }
    }

    /**
     * Validation of Contract object
     *  1- We validate that we have at least the minimum number of vacation days
     *  2- Contract starting day must be one of the possible starting day
     */
    private function validate()
    {
        Assertion::integer($this->baseVacationDays, 'Number of vacations days is Invalid');
        Assertion::min(
            $this->baseVacationDays,
            self::MINIMUM_VACATION_DAYS,
            sprintf('Number of vacations days must be at least %d', self::MINIMUM_VACATION_DAYS)
        );
        $startingDay = (int)$this->startDate->format('d');
        Assertion::inArray(
            $startingDay,
            self::POSSIBLE_STARTING_DAYS,
            sprintf('Contract starting day must be %s', implode(' or ', self::POSSIBLE_STARTING_DAYS))
        );
    }

    /**
     * Calculate employee eligible vacation days
     *
     * @param int $asOfYear current where based on which vacation days will be calculated
     *
     * @return float
     */
    public function getEligibleVacationDays($asOfYear = null)
    {
        if (null === $asOfYear) {
            $todayDate = new \DateTime();
            $this->currentYear = $todayDate->format('Y');
        } else {
            $this->currentYear = $asOfYear;
        }

        $yearlyDays = $this->calculateYearlyVacationDays();

        $extraDays = $this->calculateSeniorityExtraVacationDays();

        return round($extraDays + $yearlyDays, 2);
    }

    /**
     * Return number of extra vacation dates based on employee age and seniority years
     *
     * @return int
     */
    private function calculateSeniorityExtraVacationDays()
    {
        $age = $this->getEmployee()->getAge($this->currentYear);
        if ($age >= self::SENIORITY_MNIMIM_AGE) {
            $seniorityYears = $this->currentYear - $this->getStartYear();
            return intdiv($seniorityYears, Contract::SENIORITY_YEARS);
        }
        return 0;
    }

    /**
     * Return number of yearly vacation days based on contract start date
     *
     * @return float
     */
    private function calculateYearlyVacationDays()
    {
        // if current  year is not same as contract start year then we have full balance of base vacation days
        if ($this->getStartYear() != $this->currentYear) {
            return $this->getBaseVacationDays();
        }
        // Calculation of pro rata
        $staringMonth = $this->getStartMonth();
        return $this->baseVacationDays * (12-$staringMonth) / 12;
    }
}