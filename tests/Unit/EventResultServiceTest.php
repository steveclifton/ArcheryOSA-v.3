<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Services\EventResultService;
use Mockery;
use Tests\TestCase;

class EventResultServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_event_competition_results_returns_empty_array_for_api_when_competition_not_in_event()
    {
        $event = new Event();
        $event->eventid = 100;

        $competitionModel = Mockery::mock('alias:App\\Models\\EventCompetition');
        $competitionModel->shouldReceive('where')->once()->with('eventcompetitionid', 999)->andReturnSelf();
        $competitionModel->shouldReceive('where')->once()->with('eventid', 100)->andReturnSelf();
        $competitionModel->shouldReceive('first')->once()->andReturn(null);

        $service = new EventResultService();

        $response = $service->getEventCompetitionResults($event, 999, true);

        $this->assertSame([], $response);
    }

    public function test_get_event_competition_results_returns_eventcompetition_without_results_for_api_when_no_scores()
    {
        $event = new Event();
        $event->eventid = 10;

        $eventCompetition = (object) [
            'eventcompetitionid' => 22,
            'eventid' => 10,
        ];

        $competitionModel = Mockery::mock('alias:App\\Models\\EventCompetition');
        $competitionModel->shouldReceive('where')->once()->with('eventcompetitionid', 22)->andReturnSelf();
        $competitionModel->shouldReceive('where')->once()->with('eventid', 10)->andReturnSelf();
        $competitionModel->shouldReceive('first')->once()->andReturn($eventCompetition);

        $service = new class extends EventResultService {
            public array $fakeScores = [];

            protected function getEventCompetitionScores(int $eventId, int $eventCompetitionId): array
            {
                return $this->fakeScores;
            }
        };
        $service->fakeScores = [];

        $response = $service->getEventCompetitionResults($event, 22, true);

        $this->assertArrayHasKey('eventcompetition', $response);
        $this->assertArrayHasKey('event', $response);
        $this->assertArrayNotHasKey('results', $response);
        $this->assertSame($eventCompetition, $response['eventcompetition']);
        $this->assertSame($event, $response['event']);
    }

    public function test_get_event_competition_results_groups_and_sorts_scores_for_api_response()
    {
        $event = new Event();
        $event->eventid = 77;

        $eventCompetition = (object) [
            'eventcompetitionid' => 33,
            'eventid' => 77,
        ];

        $competitionModel = Mockery::mock('alias:App\\Models\\EventCompetition');
        $competitionModel->shouldReceive('where')->once()->with('eventcompetitionid', 33)->andReturnSelf();
        $competitionModel->shouldReceive('where')->once()->with('eventid', 77)->andReturnSelf();
        $competitionModel->shouldReceive('first')->once()->andReturn($eventCompetition);

        $scores = [
            (object) [
                'division' => 'Open',
                'gender' => 'm',
                'dist1' => 70,
                'dist2' => 60,
                'dist3' => 50,
                'dist4' => null,
                'unit' => 'm',
                'username' => 'alice',
                'firstname' => 'alice',
                'lastname' => 'smith',
                'roundname' => 'WA 720',
                'dist1score' => 250,
                'dist2score' => 240,
                'dist3score' => 230,
                'dist4score' => null,
                'total' => 720,
                'inners' => 10,
                'max' => 3,
            ],
            (object) [
                'division' => 'Open',
                'gender' => 'm',
                'dist1' => 70,
                'dist2' => 60,
                'dist3' => 50,
                'dist4' => null,
                'unit' => 'm',
                'username' => 'bob',
                'firstname' => 'bob',
                'lastname' => 'stone',
                'roundname' => 'WA 720',
                'dist1score' => 255,
                'dist2score' => 250,
                'dist3score' => 245,
                'dist4score' => null,
                'total' => 750,
                'inners' => 12,
                'max' => 5,
            ],
        ];

        $service = new class extends EventResultService {
            public array $fakeScores = [];

            protected function getEventCompetitionScores(int $eventId, int $eventCompetitionId): array
            {
                return $this->fakeScores;
            }
        };
        $service->fakeScores = $scores;

        $response = $service->getEventCompetitionResults($event, 33, true);

        $this->assertArrayHasKey('results', $response);
        $this->assertArrayHasKey('Open Men', $response['results']);
        $this->assertSame($eventCompetition, $response['eventcompetition']);
        $this->assertSame($event, $response['event']);

        $group = $response['results']['Open Men'];
        $this->assertSame(70, $group['rounds']['dist1']);
        $this->assertSame('m', $group['rounds']['unit']);
        $rankedArchers = array_values(array_filter($group, fn($entry, $key) => $key !== 'rounds', ARRAY_FILTER_USE_BOTH));
        $this->assertSame('<a href="/profile/public/bob">Bob Stone</a>', $rankedArchers[0]['archer']);
        $this->assertSame(750, $rankedArchers[0]['total']);
        $this->assertSame(5, $rankedArchers[0]['xcount']);
        $this->assertSame('<a href="/profile/public/alice">Alice Smith</a>', $rankedArchers[1]['archer']);
        $this->assertSame(720, $rankedArchers[1]['total']);
    }

    public function test_get_event_overall_results_returns_competitions_for_api_when_no_scores()
    {
        $event = new Event();
        $event->eventid = 500;

        $service = new class extends EventResultService {
            public array $fakeLabels = [];
            public array $fakeScores = [];

            protected function getEventCompetitionLabels(int $eventId): array
            {
                return $this->fakeLabels;
            }

            protected function getEventOverallFlatScores(int $eventId): array
            {
                return $this->fakeScores;
            }
        };

        $service->fakeLabels = [10 => 'Saturday - 01 Jan', 11 => 'Sunday - 02 Jan'];
        $service->fakeScores = [];

        $response = $service->getEventOverallResults($event, true);

        $this->assertArrayHasKey('competitionlabels', $response);
        $this->assertArrayHasKey('event', $response);
        $this->assertArrayNotHasKey('results', $response);
        $this->assertSame($event, $response['event']);
        $this->assertSame($service->fakeLabels, $response['competitionlabels']);
    }

    public function test_get_event_overall_results_aggregates_and_sorts_division_results_for_api()
    {
        $event = new Event();
        $event->eventid = 501;

        $service = new class extends EventResultService {
            public array $fakeLabels = [];
            public array $fakeScores = [];

            protected function getEventCompetitionLabels(int $eventId): array
            {
                return $this->fakeLabels;
            }

            protected function getEventOverallFlatScores(int $eventId): array
            {
                return $this->fakeScores;
            }
        };

        $service->fakeLabels = [10 => 'Day 1 - 01 Jan', 11 => 'Day 2 - 02 Jan'];
        $service->fakeScores = [
            (object) [
                'eventcompetitionid' => 10,
                'total' => 300,
                'inners' => 5,
                'max' => 1,
                'sequence' => 1,
                'userid' => 1,
                'divisionid' => 20,
                'firstname' => 'alice',
                'lastname' => 'jones',
                'gender' => 'f',
                'username' => 'alice',
                'division' => 'Open',
                'bowtype' => 'Recurve',
                'roundname' => 'WA 720',
            ],
            (object) [
                'eventcompetitionid' => 11,
                'total' => 350,
                'inners' => 8,
                'max' => 2,
                'sequence' => 2,
                'userid' => 1,
                'divisionid' => 20,
                'firstname' => 'alice',
                'lastname' => 'jones',
                'gender' => 'f',
                'username' => 'alice',
                'division' => 'Open',
                'bowtype' => 'Recurve',
                'roundname' => 'WA 720',
            ],
            (object) [
                'eventcompetitionid' => 10,
                'total' => 400,
                'inners' => 10,
                'max' => 3,
                'sequence' => 1,
                'userid' => 2,
                'divisionid' => 20,
                'firstname' => 'beth',
                'lastname' => 'stone',
                'gender' => 'f',
                'username' => 'beth',
                'division' => 'Open',
                'bowtype' => 'Recurve',
                'roundname' => 'WA 720',
            ],
            (object) [
                'eventcompetitionid' => 11,
                'total' => 200,
                'inners' => 6,
                'max' => 1,
                'sequence' => 2,
                'userid' => 2,
                'divisionid' => 20,
                'firstname' => 'beth',
                'lastname' => 'stone',
                'gender' => 'f',
                'username' => 'beth',
                'division' => 'Open',
                'bowtype' => 'Recurve',
                'roundname' => 'WA 720',
            ],
        ];

        $response = $service->getEventOverallResults($event, true);

        $this->assertArrayHasKey('results', $response);
        $this->assertArrayHasKey('Womens Open', $response['results']);

        $group = $response['results']['Womens Open'];
        $this->assertSame('WA 720', $group['rounds'][10]);
        $this->assertSame('WA 720', $group['rounds'][11]);

        $rankedArchers = array_values(array_filter($group, fn($entry, $key) => $key !== 'rounds', ARRAY_FILTER_USE_BOTH));
        $this->assertSame('<a href="/profile/public/alice">Alice Jones</a>', $rankedArchers[0]['archer']);
        $this->assertSame(650, $rankedArchers[0]['total']);
        $this->assertSame(350, $rankedArchers[0][11]);
        $this->assertSame(8, $rankedArchers[0]['inners']);
        $this->assertSame('<a href="/profile/public/beth">Beth Stone</a>', $rankedArchers[1]['archer']);
        $this->assertSame(600, $rankedArchers[1]['total']);
    }

    public function test_get_event_overall_results_keeps_same_user_in_separate_divisions()
    {
        $event = new Event();
        $event->eventid = 502;

        $service = new class extends EventResultService {
            public array $fakeLabels = [];
            public array $fakeScores = [];

            protected function getEventCompetitionLabels(int $eventId): array
            {
                return $this->fakeLabels;
            }

            protected function getEventOverallFlatScores(int $eventId): array
            {
                return $this->fakeScores;
            }
        };

        $service->fakeLabels = [10 => 'Day 1 - 01 Jan'];
        $service->fakeScores = [
            (object) [
                'eventcompetitionid' => 10,
                'total' => 320,
                'inners' => 5,
                'max' => 2,
                'sequence' => 1,
                'userid' => 9,
                'divisionid' => 100,
                'firstname' => 'sam',
                'lastname' => 'archer',
                'gender' => 'm',
                'username' => 'sam',
                'division' => 'Open',
                'bowtype' => 'Compound',
                'roundname' => 'WA 720',
            ],
            (object) [
                'eventcompetitionid' => 10,
                'total' => 290,
                'inners' => 4,
                'max' => 1,
                'sequence' => 1,
                'userid' => 9,
                'divisionid' => 101,
                'firstname' => 'sam',
                'lastname' => 'archer',
                'gender' => 'm',
                'username' => 'sam',
                'division' => 'U21',
                'bowtype' => 'Compound',
                'roundname' => 'WA 720',
            ],
        ];

        $response = $service->getEventOverallResults($event, true);

        $this->assertArrayHasKey('Mens Open', $response['results']);
        $this->assertArrayHasKey('Mens U21', $response['results']);
        $this->assertCount(2, $response['results']);
    }
}
