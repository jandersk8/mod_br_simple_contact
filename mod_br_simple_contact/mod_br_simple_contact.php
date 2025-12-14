<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_br_simple_contact
 *
 * @copyright   Copyright (c) 2025 Janderson Moreira. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Br\Module\SimpleContact\Helper\SimpleContactHelper;

$app = Factory::getApplication();

// --- CORREÇÃO DO ERRO FATAL ---
// Carregamos o arquivo manualmente para evitar erros de pasta (Linux Case Sensitive)
// Isso garante que funcione no Joomla 5 e 6 sem "Class Not Found"
require_once __DIR__ . '/src/Helper/SimpleContactHelper.php';

// Instancia o Helper e processa (se houver envio)
$contactHelper = new SimpleContactHelper($params, $module);
$result = $contactHelper->handleSubmission();

// --- LÓGICA DO AJAX (NOVA) ---
// Se o navegador pediu via AJAX, retornamos JSON e paramos o site aqui.
if ($app->input->get('via_ajax', 0, 'int') === 1)
{
    // Limpa qualquer lixo de memória anterior
    if (ob_get_length()) { ob_end_clean(); }

    header('Content-Type: application/json');
    
    if ($result === null) {
        echo json_encode(['success' => false, 'message' => 'Nenhum dado recebido.']);
    } else {
        echo json_encode($result);
    }
    
    // Fecha a aplicação (não carrega o resto do site)
    $app->close();
}

// --- CARREGAMENTO PADRÃO (FALLBACK) ---
// Se não for AJAX, carrega o template normalmente para exibir o formulário
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_QUOTES, 'UTF-8');
require ModuleHelper::getLayoutPath('mod_br_simple_contact', $params->get('layout', 'default'));