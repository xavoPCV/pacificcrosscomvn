<div style="display: none;" id="redactor_modal_overlay"></div>
<div class="editor-navigation navbar editor-toolbar" id="mits-top-navigation">
    <div class="header_nav">
        <ul class="nav pull-left">
            <li><a class="back_btn" href="<?php echo ADVISORLEAD_URL ?>/templates/">Back to Templates</a></li>
            <li><a class="back_btn" href="<?php echo ADVISORLEAD_URL ?>/ctas/">Back to Popup</a></li>
        </ul>
        <a href="<?php echo ADVISORLEAD_URL ?>/ctas" class="brand quit-button" id="logo"></a>
    </div>
    <div class="navbar-inner">
        <div class="nav-collapse pull-left" style="width: 100%">
            <form id="save-it-form">
                <ul class="nav name">
                    <li>
                        <label for="id_capture_clicks_name">Popup name</label>
                        <input type="text" id="id_capture_clicks_name" name="id_capture_clicks_name" placeholder="CTA Name" class="editable" value="<?php echo $this->template->cta_name ?>">
                    </li>
                </ul>
            </form>
            <ul id="notifications" class="nav">
                <li class="error"><i class="icon-warning-sign"></i><span>Error.</span></li>
                <li class="warning"><i class="icon-warning-sign"></i><span>Warning.</span></li>
                <li class="notice"><i class="icon-info-sign"></i><span>Notice.</span></li>
            </ul>
            <ul class="nav" style="width: 100%">
                <li data-action="" class="btn-group" style="text-align: center; margin: 10px 0 0">
                    <button data-action="save" class="btn btn-primary" id="save-button" >Save</button>
                </li>
            </ul>
            <ul class="nav options option-menu" style="width: 100%;">
                <li data-action="" class="btn-group">
                    <button data-action="publish" data-active-class="btn-warning" id="publish-button" class="btn sub-btn">Publish</button>
                </li>
            </ul>
        </div>
    </div>
</div>

<div id="powered_block">
</div>
<div id="template-settings" class="ui-resizable">
    <div id="white-box-left">
        <div id="mits-settings-menu">
            <ul class="unstyled" id="editable-elements">
                <li id="gen-parameters-item" class="header_text">Form Settings</li>
                <li>
                    <a class="btn-block accordion_btn" action="integration">Integration Settings<span></span></a>
                </li>
                <li>
                    <a class="btn-block accordion_btn" action="form_style">Form Style<span></span></a>
                </li>
                <li>
                    <a class="btn-block accordion_btn" action="tracking_code">Tracking Code<span></span></a>
                </li>
                <li class="page-content-divider"></li>
                <li  class="header_text" style="font-weight: normal; font-size: 20px;">Form Elements</li>
                <div id="editable_placeholder" style="text-align: center"></div>
                <li class="page-content-divider"></li>
                <li  class="header_text" style="font-weight: normal; font-size: 18px;">Form Fields</li>
                <div id="editable_fields_placeholder" style="text-align: center">
                    <img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"/> Loading fields
                </div>
            </ul>
        </div>
        <div class="editor-overlay">
            <div class="editor-wrap">
                <div id="mits-editor">
                    <h4 class="blue" id="mits-modal-header"></h4>
                    <div class="mits-form-space" id="mits-form-fields" name="">
                        <div class="control-group">
                            <label>Placeholder Text</label>
                            <div class="input-space controls">
                                <input type="text" field_type="placeholder_text" class="mits-form-field-details">
                            </div>
                        </div>
                        <!--                        <div class="control-group">
                                                    <label>Invalid Message</label>
                                                    <div class="input-space controls">
                                                        <textarea rows="7" field_type="invalid_message" class="mits-form-field-details">Please enter a correct value for this field</textarea>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label><input type="checkbox" class="is_field_required" style="margin-top: 0"/> Is required field ?</label>
                                                </div>
                                                <div class="control-group">
                                                    <label>Required Message</label>
                                                    <div class="input-space controls">
                                                        <textarea rows="7" field_type="required_message" class="mits-form-field-details">You must fill out this field in order to proceed</textarea>
                                                    </div>
                                                </div>-->
                    </div>
                    <div class="mits-form-space" id="mits-tracking-code">
                        <form class="form-vertical" id="tracking-code-form">
                            <div class="control-group">
                                <label for="id-mits-head-code" class="control-label">Head tag tracking code</label>
                                <div class="input-space controls">
                                    <textarea name="mits-head-code" class="input-w90 code-like" rows="7" id="id-mits-head-code"><?php echo $this->template->user_head_code ?></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="id-mits-analytics" class="control-label">End of body tag tracking code</label>
                                <div class="input-space controls">
                                    <textarea name="mits-analytics" class="input-w90 code-like" rows="7" id="id-mits-analytics"><?php echo $this->template->user_end_code ?></textarea>
                                </div>
                            </div>
                        </form>
                        <p class="muted">
                            <small>
                                <span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span>
                                You may use any analytics code you want in the spaces above.  Be aware that we do not provide support for tracking issues.
                                Other tracking is unsupported and may cause problems with your pages tracking.
                            </small>
                        </p>
                    </div>
                    <div class="mits-form-space" id="mits-removable-elem">
                        <form class="form-vertical">
                            <div class="control-group">
                                <div class="input-space controls">
                                    <input type="hidden" name="mits-to-remove" class="required input-w90" id="id-mits-to-remove">
                                    <div data-toggle="buttons-radio" class="btn-group">
                                        <button onClick="CCLICKS.removableChanged(true);" class="btn btn-small mits-link-target-btn" type="button" id="mits-removable-btn-yes">Visible</button>
                                        <button onClick="CCLICKS.removableChanged(false);" class="btn btn-small mits-link-target-btn" type="button" id="mits-removable-btn-no">Hidden</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-video-or-image">
                        <form class="form-vertical">
                            <div class="control-group">
                                <div class="input-space controls">
                                    <input type="hidden" name="video-or-image" class="required input-w90" id="id-video-image">
                                    <div data-toggle="buttons-radio" class="btn-group">
                                        <button onClick="CCLICKS.videoOrImage(true);" class="btn btn-small" type="button" id="video-or-image-btn-yes">Embed Code</button>
                                        <button onClick="CCLICKS.videoOrImage(false);" class="btn btn-small" type="button" id="video-or-image-btn-no">Image</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-edit-optin-form">
                        <div id="mits-optin-forms">
                            <form class="form-vertical" id="mits-modal-forms-form" novalidate>
                                <div class="control-group">
                                    <h4>Select integration</h4>
                                    <div class="input-space controls">
                                        <select name="mits-integration-select" id="id-mits-integration-select">
                                            <option value="" class="muted">----</option>
                                            <?php                                           
                                            if (!empty($this->template->integrations)) {
                                                foreach ($this->template->integrations as $name => $values) {
                                                    if ($values['active']) {
                                                        ?>
                                                        <option value="<?php echo $name; ?>" class="integration"><?php echo $values['name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <option value="other" class="integration">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="mits-mail-providers-forms">
                                    <p id="mits-loading-forms" style="display: none;">
                                        <img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"/> Loading forms
                                    </p>
                                    <div id="integration-form" style="display: none">
                                        <h4>Using</h4>
                                        <select id="mits-form-select" name="mits-form-select"></select>
                                        <p>
                                            <small>
                                                <a id="mits-reload-forms" style="cursor: pointer"><i class="icon-repeat"></i> Reload forms</a>
                                            </small>
                                        </p>
                                    </div>
                                    <div id="copy-paste-form" style="display:none;">
                                        <h4>Enter form embed HTML code</h4>
                                        <textarea class="code-like" rows="5" id="mits-copy-paste-code"></textarea>
                                        <p class="muted"><span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span> &nbsp;<span class="p-transition" id="aweber-error-msg">Please insert only raw HTML version of your embed code.</span></p>
                                    </div>
                                    <div style="display:none;" id="mits-modal-forms-thankyou">
                                        <div class="control-group">
                                            <h4>Enter thank you page URL</h4>
                                            <div class="input-space controls">
                                                <input type="text" name="mits-form-typ-url" class="required input-w90 url" id="id-form-typ-url">
                                            </div>
                                            <p class="muted note-for-t-y-p">
                                                <small>
                                                    For better results use AdvisorLeads to create your thankyou page.
                                                    If you do not want to use AdvisorLeads for your thankyou page, you may direct your customers
                                                    to any page of your choice.  All you have to do is simply enter the url above.
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="bribe-mail-old"></div>
                                </div>
                            </form>
                        </div>
                        <div style="display:none;" id="mits-optin-no-forms">
                            <div class="alert alert-info">First you need to <b><a href="/my-account/">connect at least one email marketing service</a></b> account.</div>
                        </div>
                    </div>
                    <div id="mits-editor-tools-space">
                        <div class="mits-form-space" id="mits-edit-rich-text">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <textarea name="mits-rich-text" class="input-w90" id="mits-rich-text-basic" rows="10"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mits-form-space" id="mits-edit-richtext-area">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <textarea name="mits-richtext-area" class="input-w90" id="mits-richtext-area" rows="10"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mits-form-space" id="mits-change-style">
                            <form class="form-vertical">
                                <div class="mits-colors-space" id="colors-inp-space"></div>
                            </form>
                        </div>
                        <div class="mits-form-space" id="mits-edit-video">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <label for="id-mits-video" class="control-label">Insert your video embed code</label>
                                        <textarea name="mits-video" class="input-w90 code-like" rows="17" id="id-mits-video"></textarea>
                                        <span class="hemits-block">Recommended size of video: width: <span id="lb-video-width"></span>px, height: <span id="lb-video-height"></span>px</span>
                                        <p style="text-align: justify" id="id-mits-video-http" class="hemits-block hide">
                                            <span class="label label-warning">WARNING</span> You are attempting to embed video over HTTP. Be advised that, if you
                                            are planning to host the page on AdvisorLead, or one of our domains, the video will not load
                                            unless it is served over HTTPS. Please consult your video hosting
                                            service on what options they offer with regards to video over HTTPS.

                                            <br><br>

                                            <span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span> In some cases, simply replacing every instance of <code>http://</code> in
                                            your embed code with <code>https://</code> will work. Please do test this
                                            page on multiple browsers before sending traffic to it.
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mits-form-space" id="mits-edit-area">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <label for="id-mits-placeholder" class="control-label">Insert your embed code</label>
                                        <textarea name="mits-area" class="input-w90 code-like" rows="17" id="id-mits-area"></textarea>
                                        <span class="hemits-block">Recommended size of embed code: width: <span id="lb-area-width"></span>px, height: <span id="lb-area-height"></span>px</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mits-form-space" id="mits-edit-btn-text">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <input type="text" name="mits-btn-text" class="required input-w90" id="id-mits-btn-text">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mits-form-space" id="mits-change-link">
                            <form>
                                <div class="control-group" id="id-mits-link-text-space">
                                    <h4><label for="id-mits-link-text">Text to display</label></h4>
                                    <div class="input-space controls">
                                        <input type="text" name="mits-link-text" class="required input-w90" id="id-mits-link-text">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <h4><label for="id-mits-link-href">Link to</label></h4>
                                    <div class="input-space controls">
                                        <input type="text" name="mits-link-href" class="input-w90 checkUrl" id="id-mits-link-href">
                                    </div>
                                    <a style="display: none" id="id-mits-link-sync" href="javascript:;">Synchronize with target URL.</a>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Open in new window</label>
                                    <div class="input-space controls">
                                        <input type="hidden" name="mits-link-target" class="required input-w90" id="id-mits-link-target">
                                        <div data-toggle="buttons-radio" class="btn-group">
                                            <button onClick="$('#mits-link-target-btn-yes').addClass('btn-success');
                                                $('#mits-link-target-btn-no').removeClass('btn-danger');
                                                $('#id-mits-link-target').val('yes');" class="btn btn-small mits-link-target-btn" type="button" id="mits-link-target-btn-yes">Yes</button>
                                            <button onClick="$('#mits-link-target-btn-yes').removeClass('btn-success');
                                                $('#mits-link-target-btn-no').addClass('btn-danger');
                                                $('#id-mits-link-target').val('no');" class="btn btn-small mits-link-target-btn" type="button" id="mits-link-target-btn-no">No</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Nofollow</label>
                                    <div class="input-space controls">
                                        <input type="hidden" name="mits-link-target" class="required input-w90" id="id-mits-link-nofollow">
                                        <div data-toggle="buttons-radio" class="btn-group">
                                            <button onClick="$('#mits-link-nofollow-btn-no').addClass('btn-success');
                                                $('#mits-link-nofollow-btn-yes').removeClass('btn-danger');
                                                $('#id-mits-link-nofollow').val('no');" class="btn btn-small mits-link-follow-btn" type="button" id="mits-link-nofollow-btn-no">Follow</button>
                                            <button onClick="$('#mits-link-nofollow-btn-no').removeClass('btn-success');
                                                $('#mits-link-nofollow-btn-yes').addClass('btn-danger');
                                                $('#id-mits-link-nofollow').val('yes');" class="btn btn-small mits-link-follow-btn" type="button" id="mits-link-nofollow-btn-yes">Nofollow</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mits-form-space" id="mits-change-img">
                            <h4>1) Choose your image</h4>
                            <ul class="unstyled" id="lb-my-images">
                            </ul>
                            <p style="display: none;" id="images-loader"><img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"> Loading images</p>
                            <h4>2) or <iframe frameborder="0" noresize="noresize" id="mits-image-iframe" src="<?php echo JURI::base() ?>/index.php?option=com_advisorlead&task=templates.api_call&request=upload-image&type=cta"></iframe></h4>
                            <p style="margin-top:-10px;"><small class="muted">Maximum image size is 5 MB.</small></p>
                        </div>
                        <div class="mits-form-space" id="mits-comment-text">
                            <p class="muted"></p>
                        </div>
                    </div>
                    <div class="mits-form-space" id="mits-change-font"></div>
                    <div class="mits-form-space" id="mits-edit-styles"></div>
                    <div class="tcenter" id="lb-submit-btns">
                        <hr>
                        <button class="btn btn-primary" id="mits-form-submit">Okay</button>
                        <button onClick="CCLICKS.closeEditor();" data-dismiss="modal" class="btn">Cancel</button>
                    </div>
                    <a title="Close" class="editor-close" onClick="CCLICKS.closeEditor();"></a>
                </div>
            </div>
        </div>
    </div>
</div>