<?php
/**
 * Copyright 2019 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl.
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @license  https://www.horde.org/licenses/bsd BSD
 * @package  GitTools
 */

namespace Horde\GitTools\Module;

use Horde_Argv_IndentedHelpFormatter as IndentedHelpFormatter;
use Horde_String;
use Components_Configs as Configs;
use Horde_Argv_Option as Option;

/**
 * Class for handling help.
 *
 * @author    Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2019 Horde LLC
 * @license   https://www.horde.org/licenses/bsd BSD
 * @package   GitTools
 */
class Config extends Base
{
    public function handle(Configs $config)
    {
        $params = $config->getOptions();
        // Arguments will be an array:
        // 0 => help 1 => COMMAND [2 => ACTION]
        $arguments = $config->getArguments();
        if (isset($arguments[0]) && $arguments[0] == 'config') {
            if (empty($arguments[1])) {
                $this->_dependencies->getOutput()->help($this->getHelp());
                return true;
            }
            $vars = array('conf');
            switch ($arguments[1]) {
            case 'show':
                if (file_exists($params['config_file'])) {
                    $this->_doShow($params['config_file'], $vars);
                }
            break;
            case 'set':
            break;

            }
            return true;
        }

        return false;
    }

    /**
     * Render a configuration to screen.
     */
    protected function _doShow($file, array $vars)
    {
        include $file;
        foreach ($vars as $var) {
            print($this->_renderVariable($$var, $var));
        }
    }

    protected function _doSet($file, array $vars, $key, $value)
    {

    }

    /**
     * Render a config variable to a string
     *
     * Simple approach for trivial configs of 
     * max 1 level + number-indexed values, need to expand
     *
     * @param array   $variable  the config variable
     * @param string  $name      The name of the variable
     *
     * @return string  The rendered string
     */
    protected function _renderVariable($variable, $name)
    {
        $output = '';
        foreach ($variable as $topKey => $value) {
            $output .= '$' . $name . '[\'' . $topKey . '\'] = ';
            if (is_array($value)) {
                if (empty($value)) {
                    $output .= 'array();' . PHP_EOL;
                } else {
                    $output .= '[\'' . implode($value, '\', \'') . "'];\n";
                }
            } else {
                $output .= '\'' . $value . '\';' . PHP_EOL;
            }
        }
        return $output;
    }

    /**
     * Returns additional usage title for this module.
     *
     * @return string  The usage title.
     */
    public function getTitle()
    {
        return 'config [get|set|show] key value';
    }

    /**
     * Returns additional usage description for this module.
     *
     * @return string The description.
     */
    public function getUsage()
    {
        return 'Set or retrieve configs in files';
    }

    /**
     * Return the action arguments supported by this module.
     *
     * @return array A list of supported action arguments.
     */
    public function getActions()
    {
        return array(
            'set' => 'Store a config value to file',
            'show' => 'Get a summary of the config'
        );
    }

    /**
     * A module specific help text
     */
    public function getHelp()
    {
        return 'Available actions for this module are: ' . $this->_actionFormatter();
    }

    /**
     * A title printed with the option group
     */
    public function getOptionGroupTitle()
    {
        return 'Manage stored configuration';
    }

    /**
     * Signal we have options for this module
     */
    public function hasOptionGroup()
    {
        return true;
    }

    public function getOptionGroupOptions($action = null)
    {
        return array(
            // We intentionally do not use the --config value as we want
            // to use this module for multiple purposes (bootstrapping!)
            new Option(
                '',
                '--config-file',
                array(
                    'action' => 'store',
                    'help' => 'OPTIONAL: Which config file to process',
                    'default' => dirname(__File__, 3) . '/config/conf.php'
                )
            )
        );
    }

}
