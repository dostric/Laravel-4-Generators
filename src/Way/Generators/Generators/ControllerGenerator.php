<?php namespace Way\Generators\Generators;


use Illuminate\Support\Pluralizer;


/**
 * Class ControllerGenerator
 * @package Way\Generators\Generators
 *
 * @property $template
 */
class ControllerGenerator extends Generator {


    public $route_prefix = '';


    /**
     * Fetch the compiled template for a controller
     *
     * @param  string $template Path to template
     * @param string $className
     * @return string Compiled template
     */
    protected function getTemplate($template, $className)
    {
        $this->template = $this->file->get($template);

        $resource = strtolower(Pluralizer::plural(
            str_ireplace('Controller', '', $className)
        ));

        if ($this->scaffold)
        {
            $this->template = $this->getScaffoldedController($template, $className);
        }

        $template = str_replace('{{className}}', $className, $this->template);

        $fileAddons = str_replace('.php', '_addons.php', $this->path);
        if ($this->file->exists($fileAddons)) {

            $addons =  $this->file->get($fileAddons);
            $addons = explode('/* ADDON */', $addons);

            $addons = strlen($addons[1]) ? $addons[1] : '';
            $template = str_replace('/*ADDONs*/', $addons, $this->template);

        }

        return str_replace('{{collection}}', $resource, $template);
    }

    /**
     * Get template for a scaffold
     *
     * @param  string $template Path to template
     * @param $className
     * @return string
     */
    protected function getScaffoldedController($template, $className)
    {
        $model = $this->cache->getModelName();  // post
        $models = Pluralizer::plural($model);   // posts
        $Models = ucwords($models);             // Posts
        $Model = Pluralizer::singular($Models); // Post

        $route_prefix = $this->route_prefix;

        foreach(array('model', 'models', 'Models', 'Model', 'className', 'route_prefix') as $var)
        {
            $this->template = str_replace('{{'.$var.'}}', $$var, $this->template);
        }

        return $this->template;
    }
}
