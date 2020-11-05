<?php

$finder = PhpCsFixer\Finder::create()
	->in(__DIR__)
	->name('*.php')
	->notName('.phpstorm.meta.php')
	->notName('_ide_helper.php')
	->ignoreDotFiles(true)
	->ignoreVCS(true);

$config = PhpCsFixer\Config::create()
	->setFinder($finder)
	->setRiskyAllowed(true)
	->setLineEnding("\n")
	->setRules([
		'@PSR1' => true,
		'@PSR2' => true,
    ]);

return $config;
