<?php

namespace App\Console\Commands;

use App\Models\Record;
use http\Exception\BadMessageException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportAnzOutdoorRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:record {file_location}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import ANZ Records - Outdoor';

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
     * @return int
     */
    public function handle()
    {
        $fileLocation = sprintf(
            '%s/%s',
            base_path('storage/app/AnzRecords'),
            $this->argument('file_location')
        );

        $this->info($fileLocation);

        $csv = Reader::createFromPath($fileLocation, 'r');

        $type = $this->choice(
            'Record Type?',
            [
                'New Zealand Record',
                'National Record',
                'Open Record'
            ]
        );

        $group = $this->choice(
            'Group?',
            [
                'Outdoor',
                'Indoor',
                'Field',
                'Clout'
            ]
        );

        if (!$type || !$group) {
            $this->error('Type or Group missing');
            return 0;
        }

        $i = 1;
        $bowType = $roundType = '';

        foreach ($csv as $line) {

            // Skip first line
            if ($i++ === 1) {
                continue;
            }

            // Set the bow type
            if (!empty($line[0])) {
                $bowType = $line[0];
            }

            // Set the round type
            if (!empty($line[1])) {
                $roundType = $line[1];
            }

            // This is where the Archer Results are
            if (!empty($line[2])) {
                Record::create([
                    'type' => $type,
                    'group' => $group,
                    'round' => $roundType,
                    'bowtype' => $bowType,
                    'firstname' => ($line[2] ?? ''),
                    'lastname' => ($line[3] ?? ''),
                    'club' => ($line[4] ?? ''),
                    'division' => ($line[5] ?? ''),
                    'score' => ($line[6] ?? ''),
                    'xcount' => ($line[7] ?? ''),
                    'date' => ($line[8] ?? '')
                ]);
            }
        }

        // Finsihed
        return 0;
    }
}
