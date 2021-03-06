<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2015 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU Affero General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
     * details.
     *
     * You should have received a copy of the GNU Affero General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 27 North Wacker Drive
     * Suite 370 Chicago, IL 60606. or at email address contact@zurmo.com.
     *
     * The interactive user interfaces in original and modified versions
     * of this program must display Appropriate Legal Notices, as required under
     * Section 5 of the GNU Affero General Public License version 3.
     *
     * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
     * these Appropriate Legal Notices must retain the display of the Zurmo
     * logo and Zurmo copyright notice. If the display of the logo is not reasonably
     * feasible for technical reasons, the Appropriate Legal Notices must display the words
     * "Copyright Zurmo Inc. 2015. All rights reserved".
     ********************************************************************************/

    /**
     * Class that builds demo missions.
     */
    class MissionsDemoDataMaker extends DemoDataMaker
    {
        protected $ratioToLoad = 1;

        public static function getDependencies()
        {
            return array('users', 'groups');
        }

        /**
         * @param DemoDataHelper $demoDataHelper
         */
        public function makeAll(& $demoDataHelper)
        {
            assert('$demoDataHelper instanceof DemoDataHelper');
            assert('$demoDataHelper->isSetRange("User")');

            $missions = array();
            foreach (self::getMissionData() as $randomMissionData)
            {
                $postData               = array();
                $mission                = new Mission();
                $mission->setScenario('importModel');
                $mission->status        = Mission::STATUS_AVAILABLE;
                $mission->owner         = $demoDataHelper->getRandomByModelName('User');
                $mission->createdByUser = $mission->owner;
                $mission->description   = $randomMissionData['description'];
                $mission->reward        = $randomMissionData['reward'];
                //Add some comments
                foreach ($randomMissionData['comments'] as $commentDescription)
                {
                    $comment                = new Comment();
                    $comment->setScenario('importModel');
                    $comment->createdByUser = $demoDataHelper->getRandomByModelName('User');
                    $comment->description   = $commentDescription;
                    $mission->comments->add($comment);
                }
                $mission->addPermissions(Group::getByName(Group::EVERYONE_GROUP_NAME), Permission::READ_WRITE_CHANGE_PERMISSIONS_CHANGE_OWNER);
                $saved = $mission->save();
                assert('$saved');
                $mission = Mission::getById($mission->id);
                AllPermissionsOptimizationUtil::
                        securableItemGivenPermissionsForGroup($mission, Group::getByName(Group::EVERYONE_GROUP_NAME));
                $mission->save();
                $missions[] = $mission->id;
            }
            $demoDataHelper->setRangeByModelName('Mission', $missions[0], $missions[count($missions)-1]);
        }

        protected static function getMissionData()
        {
            $data = array(
                    array('description' => 'Can someone figure out a good location for the company party this year?',
                          'reward'      => 'Lunch on me',
                          'comments'    => array(
                              'How about at a museum?',
                              'I am going to be out of town, so I can\'t attend.',
                              'I guess i can take this on.',
                          )),
                    array('description' => 'Analyze server infrastructure, look for ways to save money',
                          'reward'      => 'Knowing you are an awesome person',
                          'comments'    => array(
                              'I don\'t even know what this mission is.  Guess I can\'t take it.',
                              'Always good to save money!',
                          )),
                    array('description' => 'Get tax document notarized ',
                          'reward'      => 'I will buy you dinner',
                          'comments'    => array(
                              'Can I go to a bank to do this?',
                              'Yes, a bank will notarize a document for you',
                          )),
                    array('description' => 'Organize the new marketing initiative for summer sales',
                          'reward'      => 'Starbucks 25 dollar gift card',
                          'comments'    => array(
                              'Is this for our consulting services?',
                              'No, this is for a new offering we will have around our widgets',
                          )),
            );
            return $data;
        }
    }
?>