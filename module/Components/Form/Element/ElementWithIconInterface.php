<?php
namespace Components\Form\Element;


interface ElementWithIconInterface
{
    const ICON_OK = 'icon-ok';
    const ICON_CANCEL = 'icon-arrow-left';
    const ICON_SUBMIT = 'icon-arrow-right';

    public function getDefaultIcon();
}

