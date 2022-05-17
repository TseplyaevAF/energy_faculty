<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendStudentProgressJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $selectedEmail = $this->details['selected_email'];
        $studentFIO = $this->details['student_FIO'];
        $filename = $this->details['filename'];
        Mail::send('mail.student.student-progress', compact('studentFIO'),
            function($message) use($selectedEmail, $filename)
            {
                $message->to($selectedEmail)
                    ->subject("Успеваемость студента");
                $message->attach(public_path('storage/' . $filename), [
                    'as' => $filename,
                    'mime' => 'application/xlsx',
                ]);
            });
    }
}
