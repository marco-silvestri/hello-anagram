<?php

declare(strict_types=1);

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class AnagramDetector extends Command
{
    protected $signature = 'detect {needle} {haystack}';

    private string $_needle = "";
    private array $_combinations = [];

    protected $description = 'Detect if an anagram of the first argument is present in the second argument';

    public function handle():?bool
    {
        if(strlen($this->argument('needle')) > 1024
        || strlen($this->argument('haystack')) > 1024)
        {
            $this->warning('Arguments cannot exceed 1024 chars length');
            return null;
        }

        $this->_needle = trim($this->argument('needle'));

        $this->combine(strlen($this->_needle));

        $found = collect($this->_combinations)->filter(function ($item, $key) {
            return stripos(trim($this->argument('haystack')), $item);
        })->toArray();

        if (count($found) > 0) {
            $this->info('Vero');

            return true;
        }

        $this->error('Falso');
        return false;
    }

    private function combine(int $totalLength, int $startAt = 0): void
    {
        if ($startAt === $totalLength) {
            $this->_combinations[] = $this->_needle;
        } else {
            for ($current = $startAt; $current < $totalLength; $current++) {
                $this->changePosition($startAt, $current);
                $this->combine($totalLength, $startAt + 1);
                $this->changePosition($startAt, $current);
            }
        }
    }

    private function changePosition($previous, $current):void
    {
        $temp = $this->_needle[$previous];
        $this->_needle[$previous] = $this->_needle[$current];
        $this->_needle[$current] = $temp;
    }
}
