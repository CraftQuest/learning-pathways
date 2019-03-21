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
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.learningPathways.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.learningPathways.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }

    public function isEnrolled($userData)
    {
        $result = LearningPathways::$plugin->learningPathwaysService->isEnrolled($userData);
        return $result;
    }
    
    public function hasEnrollment()
    {
        $result = LearningPathways::$plugin->learningPathwaysService->hasEnrollment();
        return $result;
    }

    public function getUserPathways()
    {
        $result = LearningPathways::$plugin->learningPathwaysService->getUserPathways();
        return $result;
    }

}
