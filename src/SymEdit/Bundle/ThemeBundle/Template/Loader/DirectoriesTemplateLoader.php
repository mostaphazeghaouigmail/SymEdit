<?php

/*
 * This file is part of the SymEdit package.
 *
 * (c) Craig Blanchette <craig.blanchette@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SymEdit\Bundle\ThemeBundle\Template\Loader;

use SymEdit\Bundle\ThemeBundle\Template\TemplateData;

class DirectoriesTemplateLoader implements TemplateLoaderInterface
{
    protected $directories;

    public function __construct($directories = array())
    {
        $this->directories = $directories;
    }

    public function loadTemplateData(TemplateData $templateData)
    {
        foreach ($this->directories as $directory) {
            $loader = new DirectoryTemplateLoader($directory);
            $loader->loadTemplateData($templateData);
        }
    }
}