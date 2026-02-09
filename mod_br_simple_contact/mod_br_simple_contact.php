<?php
/**
 * @package     BR Simple Contact
 * @author      Janderson Moreira
 * @copyright   Copyright (C) 2026 Janderson Moreira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Br\Module\SimpleContact\Helper\SimpleContactHelper;

$app = Factory::getApplication();

/**
 * Carregamento manual do Helper para garantir compatibilidade entre sistemas operacionais
 * e evitar o erro de "Class Not Found" no Joomla 5 e 6.
 */
require_once __DIR__ . '/src/Helper/SimpleContactHelper.php';

// Instancia o Helper e processa a submissão
$contactHelper = new SimpleContactHelper($params, $module);
$result = $contactHelper->handleSubmission();

// Lógica para interceptar chamadas AJAX
if ($app->input->get('via_ajax', 0, 'int') === 1)
{
    if (ob_get_length()) { 
        ob_end_clean(); 
    }

    header('Content-Type: application/json');
    
    if ($result === null) {
        echo json_encode(['success' => false, 'message' => 'Nenhum dado recebido.']);
    } else {
        echo json_encode($result);
    }
    
    $app->close();
}

// Configurações de layout
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_QUOTES, 'UTF-8');
require ModuleHelper::getLayoutPath('mod_br_simple_contact', $params->get('layout', 'default'));