<?php

namespace App\Command;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:react-server',
    description: 'send sse-events to a http client',
)]
class ReactServerCommand extends Command
{
    private const REACT_SERVER_URI = "0.0.0.0:8080" ;

    public function __construct(private readonly LoggerInterface $logger)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info(sprintf('Server running on %s',self::REACT_SERVER_URI));

        $http = new HttpServer(function (ServerRequestInterface $request) {

            $random_string = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));

            $data = [
                'message' => $random_string,
                'name' => 'Sadhan Sarker',
                'time' => date('h:i:s'),
                'id' => rand(10, 100),
            ];

            $headers = [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Access-Control-Allow-Origin' => '*'
            ];

            return new Response(200, $headers, 'data: ' . json_encode($data) . "\n\n");

        });

        $socket = new SocketServer(self::REACT_SERVER_URI);
        $http->listen($socket);

        return Command::SUCCESS;
    }
}
