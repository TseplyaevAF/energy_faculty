<?php

namespace App\Mail\Student;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamsheetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $exam_sheet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($exam_sheet)
    {
        $this->exam_sheet['studentFIO'] = $exam_sheet->student->user->surname
            .' '. $exam_sheet->student->user->name
            .' '. $exam_sheet->student->user->patronyic;
        $this->exam_sheet['discipline'] = $exam_sheet->individual->statement->lesson->discipline->title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.student.exam_sheet');
    }
}
