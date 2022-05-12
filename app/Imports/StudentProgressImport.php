<?php

namespace App\Imports;

use App\Models\Lesson;
use App\Models\StudentProgress;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentProgressImport implements ToCollection, WithHeadingRow
{
    protected $lesson_id;
    protected $month;

    public function __construct($lesson_id, $month)
    {
        $this->lesson_id = $lesson_id;
        $this->month = $month;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try {
            $lesson = Lesson::find($this->lesson_id);
            if (!isset($lesson)) {
                throw new \Exception('Нагрузка не найдена. Загрузить успеваемость невозможно.', -1);
            }
            DB::beginTransaction();
            // создаем новые записи успеваемости
            foreach ($collection as $item) {
                if (isset($item['fio']) &&
                    isset($item['kol_vo_propuskov']) &&
                    isset($item['ocenka_za_mesyac'])) {
                    $user = User::whereRaw(
                        "concat(surname, ' ', name, ' ', patronymic) ILIKE '%" . $item['fio'] . "%' "
                    )->first();
                    if (!isset($user) && !isset($user->student)) {
                        throw new \Exception('Студент '. $item['fio'] .' не найден. Загрузить успеваемость невозможно.', -2);
                    }
                    if ($user->student->group_id == $lesson->group->id) {
                        $studentProgress = StudentProgress::firstOrCreate([
                            'lesson_id' => $lesson->id,
                            'student_id' => $user->student->id,
                            'month' => $this->month,
                        ]);
                        $studentProgress->update([
                            'mark' => $item['ocenka_za_mesyac'],
                            'number_of_passes' => $item['kol_vo_propuskov'],
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception->getCode() == -1 || $exception->getCode() == -2) {
                throw new \Exception($exception->getMessage());
            } else {
                throw new \Exception('Некорректные данные в файле. Загрузить невозможно.');
            }
        }
    }
}
