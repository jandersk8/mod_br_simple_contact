<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_br_simple_contact
 *
 * @copyright   Copyright (c) 2025 Janderson Moreira. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Br\Module\SimpleContact\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\Registry\Registry;

/**
 * Helper para processar o formulário de contato via AJAX
 */
class SimpleContactHelper
{
    protected $params;
    protected $module;

    public function __construct(Registry $params, $module)
    {
        $this->params = $params;
        $this->module = $module;
    }

    /**
     * Retorna um ARRAY com o resultado para o JSON
     */
    public function handleSubmission()
    {
        $app   = Factory::getApplication();
        $input = $app->input;

        if ($input->get('br_contact_submit', 0, 'int') !== 1) {
            return null;
        }

        // 1. Segurança
        if (!Session::checkToken()) {
            return ['success' => false, 'message' => Text::_('JINVALID_TOKEN')];
        }

        // 2. Coleta dados
        $data = [
            'name'    => $input->getString('br_name', ''),
            'email'   => $input->getString('br_email', ''),
            'message' => $input->getString('br_message', '')
        ];

        // 3. Validação
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            return ['success' => false, 'message' => Text::_('MOD_BR_SIMPLE_CONTACT_ERROR_EMPTY_FIELDS')];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => Text::_('MOD_BR_SIMPLE_CONTACT_ERROR_INVALID_EMAIL')];
        }

        // 4. Envio
        if ($this->sendEmail($data)) {
            $msg = $this->params->get('success_message', Text::_('MOD_BR_SIMPLE_CONTACT_SUCCESS_DEFAULT'));
            return ['success' => true, 'message' => $msg];
        } else {
            return ['success' => false, 'message' => Text::_('MOD_BR_SIMPLE_CONTACT_ERROR_SENDING')];
        }
    }

    /**
     * Envia o e-mail com o NOME do cliente no remetente
     */
    protected function sendEmail(array $data): bool
    {
        $mailer = Factory::getMailer();
        $config = Factory::getConfig();

        // --- AQUI ESTÁ A MUDANÇA QUE VOCÊ PEDIU ---
        // 1º Parâmetro: E-mail do Site (Obrigatório para não ser Spam)
        // 2º Parâmetro: Nome do Cliente (Para aparecer bonito na sua caixa de entrada)
        $sender = [
            $config->get('mailfrom'), 
            $data['name'] // <--- Isso coloca "teste janjan" como remetente
        ];
        $mailer->setSender($sender);

        // Destinatário
        $recipient = $this->params->get('recipient_email');
        if (empty($recipient)) {
            $recipient = $config->get('mailfrom');
        }
        $mailer->addRecipient($recipient);

        // Assunto
        $subject = $this->params->get('email_subject', 'Contato do Site');
        $mailer->setSubject($subject . ' - ' . $data['name']);

        // Corpo
        $body  = "Nome: " . $data['name'] . "\r\n";
        $body .= "E-mail: " . $data['email'] . "\r\n";
        $body .= "Mensagem:\r\n" . $data['message'] . "\r\n";
        
        // Reply-To (Para responder direto para o cliente)
        $mailer->addReplyTo($data['email'], $data['name']);
        $mailer->setBody($body);

        try {
            return $mailer->send();
        } catch (\Exception $e) {
            return false;
        }
    }
}