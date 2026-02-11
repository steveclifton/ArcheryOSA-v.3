<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Services\LeagueResultsService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class LeagueResultsServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_league_overall_results_batches_average_and_points_queries_for_api_calls()
    {
        $event = new Event();
        $event->eventid = 100;

        $entries = [
            (object) [
                'userid' => 1,
                'divisionid' => 10,
                'bowtype' => 'Recurve',
                'divisionname' => 'Open',
                'username' => 'alice',
                'gender' => 'f',
            ],
            (object) [
                'userid' => 2,
                'divisionid' => 10,
                'bowtype' => 'Recurve',
                'divisionname' => 'Open',
                'username' => 'bob',
                'gender' => 'm',
            ],
        ];

        $eventCompetition = (object) ['ignoregenders' => true];

        // Prevent the underlying query in getEventEntrySorted
        $service = Mockery::mock(LeagueResultsService::class)->makePartial();
        $service->shouldReceive('getEventEntrySorted')
            ->once()
            ->with($event->eventid)
            ->andReturn($entries);

        // Mock the EventCompetition lookup
        $competitionModel = Mockery::mock('alias:App\\Models\\EventCompetition');
        $competitionModel->shouldReceive('where')->once()->with('eventid', $event->eventid)->andReturnSelf();
        $competitionModel->shouldReceive('first')->once()->andReturn($eventCompetition);

        // Each entry triggers three selects (top10 scores, average, points)
        DB::shouldReceive('select')
            ->once()
            ->with(
                Mockery::on(fn($query) => str_contains($query, 'scores_flat') && str_contains($query, 'ROW_NUMBER() OVER') && str_contains($query, 'IN (')),
                Mockery::on(function ($bindings) {
                    return $bindings['eventid'] === 100
                        && in_array(1, $bindings, true)
                        && in_array(2, $bindings, true)
                        && in_array(10, $bindings, true);
                })
            )
            ->andReturn([
                (object) ['userid' => 1, 'divisionid' => 10, 'total' => 500],
                (object) ['userid' => 2, 'divisionid' => 10, 'total' => 450],
            ]);

        DB::shouldReceive('select')
            ->once()
            ->with(
                Mockery::on(fn($query) => str_contains($query, 'leagueaverages') && str_contains($query, 'IN (')),
                Mockery::on(function ($bindings) {
                    return $bindings['eventid'] === 100
                        && in_array(1, $bindings, true)
                        && in_array(2, $bindings, true)
                        && in_array(10, $bindings, true);
                })
            )
            ->andReturn([
                (object) ['userid' => 1, 'divisionid' => 10, 'average' => 48],
                (object) ['userid' => 2, 'divisionid' => 10, 'average' => 42],
            ]);

        DB::shouldReceive('select')
            ->once()
            ->with(
                Mockery::on(fn($query) => str_contains($query, 'leaguepoints') && str_contains($query, 'ROW_NUMBER() OVER') && str_contains($query, 'IN (')),
                Mockery::on(function ($bindings) {
                    return $bindings['eventid'] === 100
                        && in_array(1, $bindings, true)
                        && in_array(2, $bindings, true)
                        && in_array(10, $bindings, true);
                })
            )
            ->andReturn([
                (object) ['userid' => 1, 'divisionid' => 10, 'points' => 95],
                (object) ['userid' => 2, 'divisionid' => 10, 'points' => 88],
            ]);

        $result = $service->getLeagueOverallResults($event, true);

        $this->assertArrayHasKey('evententrys', $result);
        $this->assertArrayHasKey('Recurve', $result['evententrys']);
        $this->assertArrayHasKey('Open', $result['evententrys']['Recurve']);
        $this->assertArrayHasKey('alice', $result['evententrys']['Recurve']['Open']);
        $this->assertArrayHasKey('bob', $result['evententrys']['Recurve']['Open']);

        $this->assertEquals(500, $result['evententrys']['Recurve']['Open']['alice']->top10->total);
        $this->assertEquals(95, $result['evententrys']['Recurve']['Open']['alice']->top10points->points);
        $this->assertEquals(42, $result['evententrys']['Recurve']['Open']['bob']->average->average);
    }

    public function test_get_league_overall_results_points_sum_is_capped_to_top_10()
    {
        $event = new Event();
        $event->eventid = 101;

        $entries = [
            (object) [
                'userid' => 7,
                'divisionid' => 20,
                'bowtype' => 'Compound',
                'divisionname' => 'Senior',
                'username' => 'charlie',
                'gender' => 'm',
            ],
        ];

        $eventCompetition = (object) ['ignoregenders' => true];

        $service = Mockery::mock(LeagueResultsService::class)->makePartial();
        $service->shouldReceive('getEventEntrySorted')
            ->once()
            ->with($event->eventid)
            ->andReturn($entries);

        $competitionModel = Mockery::mock('alias:App\\Models\\EventCompetition');
        $competitionModel->shouldReceive('where')->once()->with('eventid', $event->eventid)->andReturnSelf();
        $competitionModel->shouldReceive('first')->once()->andReturn($eventCompetition);

        DB::shouldReceive('select')
            ->once()
            ->with(
                Mockery::on(fn($query) => str_contains($query, 'scores_flat') && str_contains($query, 'ROW_NUMBER() OVER') && str_contains($query, 'IN (')),
                Mockery::type('array')
            )
            ->andReturn([(object) ['userid' => 7, 'divisionid' => 20, 'total' => 600]]);

        DB::shouldReceive('select')
            ->once()
            ->with(
                Mockery::on(fn($query) => str_contains($query, 'leagueaverages') && str_contains($query, 'IN (')),
                Mockery::type('array')
            )
            ->andReturn([(object) ['userid' => 7, 'divisionid' => 20, 'average' => 55]]);

        DB::shouldReceive('select')
            ->once()
            ->with(
                Mockery::on(fn($query) => str_contains($query, 'leaguepoints') && str_contains($query, 'ROW_NUMBER() OVER') && str_contains($query, 'IN (')),
                Mockery::type('array')
            )
            ->andReturn([(object) ['userid' => 7, 'divisionid' => 20, 'points' => 100]]);

        $result = $service->getLeagueOverallResults($event, true);

        $entry = $result['evententrys']['Compound']['Senior']['charlie'];
        $this->assertEquals(600, $entry->top10->total);
        $this->assertEquals(100, $entry->top10points->points);
        $this->assertEquals(55, $entry->average->average);
    }

    public function test_get_league_competition_results_returns_empty_array_for_api_when_no_entries()
    {
        $event = new Event();
        $event->eventid = 200;

        DB::shouldReceive('select')
            ->once()
            ->with(Mockery::type('string'), ['eventid' => 200, 'week' => 1, 'week2' => 1])
            ->andReturn([]);

        $service = new LeagueResultsService();

        $response = $service->getLeagueCompetitionResults($event, 1, true);

        $this->assertSame([], $response);
    }

    public function test_get_league_competition_results_groups_entries_by_bowtype_and_division()
    {
        $event = new Event();
        $event->eventid = 300;

        $dbEntries = [
            (object) [
                'userid' => 1,
                'entrycompetitionid' => 5,
                'eventcompetitionid' => 9,
                'roundid' => 2,
                'divisionname' => 'U21',
                'bowtype' => 'Compound',
                'gender' => 'm',
                'total' => 580,
            ],
            (object) [
                'userid' => 2,
                'entrycompetitionid' => 6,
                'eventcompetitionid' => 9,
                'roundid' => 2,
                'divisionname' => 'U21',
                'bowtype' => 'Compound',
                'gender' => 'f',
                'total' => 575,
            ],
        ];

        DB::shouldReceive('select')
            ->once()
            ->with(Mockery::type('string'), ['eventid' => 300, 'week' => 2, 'week2' => 2])
            ->andReturn($dbEntries);

        $eventCompetition = (object) ['ignoregenders' => true];
        $competitionModel = Mockery::mock('alias:App\\Models\\EventCompetition');
        $competitionModel->shouldReceive('where')->once()->with('eventid', $event->eventid)->andReturnSelf();
        $competitionModel->shouldReceive('first')->once()->andReturn($eventCompetition);

        $service = new LeagueResultsService();

        $response = $service->getLeagueCompetitionResults($event, 2, true);

        $this->assertArrayHasKey('evententrys', $response);
        $this->assertArrayHasKey('Compound', $response['evententrys']);
        $this->assertArrayHasKey('U21', $response['evententrys']['Compound']);

        $entries = $response['evententrys']['Compound']['U21'];
        $this->assertCount(2, $entries);
        $this->assertFalse(property_exists($entries[0], 'userid'));
        $this->assertFalse(property_exists($entries[1], 'userid'));
    }
}
