<?php

declare(strict_types=1);

/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Component\Checkers;

class Piece
{
    public const EMPTY = 0b000;
    public const WHITE_MAN = 0b100;
    public const BLACK_MAN = 0b101;
    public const WHITE_KING = 0b110;
    public const BLACK_KING = 0b111;
}

class Direction
{
    public const UP_LEFT = 0;
    public const UP_RIGHT = 1;
    public const DOWN_LEFT = 2;
    public const DOWN_RIGHT = 3;
}

function isWhite(int $piece): bool
{
    return $piece === Piece::WHITE_MAN || $piece === Piece::WHITE_KING;
}

function isBlack(int $piece): bool
{
    return $piece === Piece::BLACK_MAN || $piece === Piece::BLACK_KING;
}

function isMan(int $piece): bool
{
    return $piece === Piece::WHITE_MAN || $piece === Piece::BLACK_MAN;
}

function isKing(int $piece): bool
{
    return $piece === Piece::WHITE_KING || $piece === Piece::BLACK_KING;
}

class Position
{
    public const END = 241187; // Represents an invalid position.

    public static function toString(int $position): string
    {
        return match ($position) {
            0 => "b8",
            1 => "d8",
            2 => "f8",
            3 => "h8",
            4 => "a7",
            5 => "c7",
            6 => "e7",
            7 => "g7",
            8 => "b6",
            9 => "d6",
            10 => "f6",
            11 => "h6",
            12 => "a5",
            13 => "c5",
            14 => "e5",
            15 => "g5",
            16 => "b4",
            17 => "d4",
            18 => "f4",
            19 => "h4",
            20 => "a3",
            21 => "c3",
            22 => "e3",
            23 => "g3",
            24 => "b2",
            25 => "d2",
            26 => "f2",
            27 => "h2",
            28 => "a1",
            29 => "c1",
            30 => "e1",
            31 => "g1",
            default => throw new \InvalidArgumentException("Invalid position: $position"),
        };
    }

    public static function parse(string $position): int
    {
        return match ($position) {
            "b8" => 0,
            "d8" => 1,
            "f8" => 2,
            "h8" => 3,
            "a7" => 4,
            "c7" => 5,
            "e7" => 6,
            "g7" => 7,
            "b6" => 8,
            "d6" => 9,
            "f6" => 10,
            "h6" => 11,
            "a5" => 12,
            "c5" => 13,
            "e5" => 14,
            "g5" => 15,
            "b4" => 16,
            "d4" => 17,
            "f4" => 18,
            "h4" => 19,
            "a3" => 20,
            "c3" => 21,
            "e3" => 22,
            "g3" => 23,
            "b2" => 24,
            "d2" => 25,
            "f2" => 26,
            "h2" => 27,
            "a1" => 28,
            "c1" => 29,
            "e1" => 30,
            "g1" => 31,
            default => throw new \InvalidArgumentException("Invalid position: $position"),
        };
    }
}

function gotoDir(int $position, int $direction): int
{
    return match ($direction) {
        Direction::UP_LEFT => match ($position) {
            0, 1, 2, 3, 4 => Position::END,
            5 => 0,
            6 => 1,
            7 => 2,
            8 => 4,
            9 => 5,
            10 => 6,
            11 => 7,
            13 => 8,
            14 => 9,
            15 => 10,
            16 => 12,
            17 => 13,
            18 => 14,
            19 => 15,
            21 => 16,
            22 => 17,
            23 => 18,
            24 => 20,
            25 => 21,
            26 => 22,
            27 => 23,
            29 => 24,
            30 => 25,
            31 => 26,
            default => Position::END,
        },
        Direction::UP_RIGHT => match ($position) {
            0, 1, 2, 3 => Position::END,
            4 => 0,
            5 => 1,
            6 => 2,
            7 => 3,
            8 => 5,
            9 => 6,
            10 => 7,
            12 => 8,
            13 => 9,
            14 => 10,
            15 => 11,
            16 => 13,
            17 => 14,
            18 => 15,
            20 => 16,
            21 => 17,
            22 => 18,
            23 => 19,
            24 => 21,
            25 => 22,
            26 => 23,
            28 => 24,
            29 => 25,
            30 => 26,
            31 => 27,
            default => Position::END,
        },
        Direction::DOWN_LEFT => match ($position) {
            0 => 4,
            1 => 5,
            2 => 6,
            3 => 7,
            5 => 8,
            6 => 9,
            7 => 10,
            8 => 12,
            9 => 13,
            10 => 14,
            11 => 15,
            13 => 16,
            14 => 17,
            15 => 18,
            16 => 20,
            17 => 21,
            18 => 22,
            19 => 23,
            21 => 24,
            22 => 25,
            23 => 26,
            24 => 28,
            25 => 29,
            26 => 30,
            27 => 31,
            default => Position::END,
        },
        Direction::DOWN_RIGHT => match ($position) {
            0 => 5,
            1 => 6,
            2 => 7,
            4 => 8,
            5 => 9,
            6 => 10,
            7 => 11,
            8 => 13,
            9 => 14,
            10 => 15,
            12 => 16,
            13 => 17,
            14 => 18,
            15 => 19,
            16 => 21,
            17 => 22,
            18 => 23,
            20 => 24,
            21 => 25,
            22 => 26,
            23 => 27,
            24 => 29,
            25 => 30,
            26 => 31,
            default => Position::END,
        },
        default => throw new \InvalidArgumentException("Invalid direction: $direction"),
    };
}

class Board
{
    private int $u;
    private int $v;

    public function __construct(int $u = 49085340525, int $v = 160842843832320)
    {
        $this->u = $u;
        $this->v = $v;
    }

    public function bits(): array
    {
        return [$this->u, $this->v];
    }

    public function bitsString(): string
    {
        $turn = $this->isBlackTurn() ? 'b' : 'w';
        return sprintf("%c               g5 e5 c5 a5 h6 f6 d6 b6 g7 e7 c7 a7 h8 f8 d8 b8\n%064b\n\n%-4d            g1 e1 c1 a1 h2 f2 d2 b2 g3 e3 c3 a3 h4 f4 d4 b4\n%064b",
            $turn, $this->u, $this->onlyKingMoves(), $this->v);
    }

    public function __toString(): string
    {
        $output = "  a b c d e f g h\n8";
        for ($i = 0; $i < 64; $i++) {
            if ($i % 8 === 0 && $i !== 0) {
                $output .= sprintf(" %d\n%d", 9 - intdiv($i, 8), 8 - intdiv($i, 8));
            }
            $x = $i % 8;
            $y = intdiv($i, 8);
            if (($y % 2 === 0 && $x % 2 === 1) || ($y % 2 === 1 && $x % 2 === 0)) {
                $k = $this->get(Position::parse(Position::toString(intdiv($i, 2))));
                $output .= match ($k) {
                    Piece::EMPTY => " .",
                    Piece::WHITE_MAN => " o",
                    Piece::BLACK_MAN => " x",
                    Piece::WHITE_KING => " O",
                    Piece::BLACK_KING => " X",
                    default => " ?",
                };
            } else {
                $output .= "  ";
            }
        }
        return $output . " 1\n  a b c d e f g h  ";
    }

    public function get(int $position): int
    {
        if ($position < 0 || $position > 31) {
            throw new \InvalidArgumentException("Invalid position: $position");
        }
        return $position < 16 ? ($this->u >> ($position * 3)) & 0b111 : ($this->v >> (($position - 16) * 3)) & 0b111;
    }

    public function set(int $position, int $piece): self
    {
        $b = clone $this;
        if ($position < 0 || $position > 31) {
            throw new \InvalidArgumentException("Invalid position: $position");
        }
        if ($position < 16) {
            $mask = ~(0b111 << ($position * 3));
            $b->u = ($b->u & $mask) | ($piece << ($position * 3));
        } else {
            $mask = ~(0b111 << (($position - 16) * 3));
            $b->v = ($b->v & $mask) | ($piece << (($position - 16) * 3));
        }
        return $b;
    }

    public function onlyKingMoves(): int
    {
        return $this->v >> 59;
    }

    public function inc(): self
    {
        $this->v += 1 << 59;
        return $this;
    }

    public function setOnlyKingMoves(int $n): self
    {
        if ($n < 0 || $n > 30) {
            throw new \InvalidArgumentException("Invalid number of only king moves: $n");
        }
        $mask = ~(0b11111 << 59);
        $this->v = ($this->v & $mask) | ($n << 59);
        return $this;
    }

    public function zero(): self
    {
        $this->v &= ~(0b11111 << 59);
        return $this;
    }

    public function isDraw(): bool
    {
        return $this->onlyKingMoves() > 30;
    }

    public function turn(bool $kingMove): self
    {
        $b = clone $this;
        $b->u ^= 1 << 63;
        return $kingMove ? $b->inc() : $b->zero();
    }

    public function transpose(): self
    {
        $transposed = new self();
        for ($i = 31; $i >= 0; $i--) {
            $transposed->set(31 - $i, match ($this->get($i)) {
                Piece::EMPTY => Piece::EMPTY,
                Piece::WHITE_MAN => Piece::BLACK_MAN,
                Piece::BLACK_MAN => Piece::WHITE_MAN,
                Piece::WHITE_KING => Piece::BLACK_KING,
                Piece::BLACK_KING => Piece::WHITE_KING,
                default => throw new \InvalidArgumentException("Invalid piece"),
            });
        }
        if ($this->isWhiteTurn()) {
            $transposed = $transposed->turn(false);
        }
        $transposed->setOnlyKingMoves($this->onlyKingMoves());
        return $transposed;
    }

    public function turnString(): string
    {
        return $this->isWhiteTurn() ? 'white' : 'black';
    }

    public function isWhiteTurn(): bool
    {
        return ($this->u & (1 << 63)) === 0;
    }

    public function isBlackTurn(): bool
    {
        return ($this->u & (1 << 63)) !== 0;
    }

    public function isEnemy(int $position): bool
    {
        return $this->isWhiteTurn() ? isBlack($this->get($position)) : isWhite($this->get($position));
    }

    public function isEmpty(int $position): bool
    {
        return $this->get($position) === Piece::EMPTY;
    }

    public function allMoves(): array
    {
        [$moves,] = $this->allMovesWithFlag();
        return $moves;
    }

    public function allMovesWithFlag(): array
    {
        $man = $this->isBlackTurn() ? Piece::BLACK_MAN : Piece::WHITE_MAN;
        $king = $this->isBlackTurn() ? Piece::BLACK_KING : Piece::WHITE_KING;

        $moves = [];
        $eatMoves = [];

        for ($i = 0; $i < 32; $i++) {
            if ($this->get($i) === $man) {
                $this->manMoves($moves, $i);
                $this->manEats($eatMoves, $i, 0);
            } elseif ($this->get($i) === $king) {
                $this->kingMoves($moves, $i);
                $this->kingEats($eatMoves, $i, 0);
            }
        }

        return count($eatMoves) > 0 ? [$eatMoves, true] : [$moves, false];
    }

    private function manMoves(array &$moves, int $from): void
    {
        $piece = $this->get($from);
        if ($piece === Piece::WHITE_MAN) {
            $this->manMovesDir($moves, $from, Direction::UP_LEFT);
            $this->manMovesDir($moves, $from, Direction::UP_RIGHT);
        } elseif ($piece === Piece::BLACK_MAN) {
            $this->manMovesDir($moves, $from, Direction::DOWN_LEFT);
            $this->manMovesDir($moves, $from, Direction::DOWN_RIGHT);
        } else {
            throw new \InvalidArgumentException("Invalid piece");
        }
    }

    private function manMovesDir(array &$moves, int $from, int $direction): void
    {
        $piece = $this->get($from);
        $to = gotoDir($from, $direction);
        if ($to !== Position::END && $this->isEmpty($to)) {
            $as = $piece;
            if ($piece === Piece::WHITE_MAN && $to < 4) {
                $as = Piece::WHITE_KING;
            } elseif ($piece === Piece::BLACK_MAN && $to > 27) {
                $as = Piece::BLACK_KING;
            }
            $move = $this->turn(false)->set($from, Piece::EMPTY)->set($to, $as);
            $moves[] = $move;
        }
    }

    private function kingMoves(array &$moves, int $from): void
    {
        $piece = $this->get($from);
        if (!isKing($piece)) {
            throw new \InvalidArgumentException("Invalid piece");
        }
        $this->kingMovesDir($moves, $from, Direction::UP_LEFT);
        $this->kingMovesDir($moves, $from, Direction::UP_RIGHT);
        $this->kingMovesDir($moves, $from, Direction::DOWN_LEFT);
        $this->kingMovesDir($moves, $from, Direction::DOWN_RIGHT);
    }

    private function kingMovesDir(array &$moves, int $from, int $direction): void
    {
        $piece = $this->get($from);
        $to = gotoDir($from, $direction);
        while ($to !== Position::END && $this->isEmpty($to)) {
            $move = $this->turn(true)->set($from, Piece::EMPTY)->set($to, $piece);
            $moves[] = $move;
            $to = gotoDir($to, $direction);
        }
    }

    private function manEats(array &$moves, int $from, int $eaten): bool
    {
        $moreMoves = false;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::UP_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::UP_RIGHT, $eaten) || $moreMoves;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::DOWN_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::DOWN_RIGHT, $eaten) || $moreMoves;
        return $moreMoves;
    }

    private function manEatsDir(array &$moves, int $from, int $direction, int $eaten): bool
    {
        $piece = $this->get($from);
        if (!isMan($piece)) {
            throw new \InvalidArgumentException("Invalid piece");
        }
        $enemy = gotoDir($from, $direction);
        if ($enemy !== Position::END && $this->isEnemy($enemy)) {
            $to = gotoDir($enemy, $direction);
            if ($to !== Position::END && $this->isEmpty($to)) {
                $as = $piece;
                if ($piece === Piece::WHITE_MAN && $to < 4) {
                    $as = Piece::WHITE_KING;
                } elseif ($piece === Piece::BLACK_MAN && $to > 27) {
                    $as = Piece::BLACK_KING;
                }
                $move = $this->set($from, Piece::EMPTY)->set($enemy, Piece::EMPTY)->set($to, $as);
                $eaten |= 1 << $enemy; // Mark enemy as eaten.
                $hasMoreMoves = isKing($as) ? $move->kingEats($moves, $to, $eaten) : $move->manEats($moves, $to, $eaten);
                if (!$hasMoreMoves) {
                    $moves[] = $move->turn(false);
                }
                return true;
            }
        }
        return false;
    }

    private function kingEats(array &$moves, int $from, int $eaten): bool
    {
        $moreMoves = false;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::UP_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::UP_RIGHT, $eaten) || $moreMoves;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::DOWN_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::DOWN_RIGHT, $eaten) || $moreMoves;
        return $moreMoves;
    }

    private function kingEatsDir(array &$moves, int $from, int $direction, int $eaten): bool
    {
        $piece = $this->get($from);
        if (!isKing($piece)) {
            throw new \InvalidArgumentException("Invalid piece");
        }
        $enemy = gotoDir($from, $direction);
        while ($enemy !== Position::END && $this->isEmpty($enemy) && ($eaten & (1 << $enemy)) === 0) {
            $enemy = gotoDir($enemy, $direction);
        }
        if ($enemy !== Position::END && $this->isEnemy($enemy)) {
            $to = gotoDir($enemy, $direction);
            while ($to !== Position::END && $this->isEmpty($to)) {
                $move = $this->set($from, Piece::EMPTY)->set($enemy, Piece::EMPTY)->set($to, $piece);
                $eaten |= 1 << $enemy; // Mark enemy as eaten.
                $hasMoreMoves = $move->kingEats($moves, $to, $eaten);
                if (!$hasMoreMoves) {
                    $moves[] = $move->turn(false);
                }
                $to = gotoDir($to, $direction);
            }
            return true;
        }
        return false;
    }

    public function generateMoveName(Board $target): string
    {
        $move = new Move($this);
        foreach ($move->allMoves() as $m) {
            if ($m->board == $target) {
                return $m->name;
            }
        }
        throw new \RuntimeException("Move not found");
    }
}

class Move
{
    public Board $board;
    public string $name;

    public function __construct(Board $board, string $name = '')
    {
        $this->board = $board;
        $this->name = $name;
    }

    public function turn(bool $kingMove): Move
    {
        return new Move($this->board->turn($kingMove), $this->name);
    }

    /**
     * @return Move[]
     */
    public function allMoves(): array
    {
        $man = $this->board->isBlackTurn() ? Piece::BLACK_MAN : Piece::WHITE_MAN;
        $king = $this->board->isBlackTurn() ? Piece::BLACK_KING : Piece::WHITE_KING;

        $moves = [];
        $eatMoves = [];

        for ($i = 0; $i < 32; $i++) {
            if ($this->board->get($i) === $man) {
                $this->name = Position::toString($i);
                $this->manMoves($moves, $i);
                $this->manEats($eatMoves, $i, 0);
            } elseif ($this->board->get($i) === $king) {
                $this->name = Position::toString($i);
                $this->kingMoves($moves, $i);
                $this->kingEats($eatMoves, $i, 0);
            }
        }

        return count($eatMoves) > 0 ? $eatMoves : $moves;
    }

    private function manMoves(array &$moves, int $from): void
    {
        $piece = $this->board->get($from);
        if ($piece === Piece::WHITE_MAN) {
            $this->manMovesDir($moves, $from, Direction::UP_LEFT);
            $this->manMovesDir($moves, $from, Direction::UP_RIGHT);
        } elseif ($piece === Piece::BLACK_MAN) {
            $this->manMovesDir($moves, $from, Direction::DOWN_LEFT);
            $this->manMovesDir($moves, $from, Direction::DOWN_RIGHT);
        } else {
            throw new \InvalidArgumentException("Invalid piece");
        }
    }

    private function manMovesDir(array &$moves, int $from, int $direction): void
    {
        $piece = $this->board->get($from);
        $to = gotoDir($from, $direction);
        if ($to !== Position::END && $this->board->isEmpty($to)) {
            $as = $piece;
            if ($piece === Piece::WHITE_MAN && $to < 4) {
                $as = Piece::WHITE_KING;
            } elseif ($piece === Piece::BLACK_MAN && $to > 27) {
                $as = Piece::BLACK_KING;
            }
            $move = new Move($this->board->turn(false)->set($from, Piece::EMPTY)->set($to, $as), sprintf("%s-%s", Position::toString($from), Position::toString($to)));
            $moves[] = $move;
        }
    }

    private function kingMoves(array &$moves, int $from): void
    {
        $piece = $this->board->get($from);
        if (!isKing($piece)) {
            throw new \InvalidArgumentException("Invalid piece");
        }
        $this->kingMovesDir($moves, $from, Direction::UP_LEFT);
        $this->kingMovesDir($moves, $from, Direction::UP_RIGHT);
        $this->kingMovesDir($moves, $from, Direction::DOWN_LEFT);
        $this->kingMovesDir($moves, $from, Direction::DOWN_RIGHT);
    }

    private function kingMovesDir(array &$moves, int $from, int $direction): void
    {
        $piece = $this->board->get($from);
        $to = gotoDir($from, $direction);
        while ($to !== Position::END && $this->board->isEmpty($to)) {
            $move = new Move($this->board->turn(true)->set($from, Piece::EMPTY)->set($to, $piece), sprintf("%s-%s", Position::toString($from), Position::toString($to)));
            $moves[] = $move;
            $to = gotoDir($to, $direction);
        }
    }

    private function manEats(array &$moves, int $from, int $eaten): bool
    {
        $moreMoves = false;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::UP_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::UP_RIGHT, $eaten) || $moreMoves;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::DOWN_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->manEatsDir($moves, $from, Direction::DOWN_RIGHT, $eaten) || $moreMoves;
        return $moreMoves;
    }

    private function manEatsDir(array &$moves, int $from, int $direction, int $eaten): bool
    {
        $piece = $this->board->get($from);
        if (!isMan($piece)) {
            throw new \InvalidArgumentException("Invalid piece");
        }
        $enemy = gotoDir($from, $direction);
        if ($enemy !== Position::END && $this->board->isEnemy($enemy)) {
            $to = gotoDir($enemy, $direction);
            if ($to !== Position::END && $this->board->isEmpty($to)) {
                $as = $piece;
                if ($piece === Piece::WHITE_MAN && $to < 4) {
                    $as = Piece::WHITE_KING;
                } elseif ($piece === Piece::BLACK_MAN && $to > 27) {
                    $as = Piece::BLACK_KING;
                }
                $move = new Move($this->board->set($from, Piece::EMPTY)->set($enemy, Piece::EMPTY)->set($to, $as), sprintf("%s:%s", $this->name, Position::toString($to)));
                $eaten |= 1 << $enemy; // Mark enemy as eaten.
                $hasMoreMoves = isKing($as) ? $move->kingEats($moves, $to, $eaten) : $move->manEats($moves, $to, $eaten);
                if (!$hasMoreMoves) {
                    $moves[] = $move->turn(false);
                }
                return true;
            }
        }
        return false;
    }

    private function kingEats(array &$moves, int $from, int $eaten): bool
    {
        $moreMoves = false;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::UP_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::UP_RIGHT, $eaten) || $moreMoves;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::DOWN_LEFT, $eaten) || $moreMoves;
        $moreMoves = $this->kingEatsDir($moves, $from, Direction::DOWN_RIGHT, $eaten) || $moreMoves;
        return $moreMoves;
    }

    private function kingEatsDir(array &$moves, int $from, int $direction, int $eaten): bool
    {
        $piece = $this->board->get($from);
        if (!isKing($piece)) {
            throw new \InvalidArgumentException("Invalid piece");
        }
        $enemy = gotoDir($from, $direction);
        while ($enemy !== Position::END && $this->board->isEmpty($enemy) && ($eaten & (1 << $enemy)) === 0) {
            $enemy = gotoDir($enemy, $direction);
        }
        if ($enemy !== Position::END && $this->board->isEnemy($enemy)) {
            $to = gotoDir($enemy, $direction);
            while ($to !== Position::END && $this->board->isEmpty($to)) {
                $move = new Move($this->board->set($from, Piece::EMPTY)->set($enemy, Piece::EMPTY)->set($to, $piece), sprintf("%s:%s", $this->name, Position::toString($to)));
                $eaten |= 1 << $enemy; // Mark enemy as eaten.
                $hasMoreMoves = $move->kingEats($moves, $to, $eaten);
                if (!$hasMoreMoves) {
                    $moves[] = $move->turn(false);
                }
                $to = gotoDir($to, $direction);
            }
            return true;
        }
        return false;
    }
}
