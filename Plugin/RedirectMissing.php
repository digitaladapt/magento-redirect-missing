<?php

namespace DigitalAdapt\MagentoRedirectMissing\Plugin;

use Magento\Framework\Filesystem\DirectoryList;

class RedirectMissing
{
    /** @var DirectoryList */
    protected $directory;

    public function __construct(DirectoryList $directory)
    {
        $this->directory = $directory;
    }

    public function afterLunch($result)
    {
        if ($result->getHttpResponseCode() === 404) {
            $settings = json_decode(file_get_contents($this->directory->getRoot() . DIRECTORY_SEPARATOR . 'redirect-missing.json'));
            if (isset($settings->enabled, $settings->redirect) && $settings->enabled) {
                $result->setHttpResponseCode(302);
                $result->setBody('');
                $result->setHeader('Location', "{$settings->redirect}{$_SERVER['REQUEST_URI']}");
            }
        }
        return $result;
    }
}