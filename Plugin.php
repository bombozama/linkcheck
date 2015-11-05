<?php namespace Bombozama\LinkCheck;

use System\Classes\PluginBase;
use Bombozama\LinkCheck\Models\Settings;
use Bombozama\LinkCheck\Models\BrokenLink;
use Bombozama\LinkCheck\Classes\Helper;
use Cms\Classes\Theme;
use File;

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
                'category'    => 'Misc',
                'icon'        => 'icon-chain-broken',
                'class'       => 'Bombozama\LinkCheck\Models\Settings',
                'order'       => 500,
                'keywords'    => 'link url broken',
                'permissions' => ['bombozama.linkcheck.settings']
            ]
        ];
    }

    // Please do https://octobercms.com/docs/setup/installation#crontab-setup
    public function registerSchedule($schedule)
    {
        $settings = Settings::instance();
        $schedule->call(function(){
            $this->processLinks();
        })->cron($settings->time);
    }

    protected function processLinks()
    {
        # Let's start by truncating the BrokenLinks table
        BrokenLink::truncate();
        $brokenLinks = [];
        $settings = Settings::instance();
        foreach($settings->modelators as $el) {
            list($modelName, $field) = explode('::', $el['modelator']);
            $models = $modelName::whereNotNull($field)->get();
            foreach($models as $model){
                $status = BrokenLink::isBrokenLink($model->$field);
                if($status)
                    $brokenLinks[] = [
                        'status'    => $status,
                        'model'     => $modelName,
                        'model_id'  => $model->id,
                        'field'     => $field,
                        'url'       => $model->$field
                    ];
            }
        }

        # Go process the current theme
        $theme =  Theme::getActiveTheme();
        $theme->getPath();

        # Should we process theme pages?
        if($settings['checkCMS'] == '1')
            foreach(File::directories($theme->getPath()) as $themeSubDir){
                # Skip the assets folder
                if(basename($themeSubDir) == 'assets')
                    continue;

                foreach(File::allFiles($themeSubDir) as $filePath){
                    $urls = Helper::scanForUrls(file_get_contents($filePath));
                    foreach($urls as $url){
                        $status = BrokenLink::isBrokenLink($url);
                        if($status)
                            $brokenLinks[] = [
                                'status'    => $status,
                                'model'     => $filePath,
                                'url'       => $url
                            ];
                    }
                }
            }

        # Lets seed the BrokenLink table
        foreach($brokenLinks as $brokenLink)
            BrokenLink::create($brokenLink);
    }
}
