<?php
require __DIR__.'/../vendor/autoload.php';

$loop = React\EventLoop\Loop::get();
$socket = new React\Socket\SocketServer('0.0.0.0:8080', [], $loop);

$connections = new \SplObjectStorage();

$socket->on('connection', function (React\Socket\ConnectionInterface $connection) use ($connections) {
    $connections->attach($connection);
    
    $connection->on('data', function ($data) use ($connections, $connection) {
        $message = json_encode([
            'message' => trim($data),
            'time' => date('H:i:s')
        ]);
        
        foreach ($connections as $conn) {
            if ($conn !== $connection) {
                $conn->write($message . PHP_EOL);
            }
        }
    });
    
    $connection->on('close', function () use ($connections, $connection) {
        $connections->detach($connection);
    });
});

echo "WebSocket server running on ws://0.0.0.0:8080\n";
$loop->run();
