<?php
namespace Components\Layout\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Header extends AbstractHelper
{


    /**
     * Returns site's base path, or file with base path prepended.
     *
     * $file is appended to the base path for simplicity.
     *
     * @param  string|null $file
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke($title, $subTitle = '')
    {
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $title = $escapeHtml($title);
        $html = '<div class="page-header">
    <h1>' . $title . '
        <small>' . $subTitle . '</small>
    </h1>
</div>';

        return $html;
    }


}

