<?php namespace Bombozama\LinkCheck\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Broken Links Back-end Controller
 */
class BrokenLinks extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bombozama.LinkCheck', 'linkcheck', 'brokenlinks');
    }
}