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
     * Display a drop down.
     */
    class DropDownElement extends Element
    {
        /**
         * Renders the editable dropdown content.
         * @return A string containing the element's content.
         */
        protected function renderControlEditable()
        {
            return $this->form->dropDownList(
                $this->model->{$this->attribute},
                'value',
                $this->getDropDownArray(),
                $this->getEditableHtmlOptions()
            );
        }

        protected function resolveIdForLabel()
        {
            return $this->getIdForSelectInput();
        }

        /**
         * Renders the noneditable dropdown content.
         * Takes the model attribute value and converts it into the proper display value
         * based on the corresponding dropdown display label.
         * @return A string containing the element's content.
         */
        protected function renderControlNonEditable()
        {
            $dropDownModel = $this->model->{$this->attribute};
            $dropDownArray = $this->getDropDownArray();
            return Yii::app()->format->text(ArrayUtil::getArrayValue($dropDownArray, $dropDownModel->value));
        }

        protected function convertDropDownModelsToArrayByIdName($dropDownModels)
        {
            $array = array();
            if (!empty($dropDownModels))
            {
                foreach ($dropDownModels as $dropDownModel)
                {
                    $array[$dropDownModel->id] = $dropDownModel;
                }
            }
            return $array;
        }

        protected function getAddBlank()
        {
            if (ArrayUtil::getArrayValue($this->params, 'addBlank'))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function getIdForSelectInput()
        {
            return $this->getEditableInputId($this->attribute, 'value');
        }

        protected function getNameForSelectInput()
        {
            return $this->getEditableInputName($this->attribute, 'value');
        }

        public function getEditableNameIds()
        {
            return array(
                $this->getIdForSelectInput(),
            );
        }

        protected function getEditableHtmlOptions()
        {
            $htmlOptions = array(
                'name' => $this->getNameForSelectInput(),
                'id'   => $this->getIdForSelectInput(),
            );
            if ($this->getAddBlank())
            {
                $htmlOptions['empty'] = Zurmo::t('Core', '(None)');
            }
            $htmlOptions['disabled'] = $this->getDisabledValue();
            return $htmlOptions;
        }

        protected function getDropDownArray()
        {
            $dropDownModel = $this->model->{$this->attribute};
            $dataAndLabels    = CustomFieldDataUtil::
                                getDataIndexedByDataAndTranslatedLabelsByLanguage($dropDownModel->data, Yii::app()->language);
            return $dataAndLabels;
        }

        /**
         * Generate the error content. Used by editable content
         * @return error content
         */
        protected function renderError()
        {
            return $this->form->error($this->model, $this->attribute,
                                      array('inputID' => $this->getEditableInputId($this->attribute, 'value')));
        }
    }
?>
