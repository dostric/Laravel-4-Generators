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

            $this->generator->scaffold = true;

        }

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
            array('scaffold', null, InputOption::VALUE_OPTIONAL, 'Perform scaffold operation', 'false')
        );
    }

}
