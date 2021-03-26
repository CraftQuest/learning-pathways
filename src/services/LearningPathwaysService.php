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
use craft\elements\Entry;
use craft\elements\MatrixBlock;

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
     * @param $data
     * @return bool
     */
    public function isEnrolled($data)
    {

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

    /**
     * @return bool
     */
    public function hasEnrollment()
    {
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


    /**
     * @param $data
     */
    public function saveEnrollment($data)
    {
        $result = \Craft::$app->db->createCommand()
            ->insert('{{%learningpathways_learningpathwaysrecord}}', $data)
            ->execute();

        return;
    }

    public function removeEnrollment($data)
    {
        $result = \Craft::$app->db->createCommand()
            ->delete('{{%learningpathways_learningpathwaysrecord}}', $data)
            ->execute();

        return;
    }

    /**
     * @return array
     */
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

    public function getUserPathwaysAsEntries($userId)
    {
        $pathways = (new Query())
            ->select(['entryId'])
            ->from(['{{%learningpathways_learningpathwaysrecord}}'])
            ->where(['userId' => craft::$app->user->getId(), 'status' => 0])
            ->all();

        $entryIds = [];

        foreach ($pathways as $pathway) {
            $entryIds[] = $pathway['entryId'];
        }

        $entries = implode(", ", $entryIds);

        return Entry::find()->section('learningPathways')->id($entryIds)->all();
    }

    /**
     * @param $pathwayEntryId
     * @return bool
     */
    public function isPathwayComplete($pathwayEntryId)
    {

        // get the courses in the pathway via the provided Entry ID
        $pathwayCourses = $this->_getCoursesInPathway($pathwayEntryId);

        // get the videos for the courses
        $pathwayCoursesIds = [];
        $allCourseVideos = [];
        $playedCourseVideos = [];

        // iterate over each course and get videos
        foreach ($pathwayCourses as $course) {
            if ($course->sectionId == 7) {

                $courseVideos = $this->_getCourseVideos($course->id);
                $playedVideos = $this->_getPlayedCourseVideos($course->id);

                // iterate over each video in a course and set to array with completed as false. Will update this later when we check if video is watched.
                foreach ($courseVideos as $courseVideo) {
                    $pathwayVideos[] = [
                        'videoId' => $courseVideo->id,
                        'completed' => 0
                    ];
                }

                foreach ($playedVideos as $pv) {
                    $playedCourseVideos[] = [
                        'videoId' => $pv['rowId'],
                        'completed' => 1
                    ];
                }
            }
        }

        $allVideos = array_merge($playedCourseVideos, $pathwayVideos);

        if (count($playedCourseVideos) > 0) {
            $completedCount = array_count_values(array_column($allVideos, 'completed'))[1];
            if ($completedCount == count($allVideos)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $pathwayEntryId
     * @return mixed
     */
    private function _getCoursesInPathway($pathwayEntryId)
    {
        $pathway = Entry::find()
            ->section('learningPathways')
            ->id($pathwayEntryId)
            ->with('pathwayCourses')
            ->one();

        return $pathway->pathwayCourses;
    }


    /**
     * @param $entryId
     * @return array
     */

    private function _getPlayedCourseVideos($entryId)
    {
        $playedCourseVideos = (new Query())
            ->select(['rowId', 'entryId', 'userId', 'siteId', 'status'])
            ->from(['{{%playtracker_playtrackerrecord}}'])
            ->where(['entryId' => $entryId, 'status' => 1, 'userId' => craft::$app->user->getId()])
            ->all();

        return $playedCourseVideos;
    }

    /**
     * @param $entryId
     * @return array|\craft\base\ElementInterface[]|MatrixBlock[]
     */
    private function _getCourseVideos($entryId)
    {
        $videos = MatrixBlock::find()
            ->ownerId($entryId)
            ->all();
        return $videos;
    }
}
