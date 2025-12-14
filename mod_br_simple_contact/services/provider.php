<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_br_simple_contact
 *
 * @copyright   Copyright (c) 2025 Janderson Moreira. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface
{
    /**
     * Registra os serviços do módulo no Container de Injeção de Dependência
     *
     * @param   Container  $container  O container DI
     *
     * @return  void
     */
    public function register(Container $container)
    {
        // 1. Registra a Fábrica de Dispatchers
        // O primeiro argumento aqui é fundamental: é o Namespace base do seu módulo.
        // O Joomla vai usar isso para procurar a classe 'Dispatcher\Dispatcher.php'
        $container->registerServiceProvider(new ModuleDispatcherFactory('Br\\Module\\SimpleContact'));

        // 2. Registra o serviço padrão de Módulo do Joomla
        $container->registerServiceProvider(new Module());
    }
};