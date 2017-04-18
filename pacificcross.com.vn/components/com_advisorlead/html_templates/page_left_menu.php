<div style="display: none;" id="redactor_modal_overlay"></div>
<button id="remove-preview" class="btn">
    Continue editing... <i class="icon-remove"></i>
</button>
<div class="editor-navigation navbar editor-toolbar" id="mits-top-navigation">
    <div class="header_nav">
        <ul class="nav pull-left">
            <li><a class="back_btn" href="<?php echo ADVISORLEAD_URL; ?>/templates/">Back to Templates</a></li>
            <li><a class="back_btn" href="<?php echo ADVISORLEAD_URL; ?>/pages/">Back to My Pages</a></li>
        </ul>
        <a href="<?php print ADVISORLEAD_URL; ?>/pages" class="brand quit-button" id="logo"></a>
    </div>
    <div class="navbar-inner">
        <div class="nav-collapse pull-left" style="width: 100%">
            <form id="save-it-form">
                <ul class="nav name">
                    <li>
                        <label for="id_page_name">Page name</label>
                        <input type="text" id="id_page_name" name="id_page_name" placeholder="Page Name" class="editable" value="<?php echo $this->template->page_name ?>">
                    </li>
                    <li>
                        <label for="id_page_url">Page url</label>
						<?php //print_r($this->template);?>
                        <input type="text" id="id_page_url" name="id_page_url" placeholder="page-url" class="editable" value="<?php echo $this->template->page_slug;?>">
                    </li>
                     
                    <li>
                        <label for="id_page_url">Domain</label>
						<?php //print_r($this->template);die;?>
                        <input <?php if($this->template->page_id != '' ){ echo 'readonly=""'; } ?> type="text" id="id_domain_url" name="id_domain_url" placeholder="domain-url" class="editable" value="<?php echo $this->domains ?>">
                    </li>
                    
                </ul>
            </form>
            <ul id="notifications" class="nav">
                <li class="error"><i class="icon-warning-sign"></i><span>Error.</span></li>
                <li class="warning"><i class="icon-warning-sign"></i><span>Warning.</span></li>
                <li class="notice"><i class="icon-info-sign"></i><span>Notice.</span></li>
            </ul>
            <ul class="nav" style="width: 100%">
                <li data-action="" class="btn-group" style="text-align: center; margin: 10px 0 0;">
                    <button data-action="save" class="btn btn-primary" id="save-button" >Save</button>
                </li>
            </ul>
            <ul class="nav options option-menu" style="width: 100%;">
                <li data-action="" class="btn-group">
                    <button data-action="publish" data-active-class="btn-warning" id="publish-button" class="btn sub-btn">View</button>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="template-settings" class="ui-resizable">
    <div id="white-box-left">
        <div id="mits-settings-menu">
            <ul class="unstyled" id="editable-elements">
                <li id="gen-parameters-item" class="header_text">Page Editor</li>
                <li>
                    <a class="btn-block accordion_btn" id="basic-page-settings" href="#">Page Settings<span></span></a>
                </li>
                <li>
                    <a class="btn-block accordion_btn" id="style-settings" href="#">Colors<span></span></a>
                </li>
                <li>
                    <a class="btn-block accordion_btn" id="font-settings" href="#">Fonts<span></span></a>
                </li>
                <li>
                    <a class="btn-block accordion_btn" id="js-settings" style="display: none" href="#">Dynamic Options<span></span></a>
                </li>
                <li>
                    <a class="btn-block accordion_btn" id="exit-popup-settings" href="#">Exit Popup<span></span></a>
                </li>
                <li class="page-content-divider"></li>
                <li  class="header_text" style="font-weight: normal; font-size: 20px;">Content</li>
            </ul>
        </div>
        <div class="editor-overlay">
            <div class="editor-wrap">
                <div id="mits-editor">
                    <h4 id="mits-modal-header"></h4>
                    <div class="mits-form-space" id="mits-exit-popup-settings">
                        <form class="form-vertical" id="exit-popup-settings-form">
                            <div class="control-group">
                                <label class="control-label">Display popup on page close?</label>
                                <div class="input-space controls">
                                    <div data-toggle="buttons-radio" class="btn-group">
                                        <a class="btn btn-small" type="button" id="exit-popup-on" href="javascript:;">Yes</a>
                                        <a class="btn btn-small btn-danger active" type="button" id="exit-popup-off" href="javascript:;">No</a>
                                    </div>
                                    <input type="checkbox" style="display:none;" id="id-exit-popup" <?php echo $this->template->exit_popup == 'true' ? 'checked' : '' ?>>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="mits-exit-popup-message" class="control-label">Exit Popup Message</label>
                                <div class="input-space controls">
                                    <textarea placeholder="Hey, One More Thing Before You Go!" disabled="" name="mits-exit-popup-message" class="input-w90 code-like" rows="5" id="id-exit-popup-message"><?php echo stripslashes($this->template->exit_popup_message); ?></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Redirect if user stays on the page?</label>
                                <div class="input-space controls">
                                    <div data-toggle="buttons-radio" class="btn-group">
                                        <a class="btn btn-small disabled" type="button" id="exit-popup-redirect-on" href="javascript:;">Yes</a>
                                        <a class="btn btn-small btn-danger active disabled" type="button" id="exit-popup-redirect-off" href="javascript:;">No</a>
                                    </div>
                                    <input type="checkbox" style="display:none;" id="id-exit-popup-redirect" <?php echo $this->template->exit_popup_redirect == 'true' ? 'checked' : '' ?>>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="mits-exit-popup-redirect-url" class="control-label">Redirect url</label>
                                <div class="input-space controls">
                                    <input type="text" disabled="" placeholder="Target url, including http://" name="mits-exit-popup-redirect-url" class="required input-w84 checkUrl" value="<?php echo $this->template->exit_popup_redirect_url ?>" id="id-exit-popup-redirect-url">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-change-js-variables">
                        <form>
                            <div id="js-var-space"></div>
                            <div id="mits-systemtime" style="display: none">
                                <?php $date = JFactory::getDate(); ?>
                                Current time is: <?php echo $date->format('%m/%d/%Y - %l:%M %p') ?><br/>
                            </div>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-change-font">
                        <form class="form-vertical">
                            <hr class="mits-colors-space mits-fonts-space">
                            <div class="mits-fonts-space" id="fonts-inp-space"></div>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-change-style">
                        <form class="form-vertical">
                            <div class="mits-colors-space" id="colors-inp-space"></div>
                            <p class="mits-colors-space">
                                <small><a onClick="MITS.resetColors();
                                        return false;" href="javascript:;"><i class="icon-repeat"></i> Reset colors to default</a></small>
                            </p>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-seo-settings">
                        <form class="form-vertical" id="basic-settings-form">
                            <div id="background_type_wrap" style="display: none">
                                <h4 class="mits-modal-header">Background Type</h4>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <input type="hidden" name="background_type" class="required input-w90" id="background_type">
                                        <div id="background_type_group" data-toggle="buttons-radio" class="btn-group" element_id="bgimg" background_color="">
                                            <button onClick="MITS.backgroundType('color');" class="btn btn-small btn-success active" type="button" id="background_type_color">Color</button>
                                            <button onClick="MITS.backgroundType('image');" class="btn btn-small" type="button" id="background_type_image">Image</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mits-modal-header">SEO Settings</h4>
                            <div class="control-group">
                                <label for="id-page-title" class="control-label">Page Title</label>
                                <div class="input-space controls">                
                                    <input type="text" name="mits-id-page-title" class="required mits-letters-counter input-w84" id="id-page-title" value="<?php echo $this->template->page_title ?>">
                                    <label style="" class="error" for="id-page-title"></label>
                                    <p style="margin-top: -10px;"><small class="muted">The maximum recommended is 60 characters.</small></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="id-page-description" class="control-label">Description</label>
                                <div class="input-space controls">
                                    <input type="text" name="mits-id-page-description" class="mits-letters-counter input-w84" id="id-page-description" value="<?php echo $this->template->page_description ?>">
                                    <label style="" class="error" for="id-page-description"></label>
                                    <p style="margin-top: -10px;"><small class="muted">Recommended length is 150 characters.</small></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="id-page-keywords" class="control-label">Keywords</label>
                                <div class="input-space controls">
                                    <input type="text" name="mits-id-page-keywords" class="input-w84" id="id-page-keywords" value="<?php echo $this->template->page_keywords ?>">
                                    <label style="" class="error" for="id-page-keywords"></label>
                                    <p style="margin-top: -10px;"><small class="muted">Keywords should be separated by commas.</small></p>
                                </div>
                            </div>
                            <h4 class="mits-modal-header">Tracking Code</h4>
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
                                        <button onClick="MITS.removableChanged(true);" class="btn btn-small mits-link-target-btn" type="button" id="mits-removable-btn-yes">Visible</button>
                                        <button onClick="MITS.removableChanged(false);" class="btn btn-small mits-link-target-btn" type="button" id="mits-removable-btn-no">Hidden</button>
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
                                        <button onClick="MITS.videoOrImage(true);" class="btn btn-small" type="button" id="video-or-image-btn-yes">Embed Code</button>
                                        <button onClick="MITS.videoOrImage(false);" class="btn btn-small" type="button" id="video-or-image-btn-no">Image</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div style="display:none;" id="mits-editor-tools-space">
                        <div class="mits-form-space" id="mits-edit-optin-form">
                            <div id="mits-optin-forms">
                                <form class="form-vertical" id="mits-modal-forms-form" novalidate>
                                    <div class="control-group">
                                        <h4>Select integration</h4>
                                        <div class="input-space controls">
                                            <select name="mits-integration-select" class="required input-w90" id="id-mits-integration-select">
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
                                        <div id="non-webinar-services">
                                            <?php if ($this->template->integrations['aweber']['copy_paste']) { ?>
                                                <div style="display:none;" class="integration-choice" id="aweber-choice">
                                                    <div id="lb-aweber-forms" style="display: none;"></div>
                                                    <div style="" id="lb-aweber-copy-paste">
                                                        <h4>Enter form embed HTML code</h4>
                                                        <textarea class="input-w90 code-like" rows="5" id="id-mits-aweber-copy-paste"></textarea>
                                                        <p class="muted"><span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span> &nbsp;<span class="p-transition" id="aweber-error-msg">Please insert only raw HTML version of your embed code.</span></p>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div style="" class="integration-choice" id="aweber-choice">
                                                    <h4>Choose your desired form</h4>
                                                    <p class="muted"><small><span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span> You will only see lists, below, that have web forms already created for them in AWeber. If your list does not appear, create the web form in AWeber and then click on the "Reload forms" button below.</small></p>

                                                    <div id="lb-aweber-forms">
                                                        <select style="" id="lb-aweber-forms-select" name="lb-aweber-forms-select" class="required input-w90"></select>
                                                        <p id="lb-aweber-loading" style="display: none;"><img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"> Loading AWeber forms</p>
                                                        <p style="" id="lb-reload-btn-aweber"><small><a onClick="MITS.loadAWeberForms(true);
                                            return false;" href="javascript:;"><i class="icon-repeat"></i> Reload forms</a></small></p>
                                                    </div>
                                                    <div style="display:none;" id="lb-aweber-copy-paste">
                                                        <h4>Enter form embed HTML code</h4>
                                                        <textarea class="input-w90 code-like" rows="5" id="id-mits-aweber-copy-paste"></textarea>
                                                        <p class="muted"><span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span> &nbsp;<span class="p-transition" id="aweber-error-msg">Please insert only raw HTML version of your embed code.</span></p>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div style="display:none;" class="integration-choice" id="getresponse-choice">
                                                <?php if ($this->template->integrations['getresponse']['copy_paste']) { ?>
                                                    <div style="" id="lb-getresponse-copy-paste">
                                                        <h4>Enter form embed HTML code</h4>
                                                        <textarea class="input-w90 code-like" rows="5" id="id-mits-getresponse-copy-paste"></textarea>
                                                        <p class="muted"><span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span> &nbsp;<span class="p-transition" id="getresponse-error-msg">Please insert only raw HTML version of your embed code.</span></p>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <h4>Choose your desired campaign</h4>
                                                    <div id="lb-getresponse-forms">
                                                        <?php if (!$this->template->integrations['getresponse']['active']) { ?>
                                                            <div class="alert alert-info">First you need to <b><a href="<?php echo ADVISORLEAD_URL; ?>/my-account/">connect your GetResponse</a></b> account.</div>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <select style="" id="lb-getresponse-forms-select" name="lb-getresponse-forms-select" class="required input-w90"></select>
                                                            <p id="lb-getresponse-loading" style="display: none;"><img alt="loading" src="<?php echo $template_uri; ?>/img/ajax-loader-small.gif"> Loading GetResponse campaigns</p>
                                                            <p style="" id="lb-reload-btn-getresponse">
                                                                <small><a onClick="MITS.loadGetresponseForms(true);
                                                return false;" href="javascript:;"><i class="icon-repeat"></i> Reload campaigns</a></small>
                                                            </p>
                                                        <?php }
                                                        ?>
                                                    </div>  
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div style="display:none;" class="integration-choice" id="constantcontact-choice">
                                                <h4>Choose your desired list</h4>
                                                <div id="lb-constantcontact-forms">
                                                    <?php if ($this->template->integrations['constantcontact']['active']) { ?>
                                                        <select id="lb-constantcontact-forms-select" class="required input-w90" style="" name="lb-constantcontact-forms-select"></select>
                                                        <p id="lb-constantcontact-loading" style="display: none;"><img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"> Loading ConstantContact lists</p>
                                                        <p style="" id="lb-reload-btn-constantcontact">
                                                            <small><a onClick="MITS.loadConstantContactForms(true);
                                            return false;" href="javascript:;"><i class="icon-repeat"></i> Reload lists</a></small>
                                                        </p>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="alert alert-info">First you need to <b><a href="<?php echo ADVISORLEAD_URL; ?>/my-account/">connect your ConstantContact</a></b> account.</div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div style="display:none;" class="integration-choice" id="icontact-choice">
                                                <h4>Choose your desired lists</h4>
                                                <?php if (!$this->template->integrations['icontact']['active']) { ?>
                                                    <div id="lb-icontact-forms"><div class="alert alert-info">First you need to <b><a href="/my-account/">setup your iContact</a></b> account.</div></div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div id="lb-icontact-forms">
                                                        <select style="" id="lb-icontact-forms-select" name="lb-icontact-forms-select" class="required input-w90 valid">
                                                        </select>
                                                        <p id="lb-icontact-loading" style="display: none;"><img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"> Loading iContact lists</p>
                                                        <p style="" id="lb-reload-btn-icontact"><small><a onClick="MITS.loadIContactForms(true);
                                            return false;" href="javascript:;"><i class="icon-repeat"></i> Reload lists</a></small></p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div style="display:none;" class="integration-choice" id="mailchimp-choice">
                                                <h4>Choose your desired lists</h4>
                                                <div id="lb-mailchimp-forms">
                                                    <select style="" id="lb-mailchimp-forms-select" name="lb-mailchimp-forms-select" class="required input-w90">
                                                        <option value="" class="muted">----</option>
                                                    </select>
                                                    <p id="lb-mailchimp-loading" style="display: none;"><img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"> Loading MailChimp lists</p>
                                                    <p style="" id="lb-reload-btn-mailchimp"><small><a onClick="MITS.loadMailChimpForms(true);
                                        return false;" href="javascript:;"><i class="icon-repeat"></i> Reload lists</a></small></p>
                                                </div>
                                            </div>
                                            <div style="display:none;" class="integration-choice" id="infusionsoft-choice">
                                                <h4>Choose your desired form</h4>
                                                <?php if (!$this->template->integrations['infusionsoft']['active']) { ?>
                                                    <div id="lb-infusionsoft-forms"><div class="alert alert-info">First you need to <b><a href="/my-account/">connect your Infusionsoft</a></b> account.</div></div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div id="lb-infusionsoft-forms">
                                                        <select style="" id="lb-infusionsoft-forms-select" name="lb-infusionsoft-forms-select" class="required input-w90 valid">
                                                        </select>
                                                        <p id="lb-infusionsoft-loading" style="display: none;"><img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"> Loading Infusionsoft Forms</p>
                                                        <p style="" id="lb-reload-btn-infusionsoft"><small><a onClick="MITS.loadInfusionsoftForms(true);
                                            return false;" href="javascript:;"><i class="icon-repeat"></i> Reload lists</a></small></p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="integration-choice" id="other-choice">
                                                <div id="lb-other-copy-paste">
                                                    <h4>Enter form embed HTML code</h4>
                                                    <textarea class="code-like input-w90" rows="5" id="id-mits-other-copy-paste"></textarea>
                                                    <p class="muted"><span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span> &nbsp;<span class="p-transition" id="other-error-msg">Please insert only raw HTML version of your embed code.</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display:none;" id="mits-modal-forms-thankyou">
                                            <div class="control-group">
                                                <h4>Enter thank you page URL</h4>
                                                <div class="input-space controls">
                                                    <input type="text" name="mits-form-typ-url" class="required input-w90 url" id="id-form-typ-url">
                                                </div>
                                                <p class="muted note-for-t-y-p"><small>For better results use AdvisorLeads to create your thankyou page.
                                                        If you do not want to use AdvisorLeads for your thankyou page, you may direct your customers
                                                        to any page of your choice.  All you have to do is simply enter the url above.<br>
                                                        <span class="label label-info"><img src="<?php echo ASSETS_URL ?>/images/label-info-star.png"></span><span class="mits-service-name-for-t-y-p">service</span>'s default thank you redirect does not work with MITS pages.</small></p>
                                            </div>
                                        </div>
                                        <div style="display: none;" id="mits-use-first-name-form" class="control-group">
                                            <h4>Use first name field</h4>
                                            <div class="controls">
                                                <div data-toggle="buttons-radio" class="btn-group">
                                                    <a onClick="$('#mits-use-first-name-checkbox').prop('checked', true);
                                        $('#first-name-on').removeClass('btn-danger');
                                        $(this).addClass('btn-success');
                                        MITS.firstNameCheckboxChanged();
                                        return false;" class="btn btn-small" type="button" id="first-name-on" href="javascript:;">Yes</a>
                                                    <a onClick="$('#mits-use-first-name-checkbox').prop('checked', false);
                                        $('#first-name-on').removeClass('btn-success');
                                        $(this).addClass('btn-danger');
                                        MITS.firstNameCheckboxChanged();
                                        return false;" class="btn btn-small btn-danger active" type="button" id="first-name-off" href="javascript:;">No</a>
                                                </div>
                                                <input type="checkbox" style="display:none;" id="mits-use-first-name-checkbox">
                                            </div>
                                        </div>
                                        <div style="display: none;" id="mits-use-phone-form" class="control-group">
                                            <h4>Use phone field</h4>
                                            <div class="controls">
                                                <div data-toggle="buttons-radio" class="btn-group">
                                                    <a onClick="$('#mits-use-phone-checkbox').prop('checked', true);
                                        $('#phone-on').removeClass('btn-danger');
                                        $(this).addClass('btn-success');
                                        MITS.phoneCheckboxChanged();
                                        return false;" class="btn btn-small" type="button" id="phone-on" href="javascript:;">Yes</a>
                                                    <a onClick="$('#mits-use-phone-checkbox').prop('checked', false);
                                        $('#phone-on').removeClass('btn-success');
                                        $(this).addClass('btn-danger');
                                        MITS.phoneCheckboxChanged();
                                        return false;" class="btn btn-small btn-danger active" type="button" id="phone-off" href="javascript:;">No</a>
                                                </div>
                                                <input type="checkbox" style="display:none;" id="mits-use-phone-checkbox">
                                            </div>
                                        </div>
                                        <div id="gtw-block">
                                            <div>
                                                <div class="form-vertical">
                                                    <div class="control-group">
                                                        <h4 id="gtw-title">Integrate with GoToWebinar</h4>
                                                        <div class="input-space controls" id="gtw-on-off-btn">
                                                            <div data-toggle="buttons-radio" class="btn-group">
                                                                <a onClick="$('#mits-gotowebinar-check').prop('checked', true);
                                        $('#gtw-off').removeClass('btn-danger');
                                        $(this).addClass('btn-success');
                                        MITS.webinarChange();
                                        return false;" class="btn btn-small" type="button" id="gtw-on" href="javascript:;">Yes</a>
                                                                <a onClick="$('#mits-gotowebinar-check').prop('checked', false);
                                        $('#gtw-on').removeClass('btn-success');
                                        $(this).addClass('btn-danger');
                                        MITS.webinarChange();
                                        return false;" class="btn btn-small btn-danger active" type="button" id="gtw-off" href="javascript:;">No</a>
                                                            </div>
                                                            <input type="checkbox" style="display:none;" id="mits-gotowebinar-check" name="form-options">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:none;" id="gotowebinar-choice">
                                                <?php if (!$this->template->integrations['gotowebinar']) { ?>
                                                    <div id="lb-gotowebinar-forms"><div class="alert alert-info">First you need to <b><a href="/my-account/">setup your GoToWebinar</a></b> account.</div></div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div id="lb-gotowebinar-forms">
                                                        <div style="display: none;" id="selected-webinar-dont-exist" class="alert alert-error">Selected webinar is no longer available.</div>
                                                        <select style="display: inline-block;" id="lb-gotowebinar-forms-select" name="lb-gotowebinar-forms-select" class="required input-w90 valid">
                                                            <option value="" class="muted">----</option>
                                                        </select>
                                                        <p id="lb-gotowebinar-loading" style="display: none;"><img alt="loading" src="<?php echo ASSETS_URL ?>/images/ajax-loader-small.gif"> Loading GoToWebinar upcoming webinars</p>
                                                        <p style="display: block;" id="lb-reload-btn-gotowebinar"><small><a onClick="MITS.loadGoToWebinarWebinars(true);
                                            return false;" href="javascript:;"><i class="icon-repeat"></i> Reload webinars</a></small></p>
                                                    </div>
                                                <?php } ?>
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
                        <div class="mits-form-space" id="mits-edit-text">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <input type="text" name="mits-text" class="required input-w90" id="id-mits-text">
                                    </div>
                                </div>
                            </form>
                        </div>
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
                        <div class="mits-form-space" id="mits-edit-video">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <h4 class="mits-modal-header blue">Video Player Style</h4>
                                        <input type="hidden" name="video-player-style" class="required input-w90" id="video-player-style">
                                        <div id="video-player-group" data-toggle="buttons-radio" class="btn-group" element_id=''>
                                            <button onClick="MITS.videoPlayerStyle(1);" class="btn btn-small" type="button" id="video-player-style-1">Style 1</button>
                                            <button onClick="MITS.videoPlayerStyle(2);" class="btn btn-small" type="button" id="video-player-style-2">Style 2</button>
                                            <button onClick="MITS.videoPlayerStyle(3);" class="btn btn-small" type="button" id="video-player-style-3">Style 3</button>
                                        </div>
                                    </div>
                                </div>
								
								<div class="control-group">
                                    <div class="input-space controls">
                                        <h4 class="mits-modal-header blue">CTA Videos</h4>
										<div class="comctavideos">
											<ul>
											<?php
											require_once JPATH_ROOT.'/administrator/components/com_cta/models/cta.php';
											$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
											$cta_setting = $ctaModel->getSetting();
											$vids = $ctaModel->getVideos();
											//print_r($vids);
											$rooturl = JURI::root(false);
											foreach ($vids as $vid) {
												echo "<li><a class='comctavideo' href='".$rooturl."index.php?option=com_cta&view=video&layout=video&vidfile=$vid[VideoFile]&vidimg=$vid[ImgCTA]'>$vid[Title]</a> (<a href='".$rooturl."index.php?option=com_cta&view=video&layout=video&vidfile=$vid[VideoFile]&vidimg=$vid[ImgCTA]' class='ctapreview'>preview</a>)</li>\n\r";
											}//for
											?>
											</ul>
										</div><!--comctavideos-->
										<div style="display:none;">
											<div id="previewdiv" class="white-popup mfp-with-anim mfp-hide"></div>
										</div>
										<script>
										 jQuery(document).ready(function($) {										 
										 	$('a.comctavideo').click(function(e) {
												e.preventDefault();
												var src = $(this).attr('href');
												var html = '<iframe src="'+src+'" frameborder="0" width="800" height="600"></iframe>';
												$('#id-mits-video').val(html);
											});
											
											$("a.ctapreview").click(function(e) {
												e.preventDefault();
												var src = $(this).attr('href');
												
												
												var html = '<p>Aliquam libero diam, cursus ac iaculis a, tempus vitae justo. Aenean nisl massa, tristique nec ullamcorper eu, sagittis sit amet leo. Nam ullamcorper condimentum tempor. Mauris fermentum urna sit amet velit consectetur varius. Curabitur rhoncus volutpat imperdiet. Duis consequat justo sed ligula dictum viverra.</p>';
												
												var html = '<iframe src="'+src+'" frameborder="0" width="800" height="600"></iframe>';
												
												
												$('#previewdiv').html(html);
												
												
												/*
												$.fancybox.open({
													href : '#previewdiv',
													type : 'inline',
													padding : 5,
													width: 800,
													height: 600,
													autoSize: false,
													minHeight: 600,
													openEffect: 'elastic'
												});
												*/
												
												$.magnificPopup.open({
													items: {
														src: '#previewdiv',
														type: 'inline'
													},
													removalDelay: 500, //delay removal by X to allow out-animation
													callbacks: {
														beforeOpen: function() {
															this.st.mainClass = 'mfp-newspaper';
														}
													},
													midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
												});
												
												
											});
											
										 });
										</script>
                                    </div>
                                </div>
								
								
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
                        <div class="mits-form-space" id="mits-edit-optin-box">
                            <form>
                                <div class="control-group">
                                    <div class="input-space controls">
                                        <div style="position: relative; vertical-align: top; display: block; " class="opt_timev_wrap">
                                            <div airButtons
                                                 style="display: inline-block;" class="input-append">
                                                <input type="text" id="optin-box-min" class="input-mini tright required">
                                                <span class="add-on">min</span>
                                            </div>
                                            <div style="display: inline-block;" class="input-append">
                                                <input type="text" id="optin-box-sec" class="input-mini tright required">
                                                <span class="add-on">sec</span>
                                            </div>
                                        </div>
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
                        <div class="mits-form-space" id="mits-edit-placeholder">
                            <form>
                                <div class="control-group">
                                    <label for="id-mits-placeholder" class="control-label">Placeholder to display</label>
                                    <div class="input-space controls">
                                        <input type="text" name="mits-placeholder" class="input-w90" id="id-mits-placeholder">
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
                            <h4>2) or <iframe frameborder="0" noresize="noresize" id="mits-image-iframe" src="<?php echo JURI::base() ?>/index.php?option=com_advisorlead&task=templates.api_call&request=upload-image&type=page"></iframe></h4>
                            <p style="margin-top:-10px;"><small class="muted">Maximum image size is 5 MB.</small></p>
                        </div>
                        <div class="mits-form-space" id="mits-font-selector">
                            <div class="control-group">
                                <label class="control-label">Font</label>
                                <div class="input-space controls">
                                    <input type="hidden" value="" name="mits-text" id="id-mits-selected-font">
                                    <div id="id-mits-font-selection-space">
                                        <button data-lb-font-name="" onClick="$('.font-btn').removeClass('btn-success active');
                                        $(this).addClass('btn-success active');
                                        $('#id-mits-selected-font').val('');
                                        return false;" class="btn font-btn btn-block">Default font</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mits-form-space" id="mits-comment-text">
                            <p class="muted"></p>
                        </div>
                    </div>
                    <div class="mits-form-space" id="mits-target-page-settings">
                        <form class="form-vertical" id="target-page-settings-form">
                            <div class="control-group">
                                <p id="mits-target-page-message">You haven't selected a Conversion Goal Page yet.</p>
                                <a id="mits-target-page-choose" class="btn" href="javascript:;">Choose Conversion Goal Page</a>
                                <a id="mits-target-page-remove" class="btn btn-danger hide" href="javascript:;">Remove Conversion Goal Page</a>
                            </div>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-target-url-settings">
                        <form class="form-vertical" id="target-url-settings-form">
                            <div class="control-group">
                                <label for="target-url" class="control-label">Target URL:</label>
                                <div class="input-space controls">
                                    <input type="text" value="" id="target-url" placeholder="Target url, including http://" class="required input-w84 checkUrl">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mits-form-space" id="mits-button-colors">

                    </div>
                    <div class="mits-form-space" id="mits-button-styles">

                    </div>
                    <div class="tcenter" id="lb-submit-btns">
                        <hr>
                        <button class="btn btn-primary" id="mits-form-submit">Okay</button>
                        <button onClick="MITS.closeEditor();" data-dismiss="modal" class="btn">Cancel</button>
                    </div>
                    <a title="Close" class="editor-close" onClick="MITS.closeEditor();"></a>
                </div>
            </div>
        </div>
    </div>
</div>