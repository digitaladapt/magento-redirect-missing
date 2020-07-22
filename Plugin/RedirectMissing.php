<?php

namespace DigitalAdapt\MagentoRedirectMissing\Plugin;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem\DirectoryList;

class RedirectMissing
{
    /** @var ResultFactory */
    protected $redirect;

    /** @var DirectoryList */
    protected $directory;

    public function __construct(ResultFactory $redirect, DirectoryList $directory)
    {
        $this->redirect  = $redirect;
        $this->directory = $directory;
    }

    public function afterExecute($context, $result)
    {
        if (method_exists($context, 'getResponse')) {
            /* get the file extension of the uri, will be blank for extensionless filenames, such as directories */
            $extension = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_EXTENSION);

            /* if extension doesn't contains "htm" and isn't a blank string (directory) */
            if (!(stripos($extension, 'htm') !== false || $extension === '')) {
                /* original plan of checking http status won't work, since it's always 200, even when 404 is sent:
                 * `$context->getResponse->getHttpResponseCode()` is *always* 200 */
                $settings = json_decode(file_get_contents($this->directory->getRoot() . DIRECTORY_SEPARATOR . 'redirect-missing.json'));
                if (isset($settings->enabled, $settings->redirect) && $settings->enabled) {
                    $redirect = $this->redirect->create(ResultFactory::TYPE_REDIRECT);
                    $redirect->setUrl($settings->redirect . $_SERVER['REQUEST_URI']);
                    return $redirect;
                }
            }
        }
        return $result;
    }
}