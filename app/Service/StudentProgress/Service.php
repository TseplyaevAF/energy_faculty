<?php


namespace App\Service\StudentProgress;

class Service
{
    /**
     * @var \string[][]
     */
    private static $monthsTypes = [
        1 => [9 => 'сентябрь',10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь', 1 => 'январь'],
        2 => [2 => 'февраль', 3 => 'март', 4 => 'апрель', 5 => 'май', 6 => 'июнь'],
        3 => [9 => 'сентябрь',10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь', 1 => 'январь'],
        4 => [2 => 'февраль', 3 => 'март', 4 => 'апрель', 5 => 'май', 6 => 'июнь'],
    ];

    public static function getStudentsProgress($lesson) {
        $arrayStudentsProgress = [];
        $arrayMonths = [];
        foreach ($lesson->group->students as $student) {
            foreach ($lesson->student_progress()->orderBy('month', 'asc')->get() as $studentProgress) {
                $month = \App\Service\Task\Service::getRusMonthName(intval($studentProgress->month));

                if ($studentProgress->student_id == $student->id) {
                    $arrayStudentsProgress[$student->user->fullName()][$month] = [
                        'number_of_passes' => $studentProgress->number_of_passes,
                        'mark' => $studentProgress->mark,
                    ];
                    $arrayMonths[] = $month;
                }
            }
        }
        $arrayMonths = array_unique($arrayMonths);
        return [
            'arrayStudentsProgress' => $arrayStudentsProgress,
            'monthsCount' => count($arrayMonths),
            'arrayMonths' => $arrayMonths,
            'months' => self::$monthsTypes[$lesson->semester],
        ];
    }
}
