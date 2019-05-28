<?php

require __DIR__ . '/vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use Doctrine\Common\Inflector\Inflector;

class Command extends CLI
{
    // register options and arguments
    protected function setup(Options $options)
    {
        $options->setHelp('A very minimal example that does nothing but print a version');
        $options->registerOption('model', 'model name', 'm', 'model');
        $options->registerOption('path', 'write path name', 'm', 'path');
    }

    // implement your code
    protected function main(Options $options)
    {
        $this->ModelName = $options->getOpt('model');
        $Path = $options->getOpt('path') . '/';

        if (!$Path) {
            $Path = './';
        }

        $this->ClassifyModelName = Inflector::classify($this->ModelName); // Article
        $this->CamelizeModelName = Inflector::camelize($this->ModelName); // article
        $this->PluralizeModelName = Inflector::pluralize($this->CamelizeModelName); // articles
        $this->PluralizeModelNameU = Inflector::pluralize($this->ModelName); // Articles

        // Controller
        $content = file_get_contents('./stubs/Controller.php.stub');
        $content = $this->replace($content);
        $ControllerPath = $Path . '/app/Http/Controllers/';
        $ControllerFileName = $this->ClassifyModelName . 'Controller.php';
        file_put_contents($ControllerPath . $ControllerFileName , $content);

        // Policy
        $content = file_get_contents('./stubs/Policy.php.stub');
        $content = $this->replace($content);
        $PoliciesPath = $Path . '/app/Policies/';
        $PolicyFileName = $this->ClassifyModelName . 'Policy.php';
        file_put_contents($PoliciesPath . $PolicyFileName , $content);

        // Repository
        $content = file_get_contents('./stubs/Repository.php.stub');
        $content = $this->replace($content);
        $RepositoryPath = $Path . '/app/Repositories/';
        $RepositoryFileName = $this->ClassifyModelName . 'Repository.php';
        file_put_contents($RepositoryPath . $RepositoryFileName , $content);

        // Request Store
        $content = file_get_contents('./stubs/RequestStore.php.stub');
        $content = $this->replace($content);
        $RequestStorePath = $Path . '/app/Http/Requests/';
        $RequestStoreFileName = $this->ClassifyModelName . 'Store.php';
        file_put_contents($RequestStorePath . $RequestStoreFileName , $content);

        // Request Update
        $content = file_get_contents('./stubs/RequestUpdate.php.stub');
        $content = $this->replace($content);
        $RequestUpdatePath = $Path . '/app/Http/Requests/';
        $RequestUpdateFileName = $this->ClassifyModelName . 'Update.php';
        file_put_contents($RequestUpdatePath . $RequestUpdateFileName , $content);

        // Resource
        $content = file_get_contents('./stubs/Resource.php.stub');
        $content = $this->replace($content);
        $ResourcePath = $Path . '/app/Http/Resources/';
        $ResourceFileName = $this->ClassifyModelName . 'Resource.php';
        file_put_contents($ResourcePath . $ResourceFileName , $content);

        // Resource Collection
        $content = file_get_contents('./stubs/ResourceCollection.php.stub');
        $content = $this->replace($content);
        $ResourceCollectionPath = $Path . '/app/Http/Resources/';
        $ResourceCollectionFileName = $this->ClassifyModelName . 'ResourceCollection.php';
        file_put_contents($ResourceCollectionPath . $ResourceCollectionFileName , $content);
    }

    protected function replace($content)
    {
        $content = str_replace('{{ ClassifyModelName }}', $this->ClassifyModelName, $content);
        $content = str_replace('{{ CamelizeModelName }}', $this->CamelizeModelName, $content);
        $content = str_replace('{{ PluralizeModelName }}', $this->PluralizeModelName, $content);
        $content = str_replace('{{ PluralizeModelNameU }}', $this->PluralizeModelNameU, $content);
        return $content;
    }
}
// execute it
$cli = new Command();
$cli->run();
