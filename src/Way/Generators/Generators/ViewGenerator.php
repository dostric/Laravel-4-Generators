<?php

namespace Way\Generators\Generators;

use Illuminate\Support\Pluralizer;

class ViewGenerator extends Generator {

    public $itemTemplate;

    /**
     * Fetch the compiled template for a view
     *
     * @param  string $template Path to template
     * @param  string $name
     * @return string Compiled template
     */
    protected function getTemplate($template, $name)
    {
        $this->template = $this->file->get($template);

        if ($this->scaffold)
        {
            return $this->getScaffoldedTemplate($name);
        }

        // Otherwise, just set the file
        // contents to the file name
        return $name;
    }

    /**
     * Get the scaffolded template for a view
     *
     * @param  string $name
     * @return string Compiled template
     */
    protected function getScaffoldedTemplate($name)
    {
        $model = $this->cache->getModelName();  // post
        $models = Pluralizer::plural($model);   // posts
        $Models = ucwords($models);             // Posts
        $Model = Pluralizer::singular($Models); // Post

        // Create and Edit views require form elements
        if ($name === 'create.blade' or $name === 'edit.blade')
        {
            $formElements = $this->makeFormElements();

            $this->template = str_replace('{{formElements}}', $formElements, $this->template);

        }

        // Replace template vars in view
        foreach(array('model', 'models', 'Models', 'Model') as $var)
        {
            $this->template = str_replace('{{'.$var.'}}', $$var, $this->template);
        }

        // And finally create the table rows
        list($headings, $fields, $editAndDeleteLinks) = $this->makeTableRows($model);
        $this->template = str_replace('{{headings}}', implode(PHP_EOL."\t\t\t\t", $headings), $this->template);
        $this->template = str_replace('{{fields}}', implode(PHP_EOL."\t\t\t\t\t", $fields) . PHP_EOL . $editAndDeleteLinks, $this->template);

        if ($this->scaffold) {

            $this->template = str_replace('{{view_parent}}', $this->view_parent, $this->template);
            $this->template = str_replace('{{view_section}}', $this->view_section, $this->template);
            $this->template = str_replace('{{route_prefix}}', $this->route_prefix, $this->template);

        }

        return $this->template;
    }

    /**
     * Create the table rows
     *
     * @param  string $model
     * @return Array
     */
    protected function makeTableRows($model)
    {
        $models = Pluralizer::plural($model); // posts
        $route_prefix = $this->scaffold ? $this->route_prefix : '';

        $fields = $this->cache->getFields();

        // First, we build the table headings
        $headings = array_map(function($field) {
            return '<th>' . ucwords($field) . '</th>';
        }, array_keys($fields));

        // And then the rows, themselves
        $fields = array_map(function($field) use ($model) {
            return "<td>{{{ \$$model->$field }}}</td>";
        }, array_keys($fields));

        // Now, we'll add the edit and delete buttons.
        $editAndDelete = <<<EOT
                    <td>{{ link_to_route('{$route_prefix}{$models}.edit', 'Edit', array(\${$model}->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('{$route_prefix}{$models}.destroy', \${$model}->id), 'class'=>'form-horizontal')) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
EOT;




        return array($headings, $fields, $editAndDelete);
    }

    /**
     * Add Laravel methods, as string,
     * for the fields
     *
     * @return string
     */
    public function makeFormElements()
    {
        $formMethods = array();

        $itemTemplate = $this->itemTemplate ? file_get_contents($this->itemTemplate) :'
            <li>
                $label
                $element
            </li>
            ';

        foreach($this->cache->getFields() as $name => $type)
        {
            $formalName = ucwords($name);

            // TODO: add remaining types
            switch($type)
            {
                case 'integer':
                    $element = "{{ Form::input('number', '$name', null, array('class' => 'form-control')) }}";
                    break;

                case 'text':
                    $element = "{{ Form::textarea('$name', null, array('class' => 'form-control')) }}";
                    break;

                case 'boolean':
                    $element = "{{ Form::checkbox('$name') }}";
                    break;

                default:
                    $element = "{{ Form::text('$name', null, array('class' => 'form-control')) }}";
                    break;
            }

            if ($this->itemTemplate) {
                $label = $formalName;
            } else {
                $label = "{{ Form::label('$name', '$formalName:') }}";
            }

            // Now that we have the correct $element,
            // We can build up the HTML fragment
            $frag = $itemTemplate;
            foreach(array('name', 'label', 'element') as $item) {
                $frag = str_replace("$".$item, $$item, $frag);
            }


            $formMethods[] = $frag;
        }

        return implode(PHP_EOL, $formMethods);
    }

}
