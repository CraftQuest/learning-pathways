<?php
/**
 * Learning Pathways plugin for Craft CMS 3.x
 *
 * Tracks the learning pathways of students.
 *
 * @link      https://craftquest.io
 * @copyright Copyright (c) 2019 Ryan Irelan
 */

namespace mijingo\learningpathways\controllers;

use mijingo\learningpathways\LearningPathways;

use Craft;
use craft\web\Controller;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Ryan Irelan
 * @package   LearningPathways
 * @since     0.0.1
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'save', 'delete'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/learning-pathways/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    public function actionSave() {
        // save the pathway to the current user

        // get current user data
        $currentUserId = craft::$app->user->getId();
        // get data
        $params =  craft::$app->request->getBodyParams();
        $saveData = array(
            'userId' => $currentUserId,
            'entryId' => $params['entryId'],
            'status' => 0,
            'siteId' => $params['siteId'],
        );

        $isEnrolled = LearningPathways::$plugin->learningPathwaysService->isEnrolled($saveData);

        if (!$isEnrolled)
        {
            $enroll =  LearningPathways::$plugin->learningPathwaysService->saveEnrollment($saveData);
        }

        return;
    }

    public function actionDelete() {
        // get current user data
        $currentUserId = craft::$app->user->getId();
        // get data
        $params =  craft::$app->request->getBodyParams();
        $saveData = array(
            'userId' => $currentUserId,
            'entryId' => $params['entryId'],
            'siteId' => $params['siteId'],
        );

        $unenroll =  LearningPathways::$plugin->learningPathwaysService->removeEnrollment($saveData);

        return;
    }


}
