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
}
