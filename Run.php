<?php

require_once __DIR__ . '/vendor/autoload.php';

use BelgacemTlili\Vacation\Model\Contract;
use BelgacemTlili\Vacation\Model\Employee;

class Run
{

    public static function export($currentYear)
    {
        $fp = fopen('data/'.$currentYear. '_data.csv', 'w');
        foreach (self::getEmployeesData() as $employeeData) {
            $contract = new Contract($employeeData[2], $employeeData[3]);
            $employee = new Employee($employeeData[0], $employeeData[1], $contract);
            $line = [$employee->getName(), $contract->getEligibleVacationDays($currentYear)];
            fputcsv($fp, $line, ';');
        }
        fclose($fp);
    }

    public static function getEmployeesData()
    {
        return [
            [
                'Hans Müller', new \DateTime('1950-12-30'), new \DateTime('2001-01-01'), 26
            ],
            [
                'Angelika Fringe', new \DateTime('1966-06-09'), new \DateTime('2001-01-15'), 26
            ],
            [
                'Angelika Fringe', new \DateTime('1991-07-12'), new \DateTime('2016-05-15'), 27
            ],
            [
                'Angelika Fringe', new \DateTime('1970-01-26'), new \DateTime('2018-05-15'), 26
            ],
            [
                'Sepp Meier', new \DateTime('1980-05-23'), new \DateTime('2017-12-01'), 26
            ],
            [
                'Belga Tlili', new \DateTime('1985-01-08'), new \DateTime('2019-01-01'), 26
            ],
            [
                'Servus Djo', new \DateTime('1985-01-08'), new \DateTime('2019-12-01'), 26
            ]
        ];
    }
}