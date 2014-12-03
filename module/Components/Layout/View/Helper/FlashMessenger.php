<?php
namespace Components\Layout\View\Helper;

use Zend\View\Helper\FlashMessenger as ZVHFlashMessenger;
use Zend\Mvc\Controller\Plugin\FlashMessenger as PluginFlashMessenger;

class FlashMessenger extends ZVHFlashMessenger
{

    /**
     * Templates for the open/close/separators for message tags
     *
     * @var string
     */
    protected $messageCloseString = '</div>';

    protected $messageOpenFormat = '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="ace-icon fa fa-times"></i></button>';

    protected $messageSeparatorString = '<br>';

    protected $messageOpenFormatTemplate = '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="ace-icon fa fa-times"></i></button>';

    protected $successIconTemplate = '<i class="ace-icon fa fa-check green"></i> ';

    protected $errorIconTemplate = '<i class="ace-icon fa fa-times"></i> ';

    protected $infoIconTemplate = '<i class="ace-icon fa  fa-info-circle"></i> ';

    protected $warningIconTemplate = '<i class="ace-icon fa fa-exclamation-triangle"></i> ';

    /**
     * Render Messages
     *
     * @param string $namespace
     * @param array $classes
     * @return string
     */
    public function render($namespace = PluginFlashMessenger::NAMESPACE_DEFAULT, array $classes = array())
    {
        $flashMessenger = $this->getPluginFlashMessenger();
        $return = "<div class='flash-messager-container'>";

        // Render SUCCESS
        $messages = $flashMessenger->getMessagesFromNamespace(PluginFlashMessenger::NAMESPACE_SUCCESS);
        $this->setMessageOpenFormat($this->messageOpenFormatTemplate . $this->successIconTemplate);
        $return .= $this->renderMessages($namespace, $messages, array(
            'alert',
            'alert-success'
        ));

        // Render ERROR
        $messages = $flashMessenger->getMessagesFromNamespace(PluginFlashMessenger::NAMESPACE_ERROR);
        $this->setMessageOpenFormat($this->messageOpenFormatTemplate . $this->errorIconTemplate);
        $return .= $this->renderMessages($namespace, $messages, array(
            'alert',
            'alert-danger'
        ));

        // Render INFO
        $messages = $flashMessenger->getMessagesFromNamespace(PluginFlashMessenger::NAMESPACE_INFO);
        $this->setMessageOpenFormat($this->messageOpenFormatTemplate . $this->infoIconTemplate);
        $return .= $this->renderMessages($namespace, $messages, array(
            'alert',
            'alert-info'
        ));

        // Render WARNING
        $messages = $flashMessenger->getMessagesFromNamespace(PluginFlashMessenger::NAMESPACE_WARNING);
        $this->setMessageOpenFormat($this->messageOpenFormatTemplate . $this->warningIconTemplate);
        $return .= $this->renderMessages($namespace, $messages, array(
            'alert',
            'alert-warning'
        ));

        $return .= '</div>';

        return $return;
    }
}

