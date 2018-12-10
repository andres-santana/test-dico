<?php

class Potato_Compressor_Block_Adminhtml_System_Config_Source_Progress
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $element->getElementHtml() . $this->_getAfterElementHtml();
    }

    protected function _getAfterElementHtml()
    {
        $html = '
            <script type="text/javascript">
                varienForm.prototype.progressCompleteFlag = false;
                varienForm.prototype._submit = function () {
                    if(getProgressComplete()) {
                        var $form = $(this.formId);
                        if(this.submitUrl){
                            $form.action = this.submitUrl;
                        }
                        $form.submit();
                    } else {
                        this.startIndex();
                    }
                }
                varienForm.prototype.startIndex = function () {
                    var compressorProgressUrl = "' . $this->getUrl('po_compressor_admin/adminhtml_index/progress/section/po_compressor') . '";
                    var formUrl = $(this.formId).action;
                    var actionArray = formUrl.split("/");
                    var storeIndex = actionArray.indexOf("store");
                    var websiteIndex = actionArray.indexOf("website");
                    if (storeIndex != -1) {
                        compressorProgressUrl = compressorProgressUrl + "store/" + actionArray[storeIndex + 1] + "/";
                    }
                    if (websiteIndex != -1) {
                        compressorProgressUrl = compressorProgressUrl + "website/" + actionArray[websiteIndex + 1] + "/";
                    }

                    $("loading_mask_loader").innerHTML = $("loading_mask_loader").innerHTML + "<span id=\"compressor_progress\"></span>";
                    new Ajax.Request(compressorProgressUrl, {
                        parameters: $("config_edit_form").serialize(true),
                        onSuccess: function(response) {
                            var json = JSON.parse(response.responseText);
                            if (json.progress !== undefined) {
                                $("compressor_progress").innerHTML = json.progress + "%";
                            }
                            if (json.complete || json.progress === undefined) {
                                setProgressComplete();
                                submitConfigForm();
                                return true;
                            } else {
                                return startIndex();
                            }
                        }
                    });
                }
                var getProgressComplete = function() {
                    return configForm.progressCompleteFlag;
                }
                var setProgressComplete = function() {
                    configForm.progressCompleteFlag = true;
                }
                var submitConfigForm = function() {
                    configForm.submit();
                }
                var startIndex = function() {
                    configForm.startIndex();
                }
            </script>
        ';
        return $html;
    }
}