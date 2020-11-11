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
        foreach ($installed_modules as $item) {
            if (file_exists($item->path . "/resources/views/" . $this->location . ".twig")) {
                $views[] = [
                    'order' => 10,
                    'view' => view($item->namespace . '::' . $this->location, compact('params'))
                ];
            } elseif (count(glob($item->path . "/resources/views/" . $this->location . "_*.twig"))) {
                $order = glob($item->path . "/resources/views/" . $this->location . "_*.twig")[0];
                $location = str_replace('/', '\/', $this->location);
                preg_match('/' . $location . '_(.*?)\.twig/', $order, $match);
                $order = $match[1];
                $views[] = [
                    'order' => $order,
                    'view' => view(
                        $item->namespace . '::' . $this->location . '_' . $order,
                        compact('params')
                    )
                ];
            }
        }

        $ordered = array_column($views, 'order');
        array_multisort($ordered, SORT_ASC, $views);

        return implode('', array_column($views, 'view'));
    }
}
