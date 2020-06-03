<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatService implements MessageComponentInterface {
    protected $clients;
    private $debug = null;
    private $info = null;
    protected $error = null;
    private $dbh = null;
    protected $subscriptions = [];
    protected $users = [];
    
    public function __construct() {
        $this->clients = new \SplObjectStorage ();
        $this->debug = new \strangerfw\utils\Logger('DEBUG');
        $this->info = new \strangerfw\utils\Logger('INFO');
        $this->error = new \strangerfw\utils\Logger('ERROR');
    }
    
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach ( $conn );
        $this->users[$conn->resourceId] = $conn;
        // echo "New connection! ({$conn->resourceId})\n";
        $this->debug->log("ChatService::onMessage() New connection! ({$conn->resourceId})");
        // $this->debug->log("ChatService::onOpen() users:".print_r($this->users, true));
    }
    
    public function onMessage(ConnectionInterface $conn, $msg) {
        try{
            $this->debug->log("ChatService::onMessage()");
            // $this->debug->log("ChatService::onMessage() from:".print_r($from, true));
            $this->debug->log("ChatService::onMessage() msg:".print_r($msg, true));
            $session = \strangerfw\core\Session::get();

            // $this->sendSdp($from, $msg);
            $numRecv = count ( $this->clients ) - 1;
            $recv_data = json_decode($msg);

            $this->debug->log("ChatService::onMessage() recv_data:".print_r($recv_data, true));

            // $this->debug->log("ChatService::onMessage() meeting:" . print_r($meeting, true));
            $this->debug->log("ChatService::onMessage() CH-01" );
            $numRecv = count ( $this->clients ) - 1;
            $this->debug->log("ChatService::onMessage() command:".$recv_data->command );

            switch ($recv_data->command) {
                case "subscribe":
                    $this->debug->log("ChatService::onMessage() subscribe" );
                    $this->debug->log("ChatService::onMessage() resourceId:" . $conn->resourceId);
                    $this->debug->log("ChatService::onMessage() meeting_id:" . $conn->meeting_id);
                    $this->subscriptions[$conn->resourceId] = $recv_data->meeting_id;
                    $this->debug->log("ChatService::onMessage() subscriptions(1):" . print_r($this->subscriptions, true));
                    $this->users[$conn->resourceId]->send("CONNECTED");
                    break;
                case "message":
                    $conf = \strangerfw\core\Config::get('database.config');
                    $database = $conf['default_database'];
                    $dbConnect = new \strangerfw\utils\DbConnect();
                    $dbConnect->setConnectionInfo($database);
                    $dbh = $dbConnect->createConnection();
                    $meetings = new \Meeting($dbh);

                    $data = $meetings->contain([
                        'MeetingRtcSession' => [
                          'RtcSession' => ['User'],
                        ],
                    ])
                    ->select([
                    'Meeting' => [
                      'id',
                      'title',
                      'meeting_hash_key',
                      'password',
                      'last_connected_at'
                    ],
                    'MeetingRtcSession' => [
                      'meeting_id',
                      'rtc_session_id'
                    ],
                    'RtcSession' => [
                      'sdp',
                      'status'
                    ],
                    'User' => [
                        'username'
                    ]
                    ])
                    ->where('Meeting.id', '=', $recv_data->meeting_id)
                    ->where('User.username', '=', $recv_data->username)
                    ->find('first');

                    $message = [
                        'resourceId' => $conn->resourceId ,
                        'meeting_id' => $recv_data->meeting_id ,
                        'sdp' => $data['RtcSession']['sdp'] ,
                        'username' => $recv_data->username ,
                        'numRecv' => $numRecv ,
                    ];

                    if (isset($this->subscriptions[$conn->resourceId])) {
                        $target = $this->subscriptions[$conn->resourceId];
                        foreach ($this->subscriptions as $id => $meeting_id) {
                            if ($meeting_id == $target && $id != $conn->resourceId) {
                                $this->debug->log("ChatService::onMessage() user_id[${id}] sdp:" . $data['RtcSession']['sdp']);
                                $this->users[$id]->send(json_encode($message, JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
            }
            $this->debug->log("ChatService::onMessage() END" );
        } catch (\PDOException $e) {
            $this->error->log("ChatService::onMessage() error:" . $e->getMessage());
        } catch (\Exception $e) {
            $this->error->log("ChatService::onMessage() error:" . $e->getMessage());
        }
    }
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach ( $conn );
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);
        $this->debug->log("ChatService::onClose()");
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $this->debug->log("ChatService::onError()");
        $conn->close ();
    }
}
