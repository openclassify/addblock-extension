<?php namespace Visiosoft\AddblockExtension\Command;

class addBlock
{
    /**
     * @var $location
     */
    protected $location;

    /**
     * @var $params
     */
    protected $params;


    /**
     * @param $location
     * @param $params
     */
    public function __construct($location, $params)
    {
        $this->location = $location;
        $this->params = $params;
    }

    public function handle()
    {
        $installed_modules = app('module.collection')->installed();

        $views = "";
        $params = $this->params;
        foreach ($installed_modules as $item) {
            if (file_exists($item->path . "/resources/views/" . $this->location . ".twig")) {
                $views .= view($item->namespace . '::' . $this->location, compact('params'));
            }
        }
        return $views;

    }
}
