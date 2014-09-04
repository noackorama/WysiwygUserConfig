<?php
/*
 * WysiwygUserConfig.php
 * Copyright (c) 2014  André Noack <noack@data-quest.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

class WysiwygUserConfig extends StudipPlugin implements SystemPlugin
{

    /**
     * Initialize a new instance of the plugin.
     */
    function __construct()
    {
        parent::__construct();
        if (Config::get()->WYSIWYG && is_object($GLOBALS['user'])) {
            if (strpos($_SERVER['REQUEST_URI'], 'dispatch.php/settings/general') !== false) {
                if (Request::submitted('forced_language')) {
                    $GLOBALS['user']->cfg->store('WYSIWYG_DISABLE', Request::submitted('wysiwyg_user_config'));
                }
                $snippet = '
                <tr>
                    <td>
                        <label for="wysiwyg_user_config">
                            WYSIWYG Editor ausschalten<br>
                            <dfn id="cookie_auth_token_description">
                                Mit dieser Einstellung können Sie den Stud.IP WYSIWYG Editor ausschalten. Dadurch müssen Sie ggf. Texte in HTML schreiben.
                            </dfn>
                        </label>
                    </td>
                    <td>
                        <input type="checkbox" value="1" aria-describedby="wysiwyg_user_config" id="wysiwyg_user_config" name="wysiwyg_user_config" ' . ($GLOBALS['user']->cfg->WYSIWYG_DISABLE ? 'checked' : '') .'>
                    </td>
                </tr>';

                 $snippet = jsready($snippet, 'script-double');
                 PageLayout::addHeadElement('script', array('type' => 'text/javascript'),"jQuery(function (\$) {\$('#main_content tbody tr').first().after('$snippet');});");
            }
            if (!(Config::get()->WYSIWYG = !$GLOBALS['user']->cfg->WYSIWYG_DISABLE)) {
                $old_packages = array_flip(PageLayout::getSqueezePackages());
                unset($old_packages['wysiwyg']);
                call_user_func_array('PageLayout::setSqueezePackages', array_values(array_flip($old_packages)));
            }
        }
    }
}

?>
