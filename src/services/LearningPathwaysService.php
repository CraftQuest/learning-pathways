<?php
/**
 * Learning Pathways plugin for Craft CMS 3.x
 *
 * Tracks the learning pathways of students.
 *
 * @link      https://craftquest.io
 * @copyright Copyright (c) 2019 Ryan Irelan
 */

namespace mijingo\learningpathways\services;

use mijingo\learningpathways\LearningPathways;

use Craft;
use craft\base\Component;
use craft\db\Query;

/**
 * LearningPathwaysService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Ryan Irelan
 * @package   LearningPathways
 * @since     0.0.1
 */
class LearningPathwaysService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     LearningPathways::$plugin->learningPathwaysService->exampleService()
     *
     * @return mixed
     */

    public function isEnrolled($data) {

        // check if student is already enrolled in this pathway
        $count = (new Query())
            ->select(['entryId', 'userId'])
            ->from(['{{%learningpathways_learningpathwaysrecord}}'])
            ->where(['entryId' => $data['entryId'], 'userId' => craft::$app->user->getId()])
            ->count();

        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function hasEnrollment() {
        // check if the student is enrolled in at least one pathway
        $count = (new Query())
            ->select(['userId', 'status'])
            ->from(['{{%learningpathways_learningpathwaysrecord}}'])
            ->where(['userId' => craft::$app->user->getId(), 'status' => 0])
            ->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }


    public function saveEnrollment($data)
    {
        $result = \Craft::$app->db->createCommand()
            ->insert('{{%learningpathways_learningpathwaysrecord}}', $data)
            ->execute();

        return;
    }

    public function getUserPathways()
    {
        // returns the incomplete pathways this user is enrolled in
        $result = (new Query())
            ->select(['entryId'])
            ->from(['{{%learningpathways_learningpathwaysrecord}}'])
            ->where(['userId' => craft::$app->user->getId(), 'status' => 0])
            ->all();

        return $result;
    }
}
