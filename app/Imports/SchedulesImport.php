<?php

namespace App\Imports;

use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Schedule\ClassTime;
use App\Models\Schedule\Schedule;
use App\Models\User;
use App\Service\Schedule\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SchedulesImport implements ToCollection, WithHeadingRow
{
    protected $semester;
    protected $group_id;

    public function __construct($semester, $group_id)
    {
        $this->semester = $semester;
        $this->group_id = $group_id;
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection)
    {
        try {
            DB::beginTransaction();
            $group = Group::find($this->group_id);
            foreach ($group->lessons as $lesson) {
                $schedules = $lesson->schedules;
                foreach ($schedules as $schedule) {
                    $schedule->forceDelete();
                }
            }
            $day = null;
            $days = Schedule::getDays();
            $service = new Service();
            foreach ($collection->toArray() as $item) {
                if (isset($item['den'])) {
                    $day = array_search($item['den'], $days);
                }
                if (isset($item['vremya']) &&
                    isset($item['verxnyaya_nedelya'])) {
                    $service->store(self::getScheduleData($item, $item['verxnyaya_nedelya'], $day, Schedule::WEEK_UP));
                }
                if (isset($item['vremya']) &&
                    isset($item['niznyaya_nedelya'])) {
                    $service->store(self::getScheduleData($item, $item['niznyaya_nedelya'], $day, Schedule::WEEK_LOW));
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    private function getScheduleData($item, $weekData, $day, $weekType) {
        $scheduleInfo = explode('·', $weekData);
        $discipline = Discipline::where('title', 'ilike' , trim($scheduleInfo[0]))->first();
        if (!isset($discipline)) {
            throw new \Exception('Дисциплина ' .
                $scheduleInfo[0] . ' не была найдена', -1);
        }
        $user = User::whereRaw(
            "concat(surname, ' ', name, ' ', patronymic) ILIKE '%" . trim($scheduleInfo[1]) . "%' "
        )->first();
        if (!isset($user) || !isset($user->teacher)) {
            throw new \Exception('Преподаватель ' .
                $scheduleInfo[1] . ' не был найден', -2);
        }
        $lesson = Lesson::where('discipline_id', $discipline->id)
            ->where('group_id', $this->group_id)
            ->where('teacher_id', $user->teacher->id)
            ->where('semester', $this->semester)->first();
        if (!isset($lesson)) {
            throw new \Exception('Нагрузка за "' .
                Schedule::getDays()[$day] . ', ' . $item['vremya'] . ', ' .
                Schedule::getWeekTypes()[$weekType] . ' неделя" не была найдена', -3);
        }
        $classTimeInput = explode('-', $item['vremya']);
        return [
            'day' => $day,
            'week_type' => $weekType,
            'class_time_id' => ClassTime::firstOrCreate([
                'start_time' => $classTimeInput[0],
                'end_time' => $classTimeInput[1],
            ])->id,
            'class_type_id' => trim($scheduleInfo[2]),
            'classroom_id' => trim($scheduleInfo[3]),
            'lesson_id' => $lesson->id,
            'group_id' => $this->group_id,
        ];
    }
}
