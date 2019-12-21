<?php
namespace Models;
use Models\UserRole as UserRole;
class User {
    private $id;
    private $email;
    private $password;
    private $firstName;
    private $lastName;
    private $photo;
    private $userRole;
    private $idFacebook;

    public function __construct() {

    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function getUserRole() {
        return $this->userRole;
    }

    public function setUserRole(UserRole $userRole) {
        $this->userRole = $userRole;
    }

    public function getUserRoleId() {
        $userRole = $this->userRole;
        $id = $userRole->getId();
        return $id;
    }

    public function getUserRoleDescription() {
        $userRole = $this->userRole;
        $descripcion = $userRole->getDescription();
        return $descripcion;
    }

    public function getIdFacebook() {
        return $this->idFacebook;
    }

    public function setIdFacebook($idFacebook) {
        $this->idFacebook = $idFacebook;
    }
}
?>
