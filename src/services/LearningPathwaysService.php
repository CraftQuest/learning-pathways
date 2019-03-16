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
    public function exampleService()
    {
        $result = 'something';

        return $result;
    }
}
