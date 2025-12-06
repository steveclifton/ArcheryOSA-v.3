<?php

namespace App\Services;

class ScoringService
{

    private array $results;

    private array $sortedResults;

    public function __construct(array $results)
    {
        $this->results = $results;
        $this->sortedResults = $this->sort();
    }

    protected function sort()
    {
        $return = [];

        foreach ($this->results as $bowtype => $r) {

            // HERE I NEED TO SORT THE DIVISIONS BY A SEQUENCE YET TO BE ADDED TO DIVISIONS
            ksort($r);

            foreach ($r as $d => &$res) {
                $rounds = $res['rounds'];
                unset($res['rounds']);

                // Sort each divisions results by highest first
                uasort($res, function ($a, $b) use ($d) {
                    if (empty($a['total']) && empty($b['total'])) {
                        return 0;
                    }

                    if ((int)$b['total'] > (int)$a['total']) {
                        return 1;
                    }
                    if ((int)$b['total'] < (int)$a['total']) {
                        return -1;
                    }

                    if ((int)$b['total'] != (int)$a['total']) {
                        return 0;
                    }

                    if ((int)$b['inners'] > (int)$a['inners']) {
                        return 1;
                    }
                    if ((int)$b['inners'] < (int)$a['inners']) {
                        return -1;
                    }

                    return 0;
                });
                $res['rounds'] = $rounds;
            }

            $return = array_merge($return, $r);
        }

        return $return;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getResultsByKey(string $key): ?array
    {
        return $this->results[$key] ?? null;
    }

    public function getSortedResults(): array
    {
        return $this->sortedResults;
    }

    public function getSortedResultsByDivisionKey(string $key): ?array
    {
        return $this->sortedResults[$key] ?? null;

    }


}