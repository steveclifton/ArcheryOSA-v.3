<?php

namespace Tests\Unit;

use App\Http\Controllers\Events\PublicEvents\ResultsController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tests\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class ResultsControllerNPlusOneTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Force in-memory sqlite for this test class only
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        // Ensure the connection is refreshed after other tests switch drivers
        DB::setDefaultConnection('sqlite');
        DB::purge();
        DB::reconnect();

        $this->createSchema();
    }

    public function test_league_weeks_are_loaded_with_single_scores_query()
    {
        $this->seedLeagueData();

        DB::enableQueryLog();

        $controller = app(ResultsController::class);
        $controller->getEventResultsList(Request::create('/event/results/league', 'GET', [
            'eventurl' => 'league',
        ]));

        $scoreQueries = $this->filterQueriesContaining('scores_flat');

        $this->assertCount(1, $scoreQueries, 'Weeks should be fetched with a single scores_flat query');
    }

    public function test_event_competition_scores_are_prefetched_for_all_competitions()
    {
        $this->seedEventData();

        DB::enableQueryLog();

        $controller = app(ResultsController::class);
        $response = $controller->getEventResultsList(Request::create('/event/results/event', 'GET', [
            'eventurl' => 'event',
        ]));

        $scoreQueries = $this->filterQueriesContaining('scores_flat');
        $this->assertCount(1, $scoreQueries, 'Scores for all competitions should be loaded in a single query');

        $viewData = $response->getData();
        $this->assertEquals(1, $viewData['overall'], 'Overall flag should remain true when scores exist');
        $this->assertNotNull($viewData['eventcompetitions'][0]->score, 'Competition should have an attached score');
        $this->assertNotNull($viewData['eventcompetitions'][1]->score, 'Second competition should have an attached score');
    }

    private function createSchema(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('eventid');
            $table->string('eventurl');
            $table->integer('eventtypeid');
            $table->boolean('showoverall')->default(true);
        });

        Schema::create('eventcompetitions', function (Blueprint $table) {
            $table->increments('eventcompetitionid');
            $table->integer('eventid');
            $table->integer('currentweek')->default(1);
            $table->date('date')->nullable();
        });

        Schema::create('scores_flat', function (Blueprint $table) {
            $table->increments('flatscoreid');
            $table->integer('eventid');
            $table->integer('eventcompetitionid');
            $table->integer('week')->nullable();
            $table->integer('entryid')->nullable();
            $table->integer('divisionid')->nullable();
            $table->integer('roundid')->nullable();
            $table->integer('userid')->nullable();
        });
    }

    private function seedLeagueData(): void
    {
        DB::table('events')->insert([
            'eventid' => 1,
            'eventurl' => 'league',
            'eventtypeid' => 2, // league
            'showoverall' => true,
        ]);

        DB::table('eventcompetitions')->insert([
            'eventcompetitionid' => 10,
            'eventid' => 1,
            'currentweek' => 4,
        ]);

        // Scores exist for weeks 1 and 3 only
        DB::table('scores_flat')->insert([
            ['flatscoreid' => 1, 'eventid' => 1, 'eventcompetitionid' => 10, 'week' => 1],
            ['flatscoreid' => 2, 'eventid' => 1, 'eventcompetitionid' => 10, 'week' => 3],
        ]);
    }

    private function seedEventData(): void
    {
        DB::table('events')->insert([
            'eventid' => 2,
            'eventurl' => 'event',
            'eventtypeid' => 1, // normal event
            'showoverall' => true,
        ]);

        DB::table('eventcompetitions')->insert([
            ['eventcompetitionid' => 20, 'eventid' => 2, 'date' => '2024-01-01'],
            ['eventcompetitionid' => 21, 'eventid' => 2, 'date' => '2024-01-02'],
        ]);

        DB::table('scores_flat')->insert([
            ['flatscoreid' => 3, 'eventid' => 2, 'eventcompetitionid' => 20, 'week' => null],
            ['flatscoreid' => 4, 'eventid' => 2, 'eventcompetitionid' => 21, 'week' => null],
        ]);
    }

    private function filterQueriesContaining(string $needle): array
    {
        return array_filter(DB::getQueryLog(), function (array $query) use ($needle) {
            return strpos($query['query'], $needle) !== false;
        });
    }
}
