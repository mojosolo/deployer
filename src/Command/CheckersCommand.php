<?php

declare(strict_types=1);

/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Command;

use Deployer\Component\Checkers\Board;
use Deployer\Component\Checkers\Minimax;
use Deployer\Component\Checkers\Move;
use Deployer\Deployer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\Console\Question\ChoiceQuestion;

class CheckersCommand extends Command
{
    use CommandCommon;

    /**
     * @var Input
     */
    private $input;

    /**
     * @var Output
     */
    private $output;

    public function __construct()
    {
        parent::__construct('checkers');
        $this->setDescription('Play checkers');
    }

    protected function execute(Input $input, Output $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->telemetry();

        $board = new Board();
        $minimax = new Minimax(6);


        turn:
        $output->writeln("\033[2J\033[H");
        $output->writeln("$board");

        $move = new Move($board);
        $moves = $move->allMoves();
        $autocomplete = array_map(fn(Move $move) => $move->name, $moves);

        if (empty($moves)) {
            if ($board->isWhiteTurn()) {
                echo "Black wins\n";
                return 0;
            } else {
                echo "White wins\n";
                return 0;
            }
        }
        if ($board->isDraw()) {
            echo "Draw\n";
            return 0;
        }

        /** @var QuestionHelper */
        $helper = Deployer::get()->getHelper('question');

        $question = new ChoiceQuestion("<question>You move:</question> ", $autocomplete, 0);
        $chosenMove = $helper->ask($input, $output, $question);

        foreach ($moves as $move) {
            if ($chosenMove == $move->name) {
                $board = $move->board;
                goto opponent;
            }
        }

        throw new \RuntimeException("Move not found");

        opponent:
        $output->writeln("\033[2J\033[H");
        $output->writeln("$board");
        $output->writeln("");
        $output->write("Thinking...");
        [$response,] = $minimax->bestRandomMove($board, false);
        $moveName = $board->generateMoveName($response);
        $output->writeln("$moveName!");
        $output->writeln("");
        $board = $response;
        goto turn;
    }

    protected function autoplay()
    {
        $board = new Board();
        $player = new Minimax(4);
        $opponent = new Minimax(4);
        $debug = true;

        $moveNumber = 0;
        while (true) {
            echo $board . "\n\n";

            $moveNumber++;
            $moves = $board->allMoves();

            if (empty($moves)) {
                if ($board->isWhiteTurn()) {
                    echo "Black wins\n";
                    return 0;
                } else {
                    echo "White wins\n";
                    return 0;
                }
            }

            if ($board->isDraw()) {
                echo "Draw\n";
                return 0;
            }

            echo "Move $moveNumber\n";

            if ($board->isWhiteTurn()) {
                [$move, $rate] = $player->bestRandomMove($board, $debug);
            } else {
                [$move, $rate] = $opponent->bestRandomMove($board, $debug);
            }

            echo "Best " . $board->turnString() . " move: " . $board->generateMoveName($move) . " (" . $rate . ")\n";

            $board = $move;
        }
    }
}
