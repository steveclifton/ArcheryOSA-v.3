<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicProfileNPlusOneTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Use a lightweight in-memory database for these tests.
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        $this->createSchema();
        $this->seedData();
    }

    public function test_get_entries_for_events_fetches_once_and_groups_by_event()
    {
        $controller = new class extends ProfileController {
            public function exposeGetEntriesForEvents(array $eventIds, int $userId)
            {
                return $this->getEntriesForEvents($eventIds, $userId);
            }
        };

        DB::enableQueryLog();

        $entries = $controller->exposeGetEntriesForEvents([1, 2], 1);

        $queries = DB::getQueryLog();
        $entryQueries = array_filter($queries, function ($query) {
            return strpos($query['query'], 'evententrys') !== false;
        });

        $this->assertCount(1, $entryQueries, 'Entries should be loaded with a single query');
        $this->assertArrayHasKey(1, $entries);
        $this->assertArrayHasKey(2, $entries);
        $this->assertCount(1, $entries[1], 'Event 1 should have one entry');
        $this->assertCount(1, $entries[2], 'Event 2 should have one entry');
        $this->assertEquals('tester', $entries[1][0]->username);
    }

    private function createSchema(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userid');
            $table->string('username');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
        });

        Schema::create('evententrys', function (Blueprint $table) {
            $table->increments('entryid');
            $table->integer('eventid');
            $table->integer('userid');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('gender', 1)->nullable();
            $table->integer('schoolid')->nullable();
            $table->integer('entrystatusid');
        });

        Schema::create('entrycompetitions', function (Blueprint $table) {
            $table->increments('entrycompetitionid');
            $table->integer('entryid');
            $table->integer('eventcompetitionid');
            $table->integer('roundid');
            $table->string('divisionid');
            $table->string('label')->nullable();
            $table->integer('sequence')->default(1);
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('divisionid');
            $table->string('label');
            $table->string('bowtype')->nullable();
        });

        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('roundid');
            $table->string('unit')->nullable();
            $table->string('code')->nullable();
            $table->string('label')->nullable();
        });

        Schema::create('scores_flat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entryid');
            $table->integer('divisionid');
            $table->integer('roundid');
            $table->integer('eventid');
            $table->integer('eventcompetitionid');
            $table->integer('userid');
            $table->integer('total')->default(0);
            $table->integer('eventtypeid')->default(1);
        });

        Schema::create('schools', function (Blueprint $table) {
            $table->increments('schoolid');
            $table->string('label')->nullable();
        });
    }

    private function seedData(): void
    {
        DB::table('users')->insert([
            'userid'    => 1,
            'username'  => 'tester',
            'firstname' => 'Test',
            'lastname'  => 'User',
        ]);

        DB::table('divisions')->insert([
            ['divisionid' => 1, 'label' => 'Senior', 'bowtype' => 'Recurve'],
            ['divisionid' => 2, 'label' => 'Junior', 'bowtype' => 'Compound'],
        ]);

        DB::table('rounds')->insert([
            ['roundid' => 1, 'unit' => 'm', 'code' => 'R1', 'label' => 'Round 1'],
            ['roundid' => 2, 'unit' => 'm', 'code' => 'R2', 'label' => 'Round 2'],
        ]);

        // Event 1
        DB::table('evententrys')->insert([
            'entryid' => 10,
            'eventid' => 1,
            'userid' => 1,
            'firstname' => 'Test',
            'lastname' => 'User',
            'gender' => 'm',
            'entrystatusid' => 2,
        ]);

        DB::table('entrycompetitions')->insert([
            'entrycompetitionid' => 100,
            'entryid' => 10,
            'eventcompetitionid' => 1000,
            'roundid' => 1,
            'divisionid' => '1',
            'label' => 'Event1Comp',
            'sequence' => 1,
        ]);

        DB::table('scores_flat')->insert([
            'entryid' => 10,
            'divisionid' => 1,
            'roundid' => 1,
            'eventid' => 1,
            'eventcompetitionid' => 1000,
            'userid' => 1,
            'total' => 300,
            'eventtypeid' => 1,
        ]);

        // Event 2
        DB::table('evententrys')->insert([
            'entryid' => 20,
            'eventid' => 2,
            'userid' => 1,
            'firstname' => 'Test',
            'lastname' => 'User',
            'gender' => 'm',
            'entrystatusid' => 2,
        ]);

        DB::table('entrycompetitions')->insert([
            'entrycompetitionid' => 200,
            'entryid' => 20,
            'eventcompetitionid' => 2000,
            'roundid' => 2,
            'divisionid' => '2',
            'label' => 'Event2Comp',
            'sequence' => 1,
        ]);

        DB::table('scores_flat')->insert([
            'entryid' => 20,
            'divisionid' => 2,
            'roundid' => 2,
            'eventid' => 2,
            'eventcompetitionid' => 2000,
            'userid' => 1,
            'total' => 250,
            'eventtypeid' => 1,
        ]);
    }
}
