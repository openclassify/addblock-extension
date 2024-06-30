<?php namespace Visiosoft\AddblockExtension;

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;
use Visiosoft\AddblockExtension\Command\addBlock;

class AddblockExtensionPlugin extends Plugin
{

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'addBlock',
                function ($location, $params = [], $addons = []) {

                    if (!$addBlock = $this->dispatchSync(new addBlock($location, $params, $addons))) {
                        return null;
                    }

                    return $addBlock;
                }
            )
        ];
    }
}
