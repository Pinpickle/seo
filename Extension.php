<?php

namespace Bolt\Extension\BobdenOtter\Seo;

use Bolt\Application;
use Bolt\BaseExtension;

require_once('Seo.php');
require_once('src/SEO.php');

class Extension extends BaseExtension
{

    private $version = "v0.9.1";

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app['config']->getFields()->addField(new SEOField());
    }

    public function initialize() {

        $end = $this->app['config']->getWhichEnd();

        if ($end =='backend') {
            
            // For Bolt 2.0 - 2.2
            $this->app['htmlsnippets'] = true;

            // For Bolt 2.3 +
            $this->app->before(function(Request $request){
                $request->attributes->set('allow_snippets', true);
            });
            
            $this->addCss('assets/seo.css');
            // $this->addJavascript('assets/seo.js', true);
        }

        $this->app['twig.loader.filesystem']->prependPath(__DIR__."/twig");

        $this->app['twig']->addGlobal('seoconfig', $this->config);

        if ($end == 'frontend') {
            $seo = new SEO($this->app, $this->config, $this->version);
            $this->app['twig']->addGlobal('seo', $seo);
        }

    }

    public function getName()
    {
        return "seo";
    }

}
