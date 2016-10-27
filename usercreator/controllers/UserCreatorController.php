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

        $hasError           = craft()->userSession->hasFlash('error');
        $users              = craft()->userSession->getFlash('users');
        $groupIds           = craft()->userSession->getFlash('groupIds');
        $createResetUrls    = $hasError ? craft()->userSession->getFlash('createResetUrls') : true;
        $activateUsers      = $hasError ? craft()->userSession->getFlash('activateUsers') : true;
        $forcePasswordReset = $hasError ? craft()->userSession->getFlash('forcePasswordReset') : true;

        $this->renderTemplate('usercreator/UserCreator_Index', [
            'groups'             => craft()->userGroups->getAllGroups(),
            'users'              => $users,
            'createResetUrls'    => $createResetUrls,
            'activateUsers'      => $activateUsers,
            'forcePasswordReset' => $forcePasswordReset,
            'groupIds'           => $groupIds,
        ]);
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
        $this->requirePostRequest();

        // Require elevated session
        if ( (int)craft()->getVersion() >= 2 && (int)craft()->getBuild() >= 2784 ) {
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
            //'client'    => false,
            'admin'                 => false,
            'archived'              => false,
            'locked'                => false,
            'suspended'             => false,
            'pending'               => !$activateUsers,
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

            if ( !$userModel->validate() ) {
                $errors = $userModel->getErrors();
                foreach ($errors as $attribute => $errors) {
                    craft()->userSession->setError($errors[0]);
                }

                craft()->userSession->setFlash('users', $users);
                craft()->userSession->setFlash('activateUsers', $activateUsers);
                craft()->userSession->setFlash('forcePasswordReset', $forcePasswordReset);
                craft()->userSession->setFlash('createResetUrls', $createResetUrls);
                craft()->userSession->setFlash('groupIds', $groupIds);

                return $this->redirect('usercreator');
            }

            if ( craft()->users->saveUser($userModel) ) {

                if ( $createResetUrls ) {
                    $resetUrl = craft()->users->getPasswordResetUrl($userModel);
                }

                $createdUsers[] = array_merge($userModel->getAttributes(null, $flatten = true), [
                    'name'     => $userModel->getName(),
                    'password' => $randomPassword,
                    'resetUrl' => $resetUrl
                ]);

                // Assign users
                craft()->userGroups->assignUserToGroups($userModel->id, $groupIds);

            }
            else {
                $errors = $userModel->getErrors();
                foreach ($errors as $attribute => $errors) {
                    craft()->userSession->setError($errors[0]);
                }
                
                craft()->userSession->setFlash('users', $users);
                craft()->userSession->setFlash('activateUsers', $activateUsers);
                craft()->userSession->setFlash('forcePasswordReset', $forcePasswordReset);
                craft()->userSession->setFlash('createResetUrls', $createResetUrls);
                craft()->userSession->setFlash('groupIds', $groupIds);

                return $this->redirect('usercreator');
            }

        }

        craft()->userSession->setFlash('notice', $message);
        craft()->userSession->setFlash('userCreatorResult', $createdUsers);
        craft()->userSession->setFlash('createResetUrls', $createResetUrls);

        $this->redirect('usercreator/result');
        //$this->returnJson($createdUsers);
    }
}
