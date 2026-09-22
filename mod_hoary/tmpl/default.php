<?php
/**
 * @package     Hoary Module
 * @version     1.2
 * @license     GNU General Public License version 2
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$document = $app->getDocument();
$input = $app->input;
$cookie = $input->cookie;
$cookieValue = $cookie->get('jHoaryMode', 'false', 'word');
$isDark = ($cookieValue === 'true') ? 'true' : 'false';

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $document->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_hoary');
$wa->useScript('mod_hoary.hoary');
$wa->useStyle('mod_hoary.hoary-dark-mode');

// Anti light mode FOUC script configuration
$antiFoucScript = "
function antiFouc() {
    if ($isDark) {
        document.documentElement.setAttribute('data-hoary-theme', 'dark');
    }
}
antiFouc();
";

// Render this inline script safely BEFORE the mod_hoary.hoary asset loads
$wa->addInlineScript($antiFoucScript, ['position' => 'before'], [], ['mod_hoary.hoary']);

// Pass parameters to Javascript
$document->addScriptOptions(
    'mod_hoary.vars',
    [
        'iconStyle' => (int) $iconStyle,
    ]
);

Text::script('MOD_HOARY_DARK');
Text::script('MOD_HOARY_LIGHT');
?>
<button type="button" class="header-item-content hoary-button">
    <span class="header-item-icon"><span>🌓</span></span>

    <?php if ($switcherLabel == 0) : ?>
        <span class="header-item-text">
            <?php echo Text::_('MOD_HOARY_SWITCHER'); ?>
        </span>
    <?php endif; ?>
</button>