<?php namespace Bombozama\LinkCheck\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Bombozama\LinkCheck\Models\BrokenLink;
use Flash;
use Lang;

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
        Flash::warning(Lang::get('bombozama.linkcheck::lang.strings.working'));
        $brokenLinks = BrokenLink::processLinks();
        Flash::success(Lang::get('bombozama.linkcheck::lang.strings.total_links', ['number' => $brokenLinks]));

        return $this->listRefresh();
    }
}