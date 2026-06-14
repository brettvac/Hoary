<?php
/**
 * @package     Hoary Module
 * @version     1.0
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

// Inline style for the dark mode switcher
$style = '
:root {
    --template-text-light: #fff;
}

button.header-item-content.hoary-button {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: none;
    background: transparent;
    cursor: pointer;
    padding: 0;
}

button.header-item-content.hoary-button .header-item-icon span {
    margin: 3px;
    font-size: 1.25rem;
    transition: all .6s ease;
    border-radius: 4px;
    background-color: transparent; 
}

button.header-item-content.hoary-button .header-item-text {
    background-color: rgb(31, 48, 71);
    color: var(--template-text-light, #e9ecef);
    padding: 4px 8px;
    border-radius: 4px;
    transition: all .6s ease;
}

[data-hoary-theme="dark"] button.header-item-content.hoary-button .header-item-text {
    background-color: var(--template-text-light, #e9ecef);
    color: rgb(31, 48, 71);
}

button.header-item-content.hoary-button .header-item-icon i,
button.header-item-content.hoary-button .header-item-icon svg {
    font-size: 1.2rem;
    background-color: transparent;
    transition: all .6s ease;
    display: inline-block;
    text-align: center;
    width: 1.25em; 
}

[data-hoary-theme="dark"] button.header-item-content.hoary-button .header-item-icon i,
[data-hoary-theme="dark"] button.header-item-content.hoary-button .header-item-icon svg {
    color: var(--template-text-light, #e9ecef);
}

[data-hoary-theme="light"] button.header-item-content.hoary-button .header-item-icon i,
[data-hoary-theme="light"] button.header-item-content.hoary-button .header-item-icon svg {
    color: var(--warning, #ffdb6d);
}
';

// Add the inline style to the document via WebAssetManager
$wa->addInlineStyle($style);
?>
<button type="button" class="header-item-content hoary-button">
    <span class="header-item-icon"><span>🌓</span></span>

    <?php if ($switcherLabel == 0) : ?>
        <span class="header-item-text">
            <?php echo Text::_('MOD_HOARY_SWITCHER'); ?>
        </span>
    <?php endif; ?>
</button>