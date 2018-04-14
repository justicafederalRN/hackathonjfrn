<?php
namespace Auth;

use W5n\Model\Model;

/*
 * Auth trait to enable auth capabilities on Models
 */
trait AuthableModel
{
    protected static $authModel = null;

    public static function login($login, $password, $persistent = false)
    {
        $userData = self::authFetchData($login);

        if (empty($userData)) {
            return false;
        }

        if (!self::authCheck($password, $userData[self::authGetPasswordColumn()])) {
            return false;
        }

        return self::authDoLogin($userData, $persistent);
    }

    protected static function authFetchData($login)
    {
        $query = self::getFindQueryBuilder();
        $query->where(self::authGetLoginColumn() . '=' . $query->createNamedParameter($login));

        if (\method_exists(static::class, 'authModifyQuery')) {
            call_user_func([static::class, 'authModifyQuery'], $query, $login, $password, $persistent);
        }

        return $query->execute()->fetch();
    }

    public static function autoLogin($login, $persistent = false)
    {
        $userData = self::authFetchData($login);

        if (empty($userData)) {
            return false;
        }

        return self::authDoLogin($userData, $persistent);
    }

    public static function authRefresh()
    {
        if (self::isAnonymous()) {
            return false;
        }

        $model  = self::getLoggedUser();
        $column = self::authGetLoginColumn();

        return self::autoLogin($model->{$column});
    }

    public static function authDoLogin($userData, $persistent)
    {
        $session = self::authGetSession();
        $session->set(self::authGetSessionKey(), $userData);


        //TODO: Implementar login persistente
        /*if ($persistent) {
            $persistentColumn = self::authGetPersistentColumn();
            if (!empty($persistentColumn)) {
                $loggedUser = self::getLoggedUser();
            }
          }*/

        return true;
    }

    public static function logout()
    {
        self::authGetSession()->remove(self::authGetSessionKey());
        return true;
    }

    public static function isLoggedIn()
    {
        return self::authGetSession()->has(self::authGetSessionKey());
    }

    public static function isAnonymous()
    {
        return !self::isLoggedIn();
    }

    public static function getLoggedUser()
    {
        if (self::isAnonymous()) {
            return null;
        }

        if (!empty(self::$authModel)) {
            return self::$authModel;
        }

        $data            = self::authGetSession()->get(self::authGetSessionKey());
        self::$authModel = self::createAuthModel();
        self::$authModel->populateFromArray($data, true, self::OP_DB_POPULATE);

        return self::$authModel;
    }

    protected static function createAuthModel()
    {
        return new static();
    }

    protected static function authHash($password)
    {
        $options = [
            'cost' => 12
        ];

        return \password_hash($password, \PASSWORD_BCRYPT, $options);
    }

    public static function authCheck($password, $hash)
    {
        return \password_verify($password, $hash);
    }

    protected static function authGetSession()
    {
        $app = \Application::getDefault();
        return $app['session'];
    }

    protected static function authGetLoginColumn()
    {
        return self::getDefaultPropertyValue('authLoginColumn', 'login');
    }

    protected static function authGetPersistentColumn()
    {
        return self::getDefaultPropertyValue('authPersistentColumn', 'login');
    }

    protected static function authGetSessionKey()
    {
        return self::getDefaultPropertyValue('authSessionKey', '$__auth_user__$');
    }

    protected static function authGetPasswordColumn()
    {
        return self::getDefaultPropertyValue('authPasswordColumn', 'password');
    }
}
