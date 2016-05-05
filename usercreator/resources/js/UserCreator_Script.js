/**
 * User Creator plugin for Craft CMS
 *
 * User Creator JS
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UserCreator
 * @since     1.0.0
 */

$(function () {
    // If this Craft version supports elevated sessions
    if (Craft.hasOwnProperty('ElevatedSessionForm')) {
        // Require an elevated session when submitting a form new
        Craft.ElevatedSessionForm('#container');
    }

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
});
