<?php

namespace App\Jobs;

use App\Mail\Student\ExamsheetMail;
use App\Models\ExamSheet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ExamSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $exam_sheets = ExamSheet::where('before', '<', date('Y-m-d'))
            ->get();
        foreach ($exam_sheets as $exam_sheet) {
            Mail::to($exam_sheet->student->user->email)->send(new ExamsheetMail($exam_sheet));
            $exam_sheet->delete();
        }
    }
}
