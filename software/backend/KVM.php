<?php
use ProxmoxVE\Proxmox;
use phpseclib\Net\SSH2;

$kvm = new KVM();
class KVM extends Controller
{

    public function getServerCredentials($nodeID)
    {

        $getServerCredentials = self::db()->prepare("SELECT * FROM `kvm_servers_nodes` WHERE `id` = :nodeID");
        $getServerCredentials->execute(array(":nodeID" => $nodeID));
        $credentials = $getServerCredentials->fetch(PDO::FETCH_ASSOC);

        return $serverCredentials = [ 'hostname' => $credentials['hostname'], 'username' => $credentials['username'], 'password' => $credentials['password'], 'realm' => $credentials['realm'] ];
    }

    public function getNodeStats($nodeID)
    {
        $proxmoxVE = new Proxmox(($this->getServerCredentials($nodeID)));
        $proxmoxVE->setResponseType('json');
        $response = $proxmoxVE->get('/nodes');

        return $response;
    }

    public function getPVE($nodeID)
    {

        $getServerCredentials = self::db()->prepare("SELECT * FROM `kvm_servers_nodes` WHERE `id` = :nodeID");
        $getServerCredentials->execute(array(":nodeID" => $nodeID));
        $credentials = $getServerCredentials->fetch(PDO::FETCH_ASSOC);

        $nodeName = $credentials['name'];

        return '/nodes/'.$nodeName.'/';
    }

    public function startServer($nodeID, $serverID)
    {

        $status = $this->getStatus($nodeID, $serverID);
        $status = json_decode($status);


        if($status->data->status == 'stopped'){
            $proxmoxVE = new Proxmox(($this->getServerCredentials($nodeID)));
            $proxmoxVE->setResponseType('json');
            $response = $proxmoxVE->create($this->getPVE($nodeID).'qemu/'.$serverID.'/status/start');

            return $response;
        }

        return FALSE;

    }

    public function exec($command, $credentials)
    {
        $ssh = new SSH2($credentials['hostname'], 22);
        if(!$ssh->login('root', $credentials['root_password'])){
            return ('Login Failed');
        }

        return $ssh->exec($command);
    }

    public function correctCores($nodeID, $serverID, $cores)
    {

        $proxmoxVE = new Proxmox($this->getServerCredentials($nodeID));
        $proxmoxVE->setResponseType('json');

        $response = $proxmoxVE->set($this->getPVE($nodeID).'qemu/'.$serverID.'/config/', [
            'sockets' => 1,
            'cores' => $cores,
        ]);
        return $response;

    }

    public function correctMemory($nodeID, $serverID, $memory)
    {

        $proxmoxVE = new Proxmox($this->getServerCredentials($nodeID));
        $proxmoxVE->setResponseType('json');

        $response = $proxmoxVE->set($this->getPVE($nodeID).'qemu/'.$serverID.'/config/', [
            'memory' => $memory,
        ]);
        return $response;

    }

    public function correctDisk($nodeID, $serverID, $disk)
    {

        $proxmoxVE = new Proxmox($this->getServerCredentials($nodeID));
        $proxmoxVE->setResponseType('json');

        $response = $proxmoxVE->set($this->getPVE($nodeID).'qemu/'.$serverID.'/resize/', [
            'size' => $disk.'G',
            'disk' => 'scsi0',
        ]);
        return $response;

    }

    public function stopServer($nodeID, $serverID)
    {

        $status = $this->getStatus($nodeID, $serverID);
        $status = json_decode($status);


        if($status->data->status == 'running'){
            $proxmoxVE = new Proxmox(($this->getServerCredentials($nodeID)));
            $proxmoxVE->setResponseType('json');
            $response = $proxmoxVE->create($this->getPVE($nodeID).'qemu/'.$serverID.'/status/stop');

            return $response;
        }

        return FALSE;

    }

    public function shutdown($nodeID, $serverID)
    {

        $proxmoxVE = new Proxmox(($this->getServerCredentials($nodeID)));
        $proxmoxVE->setResponseType('json');
        $response = $proxmoxVE->create($this->getPVE($nodeID).'qemu/'.$serverID.'/status/shutdown');

        return $response;

    }

    public function deleteServer($nodeID, $serverID){

        $proxmoxVE = new Proxmox($this->getServerCredentials($nodeID));
        $proxmoxVE->setResponseType('json');

        $response = $proxmoxVE->delete($this->getPVE($nodeID).'qemu/'.$serverID);
        return $response;

    }

    public function getStatus($nodeID, $serverID)
    {

        $proxmoxVE = new Proxmox(($this->getServerCredentials($nodeID)));
        $proxmoxVE->setResponseType('json');
        $response = $proxmoxVE->get($this->getPVE($nodeID) . 'qemu/' . $serverID . '/status/current');

        return $response;

    }

}