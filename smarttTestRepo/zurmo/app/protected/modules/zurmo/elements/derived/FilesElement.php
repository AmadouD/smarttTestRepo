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
     * User interface element for managing file attachments against a given model.
     *
     */
    class FilesElement extends ModelsElement implements DerivedElementInterface, ElementActionTypeInterface
    {
        const ALLOW_DOWNLOAD_ON_EDITABLE_KEY    = 'allowDownloadOnEditable';

        protected $elementIdPostfix = '';

        protected function renderControlNonEditable()
        {
            assert('$this->model instanceof Item || $this->model->getModel() instanceof Item');
            $content = null;
            if ($this->model->files->count() > 0)
            {
                $content  .= '<ul class="attachments">';
                foreach ($this->model->files as $fileModel)
                {
                    $content .= '<li><span class="icon-attachment"></span>';
                    $content .= FileModelDisplayUtil::renderDownloadLinkContentByRelationModelAndFileModel($this->model,
                                                                                                           $fileModel);
                    $content .= ' ' . FileModelDisplayUtil::convertSizeToHumanReadableAndGet((int)$fileModel->size);
                    $content .= '</li>';
                }
                $content .= '</ul>';
            }
            return $content;
        }

        protected function renderControlEditable()
        {
            if ($this->getDisabledValue())
            {
                return $this->renderControlNonEditable();
            }
            assert('$this->model instanceof WizardForm || $this->model instanceof Item || $this->model->getModel() instanceof Item');
            $existingFilesInformation = array();
            foreach ($this->model->files as $existingFile)
            {
                $existingFileInformation    = array('name' => $existingFile->name,
                                                    'size' => FileModelDisplayUtil::convertSizeToHumanReadableAndGet(
                                                                                    (int)$existingFile->size),
                                                    'id'   => $existingFile->id);
                if ($this->getAllowDownloadOnEditable())
                {
                    $existingFileInformation['filelink'] = FileModelDisplayUtil:: resolveDownloadUrlByRelationModelIdAndRelationModelClassNameAndFileIdAndFileName(
                                                                                                $this->model->id,
                                                                                                get_class($this->model),
                                                                                                $existingFile->id);
                }
                $existingFilesInformation[] = $existingFileInformation;
            }
            $inputNameAndId = $this->getEditableInputId('files');
            $cClipWidget = new CClipWidget();
            $cClipWidget->beginClip("filesElement");
            $cClipWidget->widget('application.core.widgets.FileUpload', array(
                'uploadUrl'                 => Yii::app()->createUrl("zurmo/fileModel/upload",
                                                                        array('filesVariableName' => $inputNameAndId)),
                'deleteUrl'                 => Yii::app()->createUrl("zurmo/fileModel/delete"),
                'inputName'                 => $inputNameAndId,
                'inputId'                   => $inputNameAndId,
                'hiddenInputName'           => 'filesIds',
                'formName'                  => $this->form->id,
                'allowMultipleUpload'       => true,
                'existingFiles'             => $existingFilesInformation,
                'maxSize'                   => (int)InstallUtil::getMaxAllowedFileSize(),
                'showMaxSize'               => $this->getShowMaxSize(),
                'id'                        => $this->getId(),
                'renderFileDownloadLinks'   => $this->getAllowDownloadOnEditable(),
            ));

            $cClipWidget->endClip();
            return $cClipWidget->getController()->clips['filesElement'];
        }

        protected function renderError()
        {
        }

        protected function renderLabel()
        {
            return $this->resolveNonActiveFormFormattedLabel($this->getFormattedAttributeLabel());
        }

        protected function getFormattedAttributeLabel()
        {
            return Yii::app()->format->text(Zurmo::t('ZurmoModule', 'Attachments'));
        }

        public static function getDisplayName()
        {
            return Zurmo::t('ZurmoModule', 'Attachments');
        }

        /**
         * Get the attributeNames of attributes used in
         * the derived element. For this element, there are no attributes from the model.
         * @return array - empty
         */
        public static function getModelAttributeNames()
        {
            return array();
        }

            /**
         * Gets the action type for the related model's action
         * that is called by the select button or the autocomplete
         * feature in the Editable render.
         */
        public static function getEditableActionType()
        {
            return null;
        }

        public static function getNonEditableActionType()
        {
            return null;
        }

        protected function getShowMaxSize()
        {
            if (!isset($this->params['showMaxSize']))
            {
                return true;
            }
            return $this->params['showMaxSize'];
        }

        protected function getId()
        {
            return get_class($this->model) . $this->getElementIdPostfix() . $this->getElementModeIdForElementId();
        }

        /**
         * @return string content
         */
        public static function getEditableTemplateForInlineEdit()
        {
            // Begin Not Coding Standard
            return       '<td colspan="{colspan}">' .
                         '<div class="file-upload-box">{content}{error}</div>' .
                         '<a href="#" class="show-file-upload-box" onclick="jQuery' .
                         '(this).hide().prev().show().find(\'input[type=file]\').click(); ' .
                         'return false;">' . Zurmo::t('Core', 'Add Files') . '</a>' .
                         '</td>';
            // End Not Coding Standard
        }

        public function getAllowDownloadOnEditable()
        {
            return ArrayUtil::getArrayValue($this->params,
                                            static::ALLOW_DOWNLOAD_ON_EDITABLE_KEY,
                                            $this->getAllowDownloadOnEditableDefault());
        }

        public function getAllowDownloadOnEditableDefault()
        {
            return true;
        }

        /**
         * Add ElementId Postfix, which is used to distinct file uploads elements by their ids
         * For now this feature is usefull only for comments.
         * @return string
         */
        protected function getElementIdPostfix()
        {
            if (isset($this->params['elementIdPostfix']))
            {
                return $this->params['elementIdPostfix'];
            }
            return $this->elementIdPostfix;
        }

        /**
         * Used now only for comments, because in case when we have multiple comments on same page, and because user
         * can edit comments(and remove files from comments), we need to provide unique id for each comment.
         * @return model id or null
         */
        protected function getElementModeIdForElementId()
        {
            if ($this->model instanceOf Comment && $this->model->id > 0)
            {
                return $this->model->id;
            }
        }
    }
?>