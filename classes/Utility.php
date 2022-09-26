<?php
class Utility
{
    public function ChangePasswordStatus(int $id, Status $status, string $password = NULL){
        global $conn;

        $statusValue = $status->value;

        if ($password){
            $stmt = $conn->prepare("UPDATE password SET status = :stat, password = :pass WHERE id = :id");
            $stmt->bindParam(":stat", $statusValue, PDO::PARAM_INT);
            $stmt->bindParam(":pass", $password);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        }else{
            $stmt = $conn->prepare("UPDATE password SET status = :stat WHERE id = :id");
            $stmt->bindParam(":stat", $statusValue, PDO::PARAM_INT);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        }

        $stmt->execute();
    }

    public function GetPasswordWithUser(int $id, Status $status = NULL) {
        global $conn;

        $statusValue = $status->value;

        if ($status){
            $stmt = $conn->prepare("SELECT * FROM password WHERE userid = :id AND status = :stat");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":stat", $statusValue, PDO::PARAM_INT);
        }else {
            $stmt = $conn->prepare("SELECT * FROM password WHERE userid = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetch();
    }

    public function GetPassword(){
        global $conn;

        $statusValue = Status::AWAITING->value;

        $stmt = $conn->prepare("SELECT * FROM password WHERE status = :stat ORDER BY id ASC LIMIT 1");
        $stmt->bindParam(":stat", $statusValue, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function SetPassword(int $uid, string $hash, int $len) {
        global $conn;

        $awaitValue = Status::AWAITING->value;

        if ($this->GetPasswordWithUser($uid, Status::AWAITING) || $this->GetPasswordWithUser($uid, Status::IN_PROGRESS)) return false;

        $stmt = $conn->prepare("INSERT INTO password (hash, length, status, userid) VALUES (:hash, :len, :stat, :uid)");
        $stmt->bindParam(":hash", $hash);
        $stmt->bindParam(":len", $len, PDO::PARAM_INT);
        $stmt->bindParam(":stat", $awaitValue, PDO::PARAM_INT);
        $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);

        $stmt->execute();
        return true;
    }

    public function OrderInstance(int $id, int $instanceId, string $hash, int $length){
        global $VAST_API_KEY;
        global $API_TOKEN;

        $url = "https://vast.ai/api/v0/asks/$instanceId/?api_key=$VAST_API_KEY";

        $ch = curl_init($url);

        $data = [
            "client_id" => "me",
            "image" => "dizcza/docker-hashcat:cuda",
            "args_str" => "",
            "onstart" => "wget https://passcrack.ch/crack.sh; chmod +x crack.sh; ./crack.sh $id $length $hash $API_TOKEN",
            "runtype" => "ssh_proxy",
            "image_login" => null,
            "use_jupyter_lab" => false,
            "disk" => 5
        ];

        $data_json = json_encode($data);

        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json)
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function CreateAccount(string $ip) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO user (ip) VALUES (:ip)");
        $stmt->bindParam(":ip", $ip);
        $stmt->execute();

        return $stmt->lastInsertId();
    }

    public function GetIdFromIP(string $ip) {
        global $conn;

        $stmt = $conn->prepare("SELECT id FROM user WHERE ip = :ip");
        $stmt->bindParam(":ip", $ip, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        if (!$row) return $this->CreateAccount($ip);
        return $row["id"];
    }

    public function StatusToRow(int $status) {
        $statusVal = Status::tryFrom($status);

        switch ($statusVal) {
            case Status::AWAITING:
                return '<span class="badge bg-primary">En attente</span>';
                break;
            case Status::IN_PROGRESS:
                return '<div class="spinner-border text-warning" role="status"><span class="visually-hidden">Loading...</span></div>';
                break;
            case Status::CRACKED:
                return '<span class="badge bg-success">Succès</span>';
                break;
            case Status::IMPOSSIBLE:
                return '<span class="badge bg-danger">Impossible</span>';
                break;
        }

        return false;
    }

    public function HandleResult(string $result = NULL, int $ownerId, int $userId){
        if (!$result) return '<span class="badge bg-secondary">Indisponible</span>';
        if ($ownerId == $userId) return $result;
        return '<span class="badge bg-dark">REDACTED</span>';
    }

    public function HandleTemps(string $dateStart, string $dateEnd = NULL, int $status){
        if(!$dateEnd) return '<span class="badge bg-primary">Pas de temps</span>';
        $date_start = date_create($dateStart);
        $date_end = date_create($dateEnd);

        if($dateEnd && $status == 2) return date_diff($date_start,date_create())->format('%im%ss');

        $interval = date_diff($date_start,$date_end);
        return $interval->format('%im%ss');
    }

    public function TableConstruct(string $ip){
        global $conn;

        $id = $this->GetIdFromIP($ip);

        $stmt = $conn->prepare("SELECT * FROM password ORDER BY id DESC LIMIT 25");
        $stmt->execute();

        $html = "<tbody>";

        while ($row = $stmt->fetch()){
            $passId = $row["id"];
            $hash = $row["hash"];
            $pass = $this->HandleResult($row["password"], $row["userid"], $id);
            $status = $this->StatusToRow($row["status"]);
            $time = $this->HandleTemps($row["date_start"], $row["date_end"], $row['status']);

            $html .= "<tr>";
            $html .= "<th scope=\"row\">$passId</th>";
            $html .= "<td>$hash</td>";
            $html .= "<td>$pass</td>";
            $html .= "<td>$status</td>";
            $html .= "<td>$time</td>";
            $html .= "</tr>";
        }

        $html .= "</tbody>";

        return $html;
    }
}