<?php

namespace Tests\Unit;

use App\Services\ScoringService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ScoringServiceTest extends TestCase
{
    private ScoringService $scoringService;

    private array $results;

    protected function setUp(): void
    {
        $this->results = include 'results.php';
        $this->scoringService = new ScoringService();

        parent::setUp();
    }

    #[Test]
    public function it_sorts_results_by_total_score_descending()
    {
        $unsortedResults = [
            'Test Division' => [
                'rounds' => ['dist1' => 50, 'dist2' => 40],
                0 => ['archer' => 'Archer A', 'total' => 500, 'inners' => 10, 'xcount' => 5],
                1 => ['archer' => 'Archer B', 'total' => 700, 'inners' => 15, 'xcount' => 8],
                2 => ['archer' => 'Archer C', 'total' => 600, 'inners' => 12, 'xcount' => 6],
            ],
        ];

        $sorted = $this->scoringService->setResults($unsortedResults)->sort()->getSortedResults();

        $scores = array_values(array_filter(array_keys($sorted['Test Division']), 'is_numeric'));
        $this->assertEquals(700, $sorted['Test Division'][$scores[0]]['total']);
        $this->assertEquals(600, $sorted['Test Division'][$scores[1]]['total']);
        $this->assertEquals(500, $sorted['Test Division'][$scores[2]]['total']);
    }

    #[Test]
    public function it_breaks_ties_using_inners_count()
    {
        $tiedResults = [
            'Test Division' => [
                'rounds' => ['dist1' => 50],
                0 => ['archer' => 'Archer A', 'total' => 700, 'inners' => 10, 'xcount' => 5],
                1 => ['archer' => 'Archer B', 'total' => 700, 'inners' => 15, 'xcount' => 5],
                2 => ['archer' => 'Archer C', 'total' => 700, 'inners' => 12, 'xcount' => 5],
            ],
        ];

        $sorted = $this->scoringService->setResults($tiedResults)->sort()->getSortedResults();

        $scores = array_values(array_filter(array_keys($sorted['Test Division']), 'is_numeric'));
        $this->assertEquals(15, $sorted['Test Division'][$scores[0]]['inners']);
        $this->assertEquals(12, $sorted['Test Division'][$scores[1]]['inners']);
        $this->assertEquals(10, $sorted['Test Division'][$scores[2]]['inners']);
    }

    #[Test]
    public function it_breaks_ties_using_xcount_when_inners_are_equal()
    {
        $tiedResults = [
            'Test Division' => [
                'rounds' => ['dist1' => 50],
                0 => ['archer' => 'Archer A', 'total' => 700, 'inners' => 15, 'xcount' => 5],
                1 => ['archer' => 'Archer B', 'total' => 700, 'inners' => 15, 'xcount' => 10],
                2 => ['archer' => 'Archer C', 'total' => 700, 'inners' => 15, 'xcount' => 8],
            ],
        ];

        $sorted = $this->scoringService->setResults($tiedResults)->sort()->getSortedResults();

        $scores = array_values(array_filter(array_keys($sorted['Test Division']), 'is_numeric'));
        $this->assertEquals(10, $sorted['Test Division'][$scores[0]]['xcount']);
        $this->assertEquals(8, $sorted['Test Division'][$scores[1]]['xcount']);
        $this->assertEquals(5, $sorted['Test Division'][$scores[2]]['xcount']);
    }

    #[Test]
    public function it_preserves_rounds_array_after_sorting()
    {
        $results = [
            'Test Division' => [
                'rounds' => ['dist1' => 50, 'dist2' => 40, 'dist3' => 30],
                0 => ['archer' => 'Archer A', 'total' => 500, 'inners' => 10, 'xcount' => 5],
            ],
        ];

        $sorted = $this->scoringService->setResults($results)->sort()->getSortedResults();

        $this->assertArrayHasKey('rounds', $sorted['Test Division']);
        $this->assertEquals(['dist1' => 50, 'dist2' => 40, 'dist3' => 30], $sorted['Test Division']['rounds']);
    }

    #[Test]
    public function it_handles_zero_scores()
    {
        $results = [
            'Test Division' => [
                'rounds' => ['dist1' => 50],
                0 => ['archer' => 'Archer A', 'total' => 0, 'inners' => 0, 'xcount' => 0],
                1 => ['archer' => 'Archer B', 'total' => 500, 'inners' => 10, 'xcount' => 5],
            ],
        ];

        $sorted = $this->scoringService->setResults($results)->sort()->getSortedResults();

        $scores = array_values(array_filter(array_keys($sorted['Test Division']), 'is_numeric'));
        $this->assertEquals(500, $sorted['Test Division'][$scores[0]]['total']);
        $this->assertEquals(0, $sorted['Test Division'][$scores[1]]['total']);
    }

    #[Test]
    public function it_sorts_multiple_divisions()
    {
        $sorted = $this->scoringService->setResults($this->results)->sort()->getSortedResults();

        foreach ($sorted as $division => $results) {
            $this->assertArrayHasKey('rounds', $results);

            $scores = array_filter($results, fn($key) => is_numeric($key), ARRAY_FILTER_USE_KEY);
            $totals = array_column($scores, 'total');

            $sortedTotals = $totals;
            rsort($sortedTotals);

            // Verify descending order (allowing for ties)
            for ($i = 0; $i < count($totals) - 1; $i++) {
                $this->assertGreaterThanOrEqual($totals[$i + 1], $totals[$i]);
            }
        }
    }

    #[Test]
    public function it_handles_empty_total_values()
    {
        $results = [
            'Test Division' => [
                'rounds' => ['dist1' => 50],
                0 => ['archer' => 'Archer A', 'total' => '', 'inners' => 0, 'xcount' => 0],
                1 => ['archer' => 'Archer B', 'total' => '', 'inners' => 0, 'xcount' => 0],
            ],
        ];

        $sorted = $this->scoringService->setResults($results)->sort()->getSortedResults();

        $this->assertNotEmpty($sorted);
        $this->assertArrayHasKey('Test Division', $sorted);
    }

    #[Test]
    public function it_maintains_archer_data_integrity()
    {
        $results = [
            'Test Division' => [
                'rounds' => ['dist1' => 50],
                0 => [
                    'archer' => 'Test Archer',
                    'club' => 'Test Club',
                    'round' => 'Test Round',
                    'dist1' => 100,
                    'total' => 100,
                    'inners' => 5,
                    'xcount' => 3,
                ],
            ],
        ];

        $sorted = $this->scoringService->setResults($results)->sort()->getSortedResults();

        $scores = array_filter($sorted['Test Division'], fn($key) => is_numeric($key), ARRAY_FILTER_USE_KEY);
        $archer = reset($scores);

        $this->assertEquals('Test Archer', $archer['archer']);
        $this->assertEquals('Test Club', $archer['club']);
        $this->assertEquals('Test Round', $archer['round']);
        $this->assertEquals(100, $archer['dist1']);
    }

}
