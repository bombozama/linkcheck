<?php namespace Bombozama\LinkCheck;

use Bombozama\LinkCheck\Models\BrokenLink;
use System\Classes\PluginBase;
use Bombozama\LinkCheck\Models\Settings;
use Backend;
use Lang;

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
            'name'        => 'bombozama.linkcheck::lang.details.name',
            'description' => 'bombozama.linkcheck::lang.details.description',
            'author'      => 'Gonzalo Henríquez',
            'icon'        => 'icon-chain-broken',
            'homepage'    => 'https://github.com/bombozama/linkcheck'
        ];
    }

    public function registerPermissions()
    {
        return [
            'bombozama.linkcheck.manage' => [
                'tab'   => 'bombozama.linkcheck::lang.plugin.tab',
                'label' => 'bombozama.linkcheck::lang.plugin.manage',
            ],
            'bombozama.linkcheck.view' => [
                'tab'   => 'bombozama.linkcheck::lang.plugin.tab',
                'label' => 'bombozama.linkcheck::lang.plugin.view',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'bombozama.linkcheck::lang.menu.settings.label',
                'description' => 'bombozama.linkcheck::lang.menu.settings.description',
                'category'    => 'bombozama.linkcheck::lang.plugin.category',
                'icon'        => 'icon-chain-broken',
                'class'       => 'Bombozama\LinkCheck\Models\Settings',
                'order'       => 410,
                'permissions' => ['bombozama.linkcheck.manage']
            ],
            'brokenlinks' => [
                'label'       => 'bombozama.linkcheck::lang.menu.brokenlinks.label',
                'description' => 'bombozama.linkcheck::lang.menu.brokenlinks.description',
                'category'    => 'bombozama.linkcheck::lang.plugin.category',
                'icon'        => 'icon-list',
                'url'         => Backend::url('bombozama/linkcheck/brokenlinks'),
                'order'       => 411,
                'permissions' => ['bombozama.linkcheck.view']
            ],
        ];
    }

    public function registerListColumnTypes()
    {
        return [
            'httpstatus' => [$this, 'httpStatus'],
        ];
    }

    public function httpStatus($value, $column, $record)
    {
        return '<span title="' . Lang::get('bombozama.linkcheck::lang.codes.' . $value ) . '">' . $value . '</span>';
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
