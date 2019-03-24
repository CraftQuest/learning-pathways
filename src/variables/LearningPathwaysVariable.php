<?php
/**
 * Learning Pathways plugin for Craft CMS 3.x
 *
 * Tracks the learning pathways of students.
 *
 * @link      https://craftquest.io
 * @copyright Copyright (c) 2019 Ryan Irelan
 */

namespace mijingo\learningpathways\variables;

use mijingo\learningpathways\LearningPathways;

use Craft;

/**
 * Learning Pathways Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.learningPathways }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Ryan Irelan
 * @package   LearningPathways
 * @since     0.0.1
 */
class LearningPathwaysVariable
{
    // Public Methods
    // =========================================================================


    /**
     * @param $userData
     * @return bool
     */
    public function isEnrolled($userData)
    {
        $result = LearningPathways::$plugin->learningPathwaysService->isEnrolled($userData);
        return $result;
    }

    /**
     * @return bool
     */
    public function hasEnrollment()
    {
        $result = LearningPathways::$plugin->learningPathwaysService->hasEnrollment();
        return $result;
    }

    /**
     * @return array
     */
    public function getUserPathways()
    {
        $result = LearningPathways::$plugin->learningPathwaysService->getUserPathways();
        return $result;
    }

    /**
     * @param $pathwayEntryId
     * @return bool
     */
    public function isPathwayComplete($pathwayEntryId)
    {
        $result = LearningPathways::$plugin->learningPathwaysService->isPathwayComplete($pathwayEntryId);

        return $result;
    }

}
