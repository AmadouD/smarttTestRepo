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

    class WorkflowActionAttributeFormTest extends WorkflowBaseTest
    {
        public function testGetTypeValuesAndLabels()
        {
            $form            = new CheckBoxWorkflowActionAttributeForm('WorkflowModelTestItem', 'boolean');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new CurrencyValueWorkflowActionAttributeForm('WorkflowModelTestItem', 'currencyValue');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form            = new DateWorkflowActionAttributeForm('WorkflowModelTestItem', 'date');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(2, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(false, true);
            $this->assertEquals(3, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form            = new DateTimeWorkflowActionAttributeForm('WorkflowModelTestItem', 'dateTime');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(2, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(false, true);
            $this->assertEquals(3, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new DecimalWorkflowActionAttributeForm('WorkflowModelTestItem', 'float');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new DropDownWorkflowActionAttributeForm('WorkflowModelTestItem', 'dropDowns');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(false, true);
            $this->assertEquals(2, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new EmailWorkflowActionAttributeForm('WorkflowModelTestItem', 'email');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, false);
            $this->assertEquals(2, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new IntegerWorkflowActionAttributeForm('WorkflowModelTestItem', 'integer');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new ContactStateWorkflowActionAttributeForm('WorkflowModelTestItem', 'likeContactState');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new MultiSelectDropDownWorkflowActionAttributeForm('WorkflowModelTestItem', 'multiDropDown');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new PhoneWorkflowActionAttributeForm('WorkflowModelTestItem', 'phone');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, false);
            $this->assertEquals(2, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new RadioDropDownWorkflowActionAttributeForm('WorkflowModelTestItem', 'radioDropDown');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(false, true);
            $this->assertEquals(2, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new TagCloudWorkflowActionAttributeForm('WorkflowModelTestItem', 'tagCloud');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new TextWorkflowActionAttributeForm('WorkflowModelTestItem', 'text');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, false);
            $this->assertEquals(2, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new TextAreaWorkflowActionAttributeForm('WorkflowModelTestItem', 'textArea');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, false);
            $this->assertEquals(2, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new UrlWorkflowActionAttributeForm('WorkflowModelTestItem', 'url');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, false);
            $this->assertEquals(2, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form        = new UserWorkflowActionAttributeForm('WorkflowModelTestItem', 'user');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(2, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(false, true);
            $this->assertEquals(4, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());

            $form            = new ExplicitReadWriteModelPermissionsWorkflowActionAttributeForm('WorkflowModelTestItem', 'permissions');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(3, count($valuesAndLabels));
            $this->assertFalse($form->resolveValueBeforeSave());

            //Make new group and confirm it shows up
            $group = new Group();
            $group->name = 'test';
            $this->assertTrue($group->save());
            $form            = new ExplicitReadWriteModelPermissionsWorkflowActionAttributeForm('WorkflowModelTestItem', 'permissions');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(4, count($valuesAndLabels));

            //Test subscribeToList action form
            $form        = new MarketingListWorkflowActionAttributeForm('MarketingList', 'id');
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, true);
            $this->assertEquals(1, count($valuesAndLabels));
            $valuesAndLabels = $form->getTypeValuesAndLabels(true, false);
            $this->assertEquals(1, count($valuesAndLabels));
            $this->assertTrue($form->resolveValueBeforeSave());
        }

        /**
         * @depends testGetTypeValuesAndLabels
         */
        public function testValidateDynamicDateIntegerValuePossibilities()
        {
            $form                 = new DateWorkflowActionAttributeForm('WorkflowModelTestItem', 'date');
            $form->type           = DateWorkflowActionAttributeForm::TYPE_DYNAMIC_FROM_TRIGGERED_DATE;
            $form->shouldSetValue = true;
            $validated            = $form->validate();
            $this->assertFalse($validated);
            $compareErrors = array('durationInterval' => array('Interval cannot be blank.'));
            $this->assertEquals($compareErrors, $form->getErrors());

            $form->value          = '';
            $validated            = $form->validate();
            $this->assertFalse($validated);
            $compareErrors = array('durationInterval' => array('Interval cannot be blank.'));
            $this->assertEquals($compareErrors, $form->getErrors());

            $form->durationInterval = 0;
            $validated              = $form->validate();
            $this->assertTrue($validated);

            $form->durationInterval = '0';
            $validated              = $form->validate();
            $this->assertTrue($validated);
        }
    }
?>