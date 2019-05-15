<?php

namespace dongdavid\reptile\tools;

/**
 * Env读取
 */
class Env
{
    const ENV_PREFIX = "DDV_";
    public static function overloadEnv($path)
    {
        if (is_file($path)) {
        	var_dump($path);
            $env = parse_ini_file($path, true);            
            foreach ($env as $key => $val) {
                $name = self::ENV_PREFIX . strtoupper($key);
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $item = $name . '_' . strtoupper($k);
                        putenv("$item=$v");
                    }
                } else {
                    putenv("$name=$val");
                    // $_ENV[$name] = $val;
                }
            }
        } else {
            echo ".env文件不存在" . PHP_EOL;
        }
    }
    public static function get($name)
    {
        return getenv(self::ENV_PREFIX . $name);
    }
}
