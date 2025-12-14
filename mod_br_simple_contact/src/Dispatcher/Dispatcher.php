<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_br_simple_contact
 *
 * @copyright   Copyright (c) 2025 Janderson Moreira. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Br\Module\SimpleContact\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Br\Module\SimpleContact\Helper\SimpleContactHelper;

/**
 * Dispatcher do Módulo de Contato
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /**
     * Retorna os dados que serão usados no layout (tmpl/default.php)
     *
     * @return  array
     */
    protected function getLayoutData(): array
    {
        // Pega os dados padrão (params, module, etc)
        $data = parent::getLayoutData();

        // Instancia o nosso Helper (que criaremos no próximo passo).
        // Passamos os parâmetros e o objeto do módulo para ele.
        $helper = new SimpleContactHelper($this->params, $this->module);

        // Verifica se o formulário foi submetido e processa o envio do e-mail
        // O resultado (sucesso ou erro) pode ser passado para a view
        $processResult = $helper->handleSubmission();

        // Injetamos o helper e o resultado no array $data
        // Assim, lá no HTML (default.php), podemos usar $displayData['helper']
        $data['helper'] = $helper;
        $data['result'] = $processResult;

        return $data;
    }
}