<?php
namespace Components\Layout\View\Model;

use Zend\View\Model\ViewModel;

class FlashMessagerModel extends ViewModel
{
    /**
     * Template to use when rendering this model
     *
     * @var string
     */
    protected $template = 'components/layout/flashmessager';
    protected $terminate = true;
}

