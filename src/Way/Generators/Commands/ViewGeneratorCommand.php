<?php namespace Way\Generators\Commands;

use Way\Generators\Generators\ViewGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ViewGeneratorCommand extends BaseGeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new view.';

    /**
     * Model generator instance.
     *
     * @var \Way\Generators\Generators\ViewGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @param \Way\Generators\Generators\ViewGenerator $generator
     * @return \Way\Generators\Commands\ViewGeneratorCommand
     */
    public function __construct(ViewGenerator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    public function fire() {

        if ($this->option('scaffold') == 'true') {

            //not suitable
            //$this->generator = new \ViewGeneratorBootstrap($this->generator->file, $this->generator->cache);

            $this->generator->scaffold = true;
            $this->generator->view_parent = $this->option('view_parent');
            $this->generator->view_section = $this->option('view_section');
            $this->generator->route_prefix = $this->option('route_prefix');
            $this->generator->itemTemplate = $this->option('itemTemplate');

        }

        $this->generator->force_delete = $this->option('force_delete') == true ? true : false;

        parent::fire();

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Name of the view to generate.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('path', null, InputOption::VALUE_OPTIONAL, 'Path to views directory.', app_path() . '/views'),
            array('template', null, InputOption::VALUE_OPTIONAL, 'Path to template.', __DIR__.'/../Generators/templates/view.txt'),
            array('scaffold', null, InputOption::VALUE_OPTIONAL, 'Perform scaffold operation', 'false'),
            array('view_parent', null, InputOption::VALUE_OPTIONAL, 'Skin to extend', 'layouts.scaffold'),
            array('view_section', null, InputOption::VALUE_OPTIONAL, 'Section name in the view', 'main'),
            array('route_prefix', null, InputOption::VALUE_OPTIONAL, 'Route prefix', ''),
            array('force_delete', null, InputOption::VALUE_OPTIONAL, 'Force file overwriting', 'false'),
            array('itemTemplate', null, InputOption::VALUE_OPTIONAL, 'Defines item template path. Not supposed to used in cmd.', ''),
        );
    }

}
