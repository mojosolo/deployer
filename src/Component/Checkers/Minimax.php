<?php

declare(strict_types=1);

/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Component\Checkers;

class Minimax
{
    private const KING_VALUE = 3;

    private int $maxDepth;
    private int $evaluated = 0;
    private int $cutOffs = 0;

    public function __construct(int $maxDepth)
    {
        $this->maxDepth = $maxDepth;
    }

    public function clearStats(): void
    {
        $this->evaluated = 0;
        $this->cutOffs = 0;
    }

    public function bestMove(Board $board, bool $debug = false): array
    {
        $possibleMoves = $board->allMoves();
        if (empty($possibleMoves)) {
            throw new \Exception($board->isWhiteTurn() ? "white lost" : "black lost");
        } elseif ($board->isDraw()) {
            throw new \Exception("draw");
        }

        $bestRate = $board->isWhiteTurn() ? -INF : INF;
        $bestMove = null;

        foreach ($possibleMoves as $move) {
            [$rate,] = $this->minimax($move, $this->maxDepth, -INF, INF);
            if ($board->isWhiteTurn() ? $rate > $bestRate : $rate < $bestRate) {
                $bestRate = $rate;
                $bestMove = $move;
            }
            if ($debug) {
                echo sprintf("%s %s: (%f)\n", $board->isWhiteTurn() ? "white" : "black", $board->generateMoveName($move), $rate);
            }
        }
        return [$bestMove, $bestRate, $board->isWhiteTurn() ? 144 : -144];
    }

    public function bestRandomMove(Board $board, bool $debug = false): array
    {
        $possibleMoves = $board->allMoves();
        if (empty($possibleMoves)) {
            throw new \Exception($board->isWhiteTurn() ? "white lost" : "black lost");
        } elseif ($board->isDraw()) {
            throw new \Exception("draw");
        }

        $rates = [];
        $moves = [];
        $maxRate = -INF;

        // Collect moves and rates, tracking the highest rate found
        foreach ($possibleMoves as $i => $move) {
            [$rate, ] = $this->minimax($move, $this->maxDepth, -INF, INF);
            $rate = 1 + ($board->isWhiteTurn() ? $rate : -$rate);

            // Update max rate and reset moves if we find a new max
            if ($rate > $maxRate) {
                $maxRate = $rate;
                $rates = [$rate];
                $moves = [$move];
            } elseif ($rate == $maxRate) {
                $rates[] = $rate;
                $moves[] = $move;
            }

            if ($debug) {
                echo sprintf("%s %s: (%f)\n", $board->isWhiteTurn() ? "white" : "black", $board->generateMoveName($move), $rate);
            }
        }

        // Randomly pick one of the moves with the highest rate
        $index = array_rand($moves);
        return [$moves[$index], $rates[$index]];
    }

    private function minimax(Board $board, int $depth, float $alpha, float $beta): array
    {
        [$possibleMoves, $eatMoves] = $board->allMovesWithFlag();
        if (empty($possibleMoves)) {
            return [$board->isWhiteTurn() ? -1 : 1, 0];
        } elseif ($board->isDraw()) {
            return [0, 0];
        }

        if (count($possibleMoves) === 1) {
            [$rate, $steps] = $this->minimax($possibleMoves[0], $depth, $alpha, $beta);
            return [$rate, $steps + 1];
        }

        if ($depth <= 0 && !$eatMoves) {
            $this->evaluated++;
            $rate = $this->evaluateBoard($board);
            return [$rate, 0];
        }

        if ($board->isWhiteTurn()) {
            $maxRate = -INF;
            $minSteps = 0;
            foreach ($possibleMoves as $move) {
                [$rate, $steps] = $this->minimax($move, $depth - 1, $alpha, $beta);
                if ($rate > $maxRate || ($rate === $maxRate && $steps < $minSteps)) {
                    $maxRate = $rate;
                    $minSteps = $steps;
                }
                $alpha = max($alpha, $rate);
                if ($beta <= $alpha) {
                    $this->cutOffs++;
                    break;
                }
            }
            return [$maxRate, $minSteps + 1];
        } else {
            $minRate = INF;
            $minSteps = 0;
            foreach ($possibleMoves as $move) {
                [$rate, $steps] = $this->minimax($move, $depth - 1, $alpha, $beta);
                if ($rate < $minRate || ($rate === $minRate && $steps < $minSteps)) {
                    $minRate = $rate;
                    $minSteps = $steps;
                }
                $beta = min($beta, $rate);
                if ($beta <= $alpha) {
                    $this->cutOffs++;
                    break;
                }
            }
            return [$minRate, $minSteps + 1];
        }
    }

    private function value(int $piece): float
    {
        return match ($piece) {
            Piece::EMPTY => 0,
            Piece::WHITE_MAN => 1,
            Piece::BLACK_MAN => -1,
            Piece::WHITE_KING => self::KING_VALUE,
            Piece::BLACK_KING => -self::KING_VALUE,
            default => throw new \InvalidArgumentException("Invalid piece"),
        };
    }

    private function evaluateBoard(Board $board): float
    {
        $sum = 0.0;
        if ($board->isWhiteTurn()) {
            for ($i = 0; $i < 32; $i++) {
                $sum += $this->value($board->get($i));
            }
        } else {
            for ($i = 31; $i >= 0; $i--) {
                $sum += -$this->value($board->get($i));
            }
        }

        return $board->isBlackTurn() ? -$sum : $sum;
    }
}
