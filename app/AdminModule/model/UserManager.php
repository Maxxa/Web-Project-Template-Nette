<?php

namespace App\AdminModule\Model;

use Nette;
use Nette\Database\Context;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

/**
 * Users management.
 */
class UserManager extends Object implements IAuthenticator {

    const
            TABLE_NAME = 'users',
            COLUMN_ID = 'id',
            COLUMN_USER_NAME = 'username',
            COLUMN_PASSWORD_HASH = 'password',
            COLUMN_NAME = 'name';

    /** @var Context */
    private $database;

    public function __construct(Context $database) {
        $this->database = $database;
    }

    /**
     * Performs an authentication.
     * @param array $credentials
     * @return Identity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials) {
        list($username, $password) = $credentials;

        $row = $this->database->table(self::TABLE_NAME)->where([self::COLUMN_USER_NAME=> $username])->fetch();

        if (!$row) {
            throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        } elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
            throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
        } elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
            $row->update(array(
                self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
            ));
        }
        $arr = $row->toArray();
        unset($arr[self::COLUMN_PASSWORD_HASH]);
        return new Identity($row[self::COLUMN_ID],$arr["role"], $arr);
    }

    public function add($values) {
        $values["password"] = $this->passwordHash($values["password"]);
        unset($values["passwordVerify"]);
        try {
            $this->database->table(self::TABLE_NAME)->insert($values);
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }
    
    public function getRow($id){
        return $this->getRows()->get($id);
    }
    
    public function getRows(){
        return $this->database->table(self::TABLE_NAME);
    }

    public function passwordHash($password){
        return Passwords::hash($password);
    }
    
}

class DuplicateNameException extends \Exception {
    
}
