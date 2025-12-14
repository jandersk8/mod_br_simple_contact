<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_br_simple_contact
 *
 * @copyright   Copyright (c) 2025 Janderson Moreira. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

// Scripts básicos (mantemos por compatibilidade, mas nosso CSS resolve o estilo)
HTMLHelper::_('behavior.formvalidator');

$formId    = 'br-mod-form-' . $module->id;
$msgId     = 'br-mod-msg-' . $module->id;
$btnId     = 'br-mod-btn-' . $module->id;
// ID Wrapper para "blindar" o CSS contra o template do site
$wrapperId = 'br-mod-wrapper-' . $module->id;
?>

<style>
    /* --- CSS ISOLADO (ESTILO PRÓPRIO - MÓDULO) --- */

    #<?php echo $wrapperId; ?> {
        all: unset; /* Reseta heranças do template */
        display: block;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        margin-bottom: 20px;
    }

    #<?php echo $wrapperId; ?> * { box-sizing: border-box; }

    /* CARTÃO */
    #<?php echo $wrapperId; ?> .br-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        padding: 24px;
        overflow: hidden;
    }

    /* GRUPO DE CAMPO */
    #<?php echo $wrapperId; ?> .br-field-group {
        position: relative;
        margin-bottom: 16px;
        border: none;
        padding: 0;
    }

    /* INPUTS */
    #<?php echo $wrapperId; ?> .br-input {
        width: 100%;
        height: 56px;
        padding: 20px 12px 6px 12px; /* Espaço para o label flutuar */
        font-size: 16px;
        color: #333;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
        transition: all 0.2s ease;
        line-height: normal;
        display: block;
    }
    #<?php echo $wrapperId; ?> textarea.br-input {
        height: 120px;
        resize: none;
    }

    /* LABEL FLUTUANTE */
    #<?php echo $wrapperId; ?> .br-label {
        position: absolute;
        left: 12px;
        top: 18px;
        font-size: 16px;
        color: #666;
        pointer-events: none;
        transition: all 0.2s ease;
        background: transparent;
        line-height: 1;
        margin: 0;
    }

    /* EFEITO DE FLUTUAÇÃO */
    #<?php echo $wrapperId; ?> .br-input:focus ~ .br-label,
    #<?php echo $wrapperId; ?> .br-input:not(:placeholder-shown) ~ .br-label {
        top: 8px;
        font-size: 12px;
        color: #0d6efd;
        font-weight: 600;
    }

    #<?php echo $wrapperId; ?> .br-input::placeholder { color: transparent; opacity: 0; }

    /* FOCO */
    #<?php echo $wrapperId; ?> .br-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    /* BOTÃO */
    #<?php echo $wrapperId; ?> .br-btn-submit {
        width: 100%;
        padding: 14px;
        background-color: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #<?php echo $wrapperId; ?> .br-btn-submit:hover { background-color: #0b5ed7; }
    #<?php echo $wrapperId; ?> .br-btn-submit:disabled { background-color: #ccc; cursor: not-allowed; }

    /* SPINNER (Animação) */
    .br-spinner {
        display: inline-block;
        width: 1.2rem;
        height: 1.2rem;
        margin-right: 8px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: br-spin 1s ease-in-out infinite;
    }
    @keyframes br-spin { to { transform: rotate(360deg); } }

    /* MENSAGENS E ERROS */
    #<?php echo $wrapperId; ?> .br-input.br-invalid { border-color: #dc3545; }
    #<?php echo $wrapperId; ?> .br-input.br-invalid ~ .br-label { color: #dc3545; }
    
    #<?php echo $wrapperId; ?> .br-alert {
        padding: 12px;
        margin-bottom: 16px;
        border-radius: 4px;
        text-align: center;
        font-size: 14px;
    }
    .br-alert-success { background: #d1e7dd; color: #0f5132; }
    .br-alert-error { background: #f8d7da; color: #842029; }
</style>

<div id="<?php echo $wrapperId; ?>">
    <div class="br-card">
        
        <form action="<?php echo Uri::getInstance()->toString(); ?>" method="post" id="<?php echo $formId; ?>" novalidate>

            <div class="br-field-group">
                <input type="text" name="br_name" id="br_name_<?php echo $module->id; ?>" class="br-input required" placeholder=" ">
                <label for="br_name_<?php echo $module->id; ?>" class="br-label">
                    <?php echo Text::_('MOD_BR_SIMPLE_CONTACT_FIELD_NAME'); ?> *
                </label>
            </div>

            <div class="br-field-group">
                <input type="email" name="br_email" id="br_email_<?php echo $module->id; ?>" class="br-input required validate-email" placeholder=" ">
                <label for="br_email_<?php echo $module->id; ?>" class="br-label">
                    <?php echo Text::_('MOD_BR_SIMPLE_CONTACT_FIELD_EMAIL'); ?> *
                </label>
            </div>

            <div class="br-field-group">
                <textarea name="br_message" id="br_message_<?php echo $module->id; ?>" class="br-input required" placeholder=" "></textarea>
                <label for="br_message_<?php echo $module->id; ?>" class="br-label">
                    <?php echo Text::_('MOD_BR_SIMPLE_CONTACT_FIELD_MESSAGE'); ?> *
                </label>
            </div>

            <div id="<?php echo $msgId; ?>" style="display:none;" class="br-alert"></div>

            <button type="submit" class="br-btn-submit" id="<?php echo $btnId; ?>">
                <span class="icon-paper-plane me-2"></span>
                <?php echo Text::_('MOD_BR_SIMPLE_CONTACT_BTN_SEND'); ?>
            </button>

            <input type="hidden" name="br_contact_submit" value="1">
            <input type="hidden" name="via_ajax" value="1">
            <?php echo HTMLHelper::_('form.token'); ?>

        </form>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('<?php echo $formId; ?>');
    const msgDiv = document.getElementById('<?php echo $msgId; ?>');
    const btn = document.getElementById('<?php echo $btnId; ?>');
    
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let valid = true;
        
        const inputs = form.querySelectorAll('.required');
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('br-invalid');
                valid = false;
            } else {
                input.classList.remove('br-invalid');
                if (input.type === 'email' && !input.value.includes('@')) {
                    input.classList.add('br-invalid');
                    valid = false;
                }
            }
            input.addEventListener('input', function() { this.classList.remove('br-invalid'); });
        });

        if (!valid) {
            e.preventDefault();
            return;
        }

        e.preventDefault();

        // Salva estado original
        const originalBtnText = btn.innerHTML;
        
        // Bloqueia e mostra Spinner
        btn.disabled = true;
        btn.innerHTML = '<span class="br-spinner"></span> Enviando...';
        
        msgDiv.style.display = 'none';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalBtnText;

            msgDiv.style.display = 'block';
            msgDiv.innerHTML = data.message;
            msgDiv.className = 'br-alert ' + (data.success ? 'br-alert-success' : 'br-alert-error');

            if (data.success) {
                form.reset();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            btn.disabled = false;
            btn.innerHTML = originalBtnText;
            msgDiv.style.display = 'block';
            msgDiv.innerText = 'Erro de conexão.';
            msgDiv.className = 'br-alert br-alert-error';
        });
    });
});
</script>