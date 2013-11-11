<?php namespace Way\Generators\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


/**
 * Class BaseGeneratorCommand
 * @package Way\Generators\Commands
 *
 * @property $generator
 */
class BaseGeneratorCommand extends Command {

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $path = $this->getPath();
        $template = $this->option('template');

        $this->printResult($this->generator->make($path, $template), $path);
    }

    /**
     * Provide user feedback, based on success or not.
     *
     * @param  boolean $successful
     * @param  string $path
     * @return void
     */
    protected function printResult($successful, $path)
    {
        if ($successful)
        {
            $this->info("Created {$path}");
        }
            else
        {
            $this->error("Could not create {$path}");
        }

    }

    /**
     * Get the path to the file that should be generated.
     *
     * @return string
     */
    protected function getPath()
    {
       return $this->option('path') . '/' . strtolower($this->argument('name')) . '.blade.php';
    }

}