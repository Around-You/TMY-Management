<?php
namespace Components\Layout\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Sidebar extends AbstractHelper
{

    public $escapeHtml;

    public $currentItem;

    public $menuItems;

    public $shortcutItems;
    // protected $class='nav nav-list';

    // protected $navlistCloseString = '</li></ul>';
    // protected $navlistOpenFormat = '<ul%s><li%s>';
    // protected $navlistSeparatorString = '</li><li%s>';
    // protected $contentTemplete = '<span class="menu-text">%s</span>';
    // protected $iconTemplete = '<i%s></i>';
    // protected $linkTemplete = '<a href="%s">%s</a>';

    /**
     *
     * @return the $escapeHtml
     */
    public function getEscapeHtml()
    {
        if (! $this->escapeHtml) {
            $this->escapeHtml = $this->getView()->plugin('escapeHtml');
        }
        return $this->escapeHtml;
    }

    /**
     *
     * @return the $currentItem
     */
    public function getCurrentItem()
    {
        return $this->currentItem;
    }

    /**
     *
     * @return the $menuItems
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     *
     * @return the $shortcutItems
     */
    public function getShortcutItems()
    {
        return $this->shortcutItems;
    }

    /**
     *
     * @param field_type $currentItem
     */
    public function setCurrentItem($currentItem)
    {
        $this->currentItem = $currentItem;
    }

    /**
     *
     * @param field_type $menuItems
     */
    public function setMenuItems($menuItems)
    {
        $this->menuItems = $menuItems;
    }

    /**
     *
     * @param field_type $shortcutItems
     */
    public function setShortcutItems($shortcutItems)
    {
        $this->shortcutItems = $shortcutItems;
    }

    public function __invoke($menuItems, $currentItem)
    {
        $this->setMenuItems($menuItems);
        $this->setCurrentItem($currentItem);
        $content = $this->renderNavList();

        return $content;
    }

    public function renderNavList()
    {
        $html = '';
        $menuItems = $this->getMenuItems();
        foreach ($menuItems as $menuItem) {
            if (isset($menuItem['submenu'])) {
                $html .= $this->renderMenuItemWithSubmenu($menuItem);
            } else {
                $html .= $this->renderMenuItem($menuItem);
            }
        }

        return $html;

        // $markup = sprintf($this->getMessageOpenFormat(), ' class="' . implode(' ', $classes) . '"');
    }

    public function renderMenuItem($menuItem, $subMenu = FALSE)
    {
        $currentItem = $this->getCurrentItem();
        $escapeHtml = $this->getEscapeHtml();
        $liClass = '';
        if ($currentItem == $menuItem['title']) {
            $liClass = 'active';
        }
        $html = '<li class="' . $liClass . '">
                    <a href="' . $menuItem['url'] . '">';
        if ($subMenu) {
            $html .= '<i class="menu-icon fa fa-caret-right"></i>'.$escapeHtml($menuItem['title']);
        } else {
            $html .= '<i class="' . $menuItem['icon'] . '"></i>
        <span class="menu-text">' . $escapeHtml($menuItem['title']) . '</span>';
        }

        $html .= '</a>
                    <b class="arrow"></b>
                </li>';
        return $html;
    }

    public function renderMenuItemWithSubmenu($menuItem)
    {
        $currentItem = $this->getCurrentItem();
        $subItems = $menuItem['submenu'];
        $liClass = '';
        $subNavHtml = '';
        foreach ($subItems as $subMenuItem) {
            if ($currentItem == $subMenuItem['title']) {
                $liClass = 'active open';
            }
            $subNavHtml .= $this->renderMenuItem($subMenuItem,true);
        }
        $html = '<li class="' . $liClass . '">
                    <a href="#" class="dropdown-toggle">
                        <i class=""></i>
                        <span class="menu-text">
                            ' . $menuItem['title'] . '
                        </span>

                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">';
        $html .= $subNavHtml;
        $html .= '</ul></li>';
        return $html;
    }
}

