<?php


namespace App\Service\Dekanat;

use App\Exports\IndividualsExport;
use App\Models\ExamSheet;
use App\Models\Lesson;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Models\Teacher\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class Service
{
    private $docSigner;

    public function __construct(DocumentSigner $docSigner)
    {
        $this->docSigner = $docSigner;
    }

    public function signStatement($data)
    {
        $dekan = Teacher::where('post', 'декан')->first();
        if (!$dekan) {
            throw new \Exception('Декан факультета не найден');
        }
        try {
            $dekanCert = $this->docSigner->getCert($dekan);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        try {
            DB::beginTransaction();
            $statement = Statement::firstOrCreate([
                'control_form' => $data['control_form'],
                'lesson_id' => $data['lesson_id'],
                'start_date' => $data['start_date'],
                'finish_date' => $data['finish_date'],
                'dekan_signature' => '1'
            ]);

            // создание индивидуальных экзаменационных листов для каждого студента
            $lesson = Lesson::find($data['lesson_id']);
            $students = $lesson->group->students;
            foreach ($students as $student) {
                Individual::firstOrCreate([
                    'student_id' => $student->id,
                    'statement_id' => $statement->id
                ]);
            }

            $controlForms = Statement::getControlForms();
            $dataForSign = Statement::getStatementInfo($statement, $controlForms[$statement->control_form]);

            $signature = $this->docSigner->getSignature($dataForSign, $data['private_key'], $dekanCert);

            $signaturePath = 'signatures/statements/' . $statement->id . '/signature.dat';
            Storage::disk('public')->put($signaturePath, $signature);
            $statement->update([
                'dekan_signature' => $signaturePath
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception->getCode() == -1 || $exception->getCode() == -2) {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    public function issueSheet($data) {
        $sheet = $data['sheet'];
        $dekan = Teacher::where('post', 'декан')->first();
        if (!$dekan) {
            throw new \Exception('Декан факультета не найден');
        }
        try {
            $dekanCert = $this->docSigner->getCert($dekan);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        try {
            DB::beginTransaction();
            $dataForSign = ExamSheet::getExamSheetInfo($sheet);
            $dataForSign = json_encode($dataForSign);

            $signature = $this->docSigner->getSignature($dataForSign, $data['private_key'], $dekanCert);

            $signaturePath = 'signatures/sheets/' . $sheet->id . '/signature.dat';
            Storage::disk('public')->put($signaturePath, $signature);

            // сгенерировать, до какого числа будет дейстовать допуск
            for($i=1;$i<=3;$i++){
                $before_date =  date('Y-m-d', time()+$i*24*60*60);
            }

            $sheet->update([
                'before' => $before_date,
                'dekan_signature' => $signaturePath
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception->getCode() == -1 || $exception->getCode() == -2) {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    public function export($statement) {
        $fileName = 'Экзаменационная_ведомость_№'. $statement->id .'.xlsx';
        $path = 'statements/' . $statement->id . '/' . $fileName;
        $teacher = Teacher::where('id', $statement->lesson->teacher->id)->first();
        if (!$teacher) {
            throw new \Exception('Преподаватель не найден');
        }
        try {
            $teacherCert = $this->docSigner->getCert($teacher);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        $individuals = Individual::getArrayCompletedSheets($statement->individuals);
        foreach ($individuals as $individual) {
            if (!isset($individual['teacher_signature'])) {
                throw new \Exception('Не всем студентам проставлены оценки');
            }
            $dataForSign = implode(",",Individual::getIndividualInfo($individual));
            $dataForSign = hash('sha256', $dataForSign);
            $signature = Storage::disk('public')->get($individual['teacher_signature']);

            $res = $this->docSigner->verifyDoc($dataForSign, $signature, $teacherCert);
            if ($res === 0 || $res !== 1) {
                throw new \Exception('Подпись не совпадает');
            }
        }

        Excel::store(new IndividualsExport($statement), $path, 'private');
        $statement->update([
            'report' => $fileName
        ]);
    }
}
