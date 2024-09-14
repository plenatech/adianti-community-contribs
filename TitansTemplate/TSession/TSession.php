<?php
namespace Adianti\Registry;

use SessionHandlerInterface;
use Adianti\Registry\AdiantiRegistryInterface;

/**
 * Session Data Handler
 *
 * @version    7.6
 * @package    registry
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license
 */
class TSession implements AdiantiRegistryInterface
{
    /**
     * Class Constructor
     */
    public function __construct(SessionHandlerInterface $handler = NULL, $path = NULL)
    {
        if ($path)
        {
            session_save_path($path);
        }
        
        if ($handler)
        {
            session_set_save_handler($handler, true);
        }
		
        // if there's no opened session
        if (!session_id())
        {
            session_start();
            ############################################
            ### TitansTemplate - Inicio da alteração ###
            ############################################
            // Logo apos iniciar a sessão já fecha a mesma (permitindo outras manipulações)
            session_commit();
            ############################################
            ###  TitansTemplate -- Fim da alteração  ###
            ############################################
        }
    }
    
    /**
     * Returns if the service is active
     */
    public static function enabled()
    {
        if (!session_id())
        {
            ############################################
            ### TitansTemplate - Inicio da alteração ###
            ############################################

            // a Linha abaixo faz aprte da classe original e foi comentada
            // return session_start();

            // session_start retorna um bool, se houve sucesso, fecha a sessão e retorna
            // true, do contrário, retorna false, mantendo a compatibilidade
            if (session_start()) {
                session_commit();
                return true;
            } else {
                return false;
            }
            ############################################
            ###  TitansTemplate -- Fim da alteração  ###
            ############################################
        }
        return TRUE;
    }
    
    /**
     * Define the value for a variable
     * @param $var   Variable Name
     * @param $value Variable Value
     */
    public static function setValue($var, $value)
    {
        ############################################
        ### TitansTemplate - Inicio da alteração ###
        ############################################
        // Antes de setar um valor abre a sessão pra escrita - aqui pode haver alguma concorrência e espera
        session_start();
        ############################################
        ###  TitansTemplate -- Fim da alteração  ###
        ############################################
        if (defined('APPLICATION_NAME'))
        {
            $_SESSION[APPLICATION_NAME][$var] = $value;
        }
        else
        {
            $_SESSION[$var] = $value;
        }
        ############################################
        ### TitansTemplate - Inicio da alteração ###
        ############################################
        // Após setar o valor fecha a sesão e a libera para outros processos
        session_commit();
        ############################################
        ###  TitansTemplate -- Fim da alteração  ###
        ############################################
    }
    
    /**
     * Returns the value for a variable
     * @param $var Variable Name
     */
    public static function getValue($var)
    {
        if (defined('APPLICATION_NAME'))
        {
            if (isset($_SESSION[APPLICATION_NAME][$var]))
            {
                return $_SESSION[APPLICATION_NAME][$var];
            }
        }
        else
        {
            if (isset($_SESSION[$var]))
            {
                return $_SESSION[$var];
            }
        }
    }
    
    /**
     * Clear the value for a variable
     * @param $var   Variable Name
     */
    public static function delValue($var)
    {
        ############################################
        ### TitansTemplate - Inicio da alteração ###
        ############################################
        // Antes de remover um valor abre a sessão pra escrita - aqui pode haver alguma concorrência e espera
        session_start();
        ############################################
        ###  TitansTemplate -- Fim da alteração  ###
        ############################################
        if (defined('APPLICATION_NAME'))
        {
            unset($_SESSION[APPLICATION_NAME][$var]);
        }
        else
        {
            unset($_SESSION[$var]);
        }
        ############################################
        ### TitansTemplate - Inicio da alteração ###
        ############################################
        // Após remover o valor fecha a sesão e a libera para outros processos
        session_commit();
        ############################################
        ###  TitansTemplate -- Fim da alteração  ###
        ############################################
    }
    
    /**
     * Regenerate id
     */
    public static function regenerate()
    {
        ############################################
        ### TitansTemplate - Inicio da alteração ###
        ############################################
        // linha original da classe
        // session_regenerate_id();

        // abre a sessão para escrita, regenera o ID e a fecha para escrita
        session_start();
        session_regenerate_id();
        session_commit();
        ############################################
        ###  TitansTemplate -- Fim da alteração  ###
        ############################################
    }
    
    /**
     * Clear session
     */
    public static function clear()
    {
        self::freeSession();
    }
    
    /**
     * Destroy the session data
     * Backward compatibility
     */
    public static function freeSession()
    {
        ############################################
        ### TitansTemplate - Inicio da alteração ###
        ############################################
        // Antes de limpar abre a sessão pra escrita - aqui pode haver alguma concorrência e espera
        session_start();
        ############################################
        ###  TitansTemplate -- Fim da alteração  ###
        ############################################
        if (defined('APPLICATION_NAME'))
        {
            $_SESSION[APPLICATION_NAME] = array();
        }
        else
        {
            $_SESSION[] = array();
        }
        ############################################
        ### TitansTemplate - Inicio da alteração ###
        ############################################
        // Após limpar fecha a sesão e a libera para outros processos
        session_commit();
        ############################################
        ###  TitansTemplate -- Fim da alteração  ###
        ############################################
    }
}
