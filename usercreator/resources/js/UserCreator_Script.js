/**
 * User Creator plugin for Craft CMS
 *
 * User Creator JS
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UserCreator
 * @since     0.0.1
 */

$(function () {
    var $form = $('#js-userCreatorForm');

    if ($form.length && Craft.hasOwnProperty('ElevatedSessionForm')) {
        // Require an elevated session when submitting a form new
        new Craft.ElevatedSessionForm('#container');
    }

    if (window.hasOwnProperty('userCreatorCopyData')) {
        var userData = window.userCreatorCopyData;
        var text = '';

        $.each(userData, function (index, user) {
            text += '';

            if (user.name !== '') {
                text += Craft.t('Name') + ': ' + user.name + "\n";
            }

            text += Craft.t('E-mail') + ': ' + user.email + "\n";
            //text += 'Password: ' + user.password + "\n\n";

            if (user.resetUrl) {
                text += Craft.t('Set your password here') + ': ' + user.resetUrl + "\n\n";
            }
        });

        var clipboard = new Clipboard('.js-userCreatorCopyClipboard', {
            text: function (trigger) {
                return text;
            }
        });

        var clipboardResetUrls = new Clipboard('.js-copyResetUrl');
    }
});
