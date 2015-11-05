<?php namespace Bombozama\LinkCheck\Models;

use Model;
use Bombozama\LinkCheck\Classes\Helper;

class BrokenLink extends Model
{
    public $table = 'bombozama_linkcheck_broken_links';

    protected $fillable = ['model', 'model_id', 'field', 'url', 'status'];

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
}