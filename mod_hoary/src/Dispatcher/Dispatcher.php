<?php
/*
* @package    Hoary Module
* @version    1.1
* @license    GNU General Public License version 2 or later
*/

namespace Naftee\Module\Hoary\Site\Dispatcher;

//No direct access
\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;

class Dispatcher extends AbstractModuleDispatcher
    {
    /**
     * Returns the layout data.
     *
     * @return  array|false
     */
    protected function getLayoutData()
        {
        // Get base data (module, app, input, params and template)
        $data = parent::getLayoutData();

        // The parent getLayoutData() puts the module's Registry object into $data['params']
        $params = $data['params'];

        // Get module parameters
        $switcherLabel = (int) $params->get('switcher_label', 0);
        $iconStyle     = (int) $params->get('icon_style', 0);

        // Inject variables into the data array for the tmpl
        $data['switcherLabel'] = $switcherLabel;
        $data['iconStyle']     = $iconStyle;

        return $data;
        }
    }