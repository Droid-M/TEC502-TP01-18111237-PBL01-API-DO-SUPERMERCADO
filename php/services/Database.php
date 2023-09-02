<?php

use php\services\Response;

/** @var null|PDO $pdo19293s8389210283383801 */
$pdo19293s8389210283383801 = null;

class Database
{
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
}
