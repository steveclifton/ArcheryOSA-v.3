<?php

namespace App\Services;

class ScoringService
{
    private array $results;

    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }

    public function getSortedResults()
    {
        return $this->results;
    }

    public function sort()
    {
        foreach ($this->results as $key => &$r) {
            // Remove rounds before sorting
            $rounds = $r['rounds'] ?? [];
            unset($r['rounds']);

            // Sort each divisions results by highest first
            uasort($r, function ($a, $b) {
                if (empty($a['total']) && empty($b['total'])) {
                    return 0;
                }

                if ((int)$b['total'] > (int)$a['total']) {
                    return 1;
                }
                if ((int)$b['total'] < (int)$a['total']) {
                    return -1;
                }

                // Stop here if totals are not equal - this means the scores are not tied
                if ((int)$b['total'] != (int)$a['total']) {
                    return 0;
                }

                // First attempt to break tie with inners
                if ((int)$b['inners'] > (int)$a['inners']) {
                    return 1;
                } else if ((int)$b['inners'] < (int)$a['inners']) {
                    return -1;
                } else if ((int)$b['inners'] == (int)$a['inners']) {
                    // If inners are also tied, continue to next tiebreaker
                    // Second attempt to break tie with xcount
                    if ((int)$b['xcount'] > (int)$a['xcount']) {
                        return 1;
                    } else if ((int)$b['xcount'] < (int)$a['xcount']) {
                        return -1;
                    }
                }

                return 0;
            });

            // Restore rounds after sorting
            $this->results[$key]['rounds'] = $rounds;
        }

        return $this;
    }

}