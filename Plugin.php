<?php namespace Bombozama\LinkCheck;

use Bombozama\LinkCheck\Models\BrokenLink;
use System\Classes\PluginBase;
use Bombozama\LinkCheck\Models\Settings;
use Backend;

/**
 * LinkCheck Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Link Check',
            'description' => 'Checks database daily for broken links...',
            'author'      => 'Gonzalo Henríquez',
            'icon'        => 'icon-chain-broken'
        ];
    }

    public function registerPermissions()
    {
        return [
            'bombozama.linkcheck.settings' => [
                'tab'   => 'Misc',
                'label' => 'Manage Link check configuration',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Link Check Settings',
                'description' => 'Checks database daily for broken links.',
                'category'    => 'Link Check',
                'icon'        => 'icon-chain-broken',
                'class'       => 'Bombozama\LinkCheck\Models\Settings',
                'order'       => 410,
                'keywords'    => 'link url broken',
                'permissions' => ['bombozama.linkcheck.settings']
            ],
            'brokenlinks' => [
                'label'       => 'Link report',
                'description' => 'Shows list of broken links',
                'category'    => 'Link Check',
                'icon'        => 'icon-list',
                'url'         => Backend::url('bombozama/linkcheck/brokenlinks'),
                'order'       => 411,
                'keywords'    => 'link url broken',
                'permissions' => ['bombozama.linkcheck.settings']
            ],
        ];
    }

    // Please do https://octobercms.com/docs/setup/installation#crontab-setup
    public function registerSchedule($schedule)
    {
        $settings = Settings::instance();
        $schedule->call(function(){
            BrokenLink::processLinks();
        })->cron($settings->time);
    }
}
