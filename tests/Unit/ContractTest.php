<?php

namespace BelgacemTlili\Test\Unit;

use BelgacemTlili\Vacation\Model\Employee;
use PHPUnit\Framework\TestCase;
use BelgacemTlili\Vacation\Model\Contract;
use Assert\InvalidArgumentException;


class ContractTest extends TestCase
{
    public function testCreationOfNewContractSuccess()
    {
        $contract = new Contract(new \DateTime('2019-05-15'));
        $this->assertEquals(Contract::MINIMUM_VACATION_DAYS, $contract->getBaseVacationDays());
    }

    public function testCreationOfNewContractWithInvalidBaseVacationDays()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of vacations days is Invalid');
        $contract = new Contract(new \DateTime(), 'notANumber');
    }

    public function testCreationOfNewContractWithInvalidStartDay()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Contract starting day must be 1 or 15');
        $contract = new Contract(new \DateTime(2019 - 05 - 07), 26);
    }

    public function testCreationOfNewContractWithLessThanMinimumVacationDays()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of vacations days must be at least 26');
        $contract = new Contract(new \DateTime(), 25);
    }


    /**
     * @dataProvider employeeAndContractDataProvider
     */
    public function testGetEligibleVacationDays($employeeName, $dateOfBirth, $contractStartDate, $currentYear, $contractVacationDays, $expectedEligibleVacationDays)
    {
        $contract = new Contract($contractStartDate, $contractVacationDays);

        $employee = new Employee($employeeName, $dateOfBirth, $contract);

        $eligibleVacationDays = $contract->getEligibleVacationDays($currentYear);

        $this->assertEquals($expectedEligibleVacationDays, $eligibleVacationDays);
    }


    public function employeeAndContractDataProvider()
    {
        return [
            'Test employee started 01.01.2001' => [
                'Hans Müller', new \DateTime('1950-12-30'), new \DateTime('2001-01-01'), 2019, null, 29
            ],
            'Test employee started 15.01.2001' => [
                'Angelika Fringe', new \DateTime('1966-06-09'), new \DateTime('2001-01-15'), 2019, null, 29
            ],
            'Test employee started 15.05.2016' => [
                'Angelika Fringe', new \DateTime('1991-07-12'), new \DateTime('2016-05-15'), 2019, 27, 27
            ],
            'Test employee started 15.05.2018' => [
                'Angelika Fringe', new \DateTime('1970-01-26'), new \DateTime('2018-05-15'), 2019, null, 26
            ],
            'Test employee started 01.12.2017' => [
                'Sepp Meier', new \DateTime('1980-05-23'), new \DateTime('2017-12-01'), 2019, null, 26
            ],
            'Test employee started 01.01.2019' => [
                'Belga Tlili', new \DateTime('1985-01-08'), new \DateTime('2019-01-01'), 2019, null, 23.83
            ],
            'Test employee started 01.12.2019' => [
                'Belga Tlili', new \DateTime('1985-01-08'), new \DateTime('2019-12-01'), 2019, null, 0
            ]
        ];
    }

}