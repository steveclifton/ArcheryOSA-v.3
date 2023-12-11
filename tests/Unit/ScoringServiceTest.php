<?php

namespace Tests\Unit;

use App\Services\ScoringService;
use PHPUnit\Framework\TestCase;

class ScoringServiceTest extends TestCase
{
    private ScoringService $scoringService;

    private array $results;

    protected function setUp(): void
    {
        $this->results = include 'results.php';
        $this->scoringService = new ScoringService($this->results);

        parent::setUp();
    }

    public function test_scoring_service_has_no_results()
    {
        $scoringService = new ScoringService([]);

        $this->assertEmpty($scoringService->getResults());
    }

    public function test_scoring_service_has_results_when_passed()
    {
        $this->assertNotEmpty($this->scoringService->getResults());

        $this->assertCount(3, $this->scoringService->getResults());

        $this->assertArrayHasKey('barebow', $this->scoringService->getResults());
    }

    public function test_can_get_a_result_by_key()
    {
        $this->assertNotEmpty($this->scoringService->getResultsByKey('barebow'));
    }

    public function test_can_get_all_results_sorted()
    {
        $allResultsSorted = $this->scoringService->getSortedResults();

        foreach ($allResultsSorted as $division => $results) {
            unset($results['rounds']); // Not needed for testing totals

            $previousKey = key($results);
            foreach ($results as $key => $result) {
                if ($key == $previousKey) {
                    continue;
                }

                $total = $result['total'];
                $previousResult = $results[$previousKey]['total'];

                if (!empty($total)) {
                    $this->assertGreaterThanOrEqual(
                        $total,
                        $previousResult,
                        sprintf(
                            "%s is not greater than %s for Division %s",
                            $total,
                            $previousResult,
                            $division
                        )
                    );
                }
                $previousKey = $key;
            }
        }
    }

    public function test_can_get_division_results_sorted()
    {
        $recurveMensOpen = $this->scoringService->getSortedResultsByDivisionKey('Mens Open Recurve');

        $this->assertCount(14, $recurveMensOpen);

        unset($recurveMensOpen['rounds']); // Not needed to test the totals

        $this->assertCount(13, $recurveMensOpen);

        $previousKey = key($recurveMensOpen);
        foreach ($recurveMensOpen as $key => $result) {
            if ($key == $previousKey) {
                continue;
            }

            $total = $result['total'];
            $previousResult = $recurveMensOpen[$previousKey]['total'];

            if (!empty($total)) {
                $this->assertGreaterThanOrEqual(
                    $total,
                    $previousResult,
                    sprintf(
                        "%s is not greater than %s",
                        $total,
                        $previousResult,
                    )
                );
            }
            $previousKey = $key;
        }
    }
}
