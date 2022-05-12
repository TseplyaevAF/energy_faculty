<?php


namespace App\Service\StudentProgress;

class Service
{
    public static function getStudentsProgress($lesson) {
        $arrayStudentsProgress = [];
        $arrayMonths = [];
        foreach ($lesson->group->students as $student) {
            foreach ($lesson->student_progress as $studentProgress) {
                $month = \App\Service\Task\Service::getRusMonthName(intval($studentProgress->created_at->format('m')))
                    . ' ' . $studentProgress->created_at->format('Y');

                if ($studentProgress->student_id == $student->id) {
                    $arrayStudentsProgress[$student->user->fullName()][$month] = [
                        'number_of_debts' => $studentProgress->number_of_debts,
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
        ];
    }
}
