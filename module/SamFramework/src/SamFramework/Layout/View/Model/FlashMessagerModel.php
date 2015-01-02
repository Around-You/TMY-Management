<?php
namespace SamFramework\Layout\View\Model;

use Zend\View\Model\ViewModel;

class FlashMessagerModel extends ViewModel
{
    /**
     * Template to use when rendering this model
     *
     * @var string
     */
    protected $template = 'SamFramework/layout/flashmessager';
    protected $terminate = true;
}

