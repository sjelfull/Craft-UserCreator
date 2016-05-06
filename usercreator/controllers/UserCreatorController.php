<?php
/**
 * User Creator plugin for Craft CMS
 *
 * UserCreator Controller
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UserCreator
 * @since     1.0.0
 */

namespace Craft;

class UserCreatorController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array();

    /**
     */
    public function actionIndex ()
    {
        $this->requireAdmin();

        $groups = craft()->userGroups->getAllGroups();
        $this->renderTemplate('usercreator/UserCreator_Index', [ 'groups' => $groups ]);
    }

    public function actionResult ()
    {
        $this->requireAdmin();

        $this->renderTemplate('usercreator/UserCreator_Result', [
            'createdUsers'    => craft()->userSession->getFlash('userCreatorResult'),
            'createResetUrls' => craft()->userSession->getFlash('createResetUrls'),
        ]);
    }

    public function actionCreate ()
    {
        $this->requireAdmin();

        // Require elevated session
        if ((int) craft()->getVersion() >= 2 && (int) craft()->getBuild() >= 2784) {
            $this->requireElevatedSession();
        }

        $users              = craft()->request->getRequiredPost('users');
        $activateUsers      = craft()->request->getRequiredPost('activateUsers');
        $forcePasswordReset = craft()->request->getRequiredPost('forcePasswordReset');
        $createResetUrls    = craft()->request->getRequiredPost('createResetUrls');
        $createdUsers       = [ ];
        $resetUrl           = null;

        $message = Craft::t('Created users. That was easy!');

        $extraAttributes = [
            'admin'                 => false,
            //'client'    => false,
            'locked'                => false,
            'suspended'             => false,
            'pending'               => !$activateUsers,
            'archived'              => false,
            'passwordResetRequired' => $forcePasswordReset,
        ];

        $groupIds = craft()->request->getPost('userGroups');

        foreach ($users as $user) {
            $userModel = new UserModel();

            $user = array_merge($user, $extraAttributes);

            foreach ($user as $key => $value) {
                $userModel->setAttribute($key, $value);
            }

            $randomPassword         = StringHelper::randomString(25);
            $userModel->newPassword = $randomPassword;

            craft()->users->saveUser($userModel);

            if ( $createResetUrls ) {
                $resetUrl = craft()->users->getPasswordResetUrl($userModel);
            }

            $createdUsers[] = array_merge($userModel->getAttributes(null, $flatten = true), [ 'name' => $userModel->getName(), 'password' => $randomPassword, 'resetUrl' => $resetUrl ]);

            // Assign users
            craft()->userGroups->assignUserToGroups($userModel->id, $groupIds);
        }

        craft()->userSession->setFlash('notice', $message);
        craft()->userSession->setFlash('userCreatorResult', $createdUsers);
        craft()->userSession->setFlash('createResetUrls', $createResetUrls);

        $this->redirect('usercreator/result');
        //$this->returnJson($createdUsers);
    }
}
