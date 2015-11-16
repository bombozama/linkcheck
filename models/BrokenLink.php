<?php namespace Bombozama\LinkCheck\Models;

use Model;
use Bombozama\LinkCheck\Classes\Helper;
use Bombozama\LinkCheck\Models\Settings;
use Cms\Classes\Theme;
use File;

class BrokenLink extends Model
{
    public $table = 'bombozama_linkcheck_broken_links';

    protected $fillable = ['model', 'plugin', 'model_id', 'field', 'url', 'status'];

    /**
     * Checks to see if links return selected response codes (in settings)
     * @param $url: the link url that should be checked
     * @return bool|string
     */
    public static function isBrokenLink($url)
    {
        $response = Helper::getResponseCode($url);

        $settings = Settings::instance();
        $report = [];

        if(in_array(200, $settings->codes))
            for($i=200;$i<=206;$i++) $report[] = $i;
        if(in_array(300, $settings->codes))
            for($i=300;$i<=308;$i++) $report[] = $i;
        if(in_array(400, $settings->codes))
            for($i=400;$i<=431;$i++) $report[] = $i;
        if(in_array(500, $settings->codes))
            for($i=500;$i<=511;$i++) $report[] = $i;

        if (in_array($response, $report))
            return $response;

        return false;
    }

    /**
     * Checks for broken links in selected database fields and/or all CMS files
     * @return void
     */
    public static function processLinks()
    {
        # Let's start by truncating the BrokenLinks table
        BrokenLink::truncate();
        $brokenLinks = [];
        $settings = Settings::instance();
        foreach($settings->modelators as $el) {
            list($modelName, $field) = explode('::', $el['modelator']);
            $models = $modelName::whereNotNull($field)->get();
            foreach($models as $model){
                $urls = Helper::scanForUrls($model->$field);
                $modelParts = explode('\\', $modelName);
                foreach($urls as $url){
                    $status = BrokenLink::isBrokenLink($url);
                    if($status)
                        $brokenLinks[] = [
                            'status'    => $status,
                            'plugin'    => $modelParts[1] . '.' . $modelParts[2],
                            'model'     => array_pop($modelParts),
                            'model_id'  => $model->id,
                            'field'     => $field,
                            'url'       => $model->$field
                        ];
                }
            }
        }

        /**
         * Go process the current theme
         */
        $theme = Theme::getActiveTheme();
        $theme->getPath();

        /**
         * Should we process theme pages?
         */
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
                                'plugin'    => 'CMS',
                                'model'     => str_replace($theme->getPath() . DIRECTORY_SEPARATOR, '', $filePath),
                                'url'       => $url
                            ];
                    }
                }
            }

        /**
         * Lets seed the BrokenLink table with any and all found links.
         */
        foreach($brokenLinks as $brokenLink)
            BrokenLink::create($brokenLink);

        return count($brokenLinks);
    }
}