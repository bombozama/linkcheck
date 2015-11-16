<?php namespace Bombozama\LinkCheck\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Bombozama\LinkCheck\Models\BrokenLink;
use Flash;

/**
 * Broken Links Back-end Controller
 */
class BrokenLinks extends Controller
{
    public $requiredPermissions = ['bombozama.linkcheck.view_brokenlinks'];

    public $implement = ['Backend.Behaviors.ListController'];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Bombozama.LinkCheck', 'brokenlinks');
    }

    public function onRefreshLinkList()
    {
        Flash::info('Processing links... Please wait.');

        $brokenLinks = BrokenLink::processLinks();
        Flash::success('A total of ' . $brokenLinks . ' broken links were found!');

        return $this->listRefresh();
    }
}