<?php namespace Way\Generators\Generators;


use Illuminate\Support\Pluralizer;


/**
 * Class ControllerGenerator
 * @package Way\Generators\Generators
 *
 * @property $template
 */
class ControllerGenerator extends Generator {

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

        foreach(array('model', 'models', 'Models', 'Model', 'className') as $var)
        {
            $this->template = str_replace('{{'.$var.'}}', $$var, $this->template);
        }

        return $this->template;
    }
}
