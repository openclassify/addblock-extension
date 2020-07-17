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
        $installed_modules = $installed_modules->merge(app('extension.collection')->installed());

        $views = array();
        $params = $this->params;
        $requiresOrdering = array();
        foreach ($installed_modules as $item) {
            if (file_exists($item->path . "/resources/views/" . $this->location . ".twig")) {
                if (isset($this->params['_ORDER_'])) {
                    $order = array_search($item->namespace, $this->params['_ORDER_'], true);
                    if ($order !== false) {
                        $requiresOrdering[$order] = array(view($item->namespace . '::' . $this->location, compact('params')));
                    }
                } else {
                    $views[] = view($item->namespace . '::' . $this->location, compact('params'));
                }
            }
        }

        if (count($requiresOrdering)) {
            ksort($requiresOrdering);
            foreach ($requiresOrdering as $index => $view) {
                array_splice($views, $index, 0, $view);
            }
        }

        return implode('', $views);
    }
}
