<?php
class Utility
{
    public function ChangePasswordStatus(int $id, Status $status, string $password = NULL){
        global $conn;

        $satusValue = $status->value;

        if ($password){
            $stmt = $conn->prepare("UPDATE password SET status = :stat, password = :pass WHERE id = :id");
            $stmt->bindParam(":stat", $satusValue, PDO::PARAM_INT);
            $stmt->bindParam(":pass", $password);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        }else{
            $stmt = $conn->prepare("UPDATE password SET status = :stat WHERE id = :id");
            $stmt->bindParam(":stat", $satusValue, PDO::PARAM_INT);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        }

        $stmt->execute();
    }
}