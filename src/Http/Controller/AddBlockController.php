<?php namespace Visiosoft\AddblockExtension\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Visiosoft\AddblockExtension\Command\addBlock;

class AddBlockController extends PublicController
{
    public function getBlock()
    {
        $html = "";
        if ($this->request->location) {
            $location = $this->request->location;
            $html = new addBlock($location, $this->request->params);
            $html = $html->handle();
        }
        return $this->response->json(['html' => $html]);
    }
}
