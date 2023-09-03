<?php

namespace php\services;

use Closure;
use Exception;
use PDO;
use PDOException;
use php\services\Response;
use php\traits\Seedable;

/** @var null|PDO $pdo19293s8389210283383801 */
$pdo19293s8389210283383801 = null;

class Database
{
    use Seedable;

    public static function init()
    {
        try {
            global $pdo19293s8389210283383801;
            $host = env(DATABASE_HOST_KEY);
            $name = env(DATABASE_NAME_KEY);
            $pdo19293s8389210283383801 = new PDO(
                "mysql:host=$host;dbname=$name",
                env(DATABASE_USERNAME_KEY),
                env(DATABASE_PASSWORD_KEY)
            );
            $pdo19293s8389210283383801->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Response::abort(500, 'Erro de conexão: ' . $e->getMessage());
        }
    }

    public static function getPDO()
    {
        global $pdo19293s8389210283383801;
        return $pdo19293s8389210283383801;
    }

    public static function beginTransaction()
    {
        global $pdo19293s8389210283383801;
        $pdo19293s8389210283383801->beginTransaction();
    }

    public static function commit()
    {
        global $pdo19293s8389210283383801;
        $pdo19293s8389210283383801->commit();
    }

    public static function rollback()
    {
        global $pdo19293s8389210283383801;
        $pdo19293s8389210283383801->rollBack();
    }

    public static function closeConnection()
    {
        global $pdo19293s8389210283383801;
        $pdo19293s8389210283383801 = null;
    }

    public static function transaction(Closure $callback)
    {
        global $pdo19293s8389210283383801;
        try {
            $pdo19293s8389210283383801->beginTransaction();
            $toReturn = $callback();
            $pdo19293s8389210283383801->commit();
            return $toReturn;
        } catch (Exception $e) {
            $pdo19293s8389210283383801->rollBack();
            return throw $e;
        }
    }
}
