<?php

namespace App\Console\Commands\Reports;

use Illuminate\Console\Command;
use App\Models\Factory\Subject;
use App\Models\Contract\Contract;

class Task2207 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:task-2207';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subjects = Subject::all();
        $output = [];
        foreach([9, 10, 11] as $grade_id) {
            $output[] = $grade_id . " класс";
            $analyzed = collect([]);
            foreach($subjects as $subject) {
                foreach($subjects as $subject2) {
                    if ($subject->id === $subject2->id || $analyzed->contains($subject2->id . '-' . $subject->id)) {
                        continue;
                    }
                    $count = Contract::query()
                        ->where('year', 2018)
                        ->where('version', 1)
                        ->where('grade_id', $grade_id)
                        ->whereHas('subjects', function ($query) use ($subject) {
                            return $query->where('subject_id', $subject->id);
                        })
                        ->whereHas('subjects', function ($query) use ($subject2) {
                            return $query->where('subject_id', $subject2->id);
                        })
                        ->count();
                    $output[] = $subject->three_letters . "–" . $subject2->three_letters . ": " . $count;
                    $analyzed->push($subject->id . '-' . $subject2->id);
                }
            }
            $output[] = "";
        }

        file_put_contents('test.txt', implode("\n", $output));
    }
}
