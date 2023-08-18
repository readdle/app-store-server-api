<?php

$finder = (new PhpCsFixer\Finder())->in(__DIR__);

return (new PhpCsFixer\Config())->setRules([
    '@PSR1' => true,
    '@PSR2' => true,
    'blank_line_after_opening_tag' => false,
])->setFinder($finder);
