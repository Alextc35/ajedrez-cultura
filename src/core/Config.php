<?php
/**
 * Clase Config
 * -----------------------
 * Carga y gestiona parámetros definidos en el archivo config.txt.
 * Utiliza el patrón Singleton para asegurar una única instancia global.
 *
 * Configuración esperada en config.txt:
 * - DB_HOST
 * - DB_PUERTO
 * - DB_NAME
 * - DB_USER
 * - DB_PASS
 * - DEFAULT_CONTROLLER
 * - DEFAULT_ACTION
 * - DEFAULT_CONTROLLER_LOGIN
 * - DEFAULT_INDEX
 *
 * La clase transforma:
 * - DEFAULT_INDEX → prefijado con "http:"
 * - DB_HOST + DB_PUERTO → combinados como "host:puerto"
 */
class Config {
    private static $instancia = null;
    private $arrParametros;

    private function __construct() {
        $strNombreFichero = '..\..\config\config.txt';
        $arrAux = ['DB_HOST', 'DB_PUERTO', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DEFAULT_CONTROLLER', 'DEFAULT_ACTION', 'DEFAULT_CONTROLLER_LOGIN', 'DEFAULT_INDEX'];
        $this->arrParametros = [];

        if ($fichero = fopen(__DIR__ . '/' . $strNombreFichero,'rb')) {
            while (!feof($fichero)) {
                    $strLinea = fgets($fichero);
                    if (!str_starts_with($strLinea,'#')) {
                        $arrParametro = explode('=',trim($strLinea));
                        if (count($arrParametro)==2)
                            $this->arrParametros[trim($arrParametro[0])] = trim($arrParametro[1]);
                    }
            }
            fclose($fichero);
        }

        $intCont=0;
        $cantidad = count($arrAux);
        for (; $intCont<$cantidad && array_key_exists($arrAux[$intCont], $this->arrParametros); $intCont++);

        if ($intCont<$cantidad) {
            exit("El archivo de configuración $strNombreFichero es incorrecto");
        } else {
            $this->arrParametros['DEFAULT_INDEX'] = 'http:' . $this->arrParametros['DEFAULT_INDEX'];
            $this->arrParametros['DB_HOST'] =  $this->arrParametros['DB_HOST'] . ':' .  $this->arrParametros['DB_PUERTO'];
        }
    }

    public static function getInstancia() {
        if (Config::$instancia === null)
            Config::$instancia = new Config();
        return Config::$instancia;
    }

    public function getParametro(string $strClave) : string {
        return $this->arrParametros[$strClave] ?? '';
    }
}