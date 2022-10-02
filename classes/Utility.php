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

    public function UpdateDate(int $id, string $date = NULL) {
        global $conn;

        $formattedDate = date("Y-m-d H:i:s", $date);

        $stmt = $conn->prepare("UPDATE password SET date_start = :datestart WHERE id = :id");
        $stmt->bindParam(":datestart", $formattedDate);
        $stmt->bindParam(":id", $id);

        $stmt->execute();
    }

    public function SetPassword(int $uid, string $hash, int $len, HashType $type, string $pbkey) {
        global $conn;

        $awaitValue = Status::AWAITING->value;
        $typeValue = $type->value;

        if ($this->GetPasswordWithUser($uid, Status::AWAITING) || $this->GetPasswordWithUser($uid, Status::IN_PROGRESS)) return "already cracking";

        $stmt = $conn->prepare("INSERT INTO password (hash, length, status, userid, hash_type, pbkey) VALUES (:hash, :len, :stat, :uid, :ht, :pbk)");
        $stmt->bindParam(":hash", $hash);
        $stmt->bindParam(":len", $len, PDO::PARAM_INT);
        $stmt->bindParam(":stat", $awaitValue, PDO::PARAM_INT);
        $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);
        $stmt->bindParam(":ht", $typeValue);
        $stmt->bindParam(":pbk", $pbkey);

        $stmt->execute();
        return $conn->lastInsertId();
    }

    public function GetHashcatType(HashType $hashType) {
        switch ($hashType) {
            case HashType::MD5:
                return 0;
                break;
            case HashType::SHA1:
                return 100;
                break;
            case HashType::SHA256:
                return 1400;
                break;
        }

        return false;
    }

    public function GetHashName(HashType $hashType) {
        switch ($hashType) {
            case HashType::MD5:
                return "md5";
                break;
            case HashType::SHA1:
                return "sha1";
                break;
            case HashType::SHA256:
                return "sha256";
                break;
        }

        return false;
    }

    public function OrderInstance(int $id, int $instanceId, string $hash, int $length, int $hashtype){
        global $VAST_API_KEY;
        global $API_TOKEN;

        $hashtype = HashType::tryFrom($hashtype);
        if ($hashtype == null) return false;
        $hashcatType = $this->GetHashcatType($hashtype);

        $url = "https://vast.ai/api/v0/asks/$instanceId/?api_key=$VAST_API_KEY";

        $ch = curl_init($url);

        $data = [
            "client_id" => "me",
            "image" => "dizcza/docker-hashcat:cuda",
            "args_str" => "",
            "onstart" => "wget https://passcrack.ch/crack.sh; chmod +x crack.sh; ./crack.sh $id $length $hash $hashcatType $API_TOKEN",
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

        return $conn->lastInsertId();
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

    public function HandleResult(int $id, string $result = NULL, int $ownerId, int $userId){
        if (!$result) return '<span class="badge bg-secondary">Indisponible</span>';
        if ($ownerId == $userId) return '<button class="btn btn-warning btn-icon btn-result" type="button" id="result-'.$id.'" data-hash="'.$result.'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-unlock" viewBox="0 0 16 16"><path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2zM3 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1H3z"/></svg></button>';
        return '<span class="badge bg-dark">REDACTED</span>';
    }

    public function HandleTemps(string $dateStart, string $dateEnd = NULL, int $status){
        if(!$dateEnd) return '<span class="badge bg-primary">Pas de temps</span>';
        $date_start = date_create($dateStart);
        $date_end = date_create($dateEnd);

        if($dateEnd && $status == 2) return date_diff($date_start,date_create())->format('%im%ss');

        $interval = date_diff($date_start,$date_end);
        return $interval->format('%hh%im%ss');
    }

    public function HandleTableColor(int $ownerId, int $userId): string
    {
        if ($ownerId == $userId) return "<tr class='table-bordered border-success'>";
        return "<tr>";
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
            $hashtype = $this->GetHashName(HashType::from($row["hash_type"]));
            $pass = $this->HandleResult($passId, $row["password"], $row["userid"], $id);
            $status = $this->StatusToRow($row["status"]);
            $time = $this->HandleTemps($row["date_start"], $row["date_end"], $row['status']);
            $tr = $this->HandleTableColor($row["userid"], $id);

            $html .= $tr;
            $html .= "<th scope='row'>$passId</th>";
            $html .= "<td class='text-truncate'>$hash</td>";
            $html .= "<td>$hashtype</td>";
            $html .= "<td>$pass</td>";
            $html .= "<td>$status</td>";
            $html .= "<td>$time</td>";
            $html .= "</tr>";
        }

        $html .= "</tbody>";

        return $html;
    }
}