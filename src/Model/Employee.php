<?php

namespace BelgacemTlili\Vacation\Model;


class Employee
{
    /** @var string */
    private $name;
    /** @var \DateTime */
    private $dateOfBirth;
    /** @var int */
    private $age;
    /** @var Contract */
    private $contract;

    /**
     * Employee constructor.
     *
     * @param string $name
     * @param \DateTime $dateOfBirth
     * @param Contract $contract
     */
    public function __construct($name, \DateTime $dateOfBirth, Contract $contract)
    {
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->setContract($contract);
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Calculate age based on the given year
     * @param int $asOfYear
     * @return int
     */
    public function getAge($asOfYear)
    {
        $birthYear = $this->dateOfBirth->format('Y');
        return $asOfYear - $birthYear;
    }

    /**
     * @param \DateTime $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param Contract $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
        if (null === $contract->getEmployee()) {
            $contract->setEmployee($this);
        }
    }

}