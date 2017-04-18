<?php
/* ------------------------------------------------------------------------
  # integrations.php - AdvisorLead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$integrations_url = ADVISORLEAD_URL . '/dashboard/integrations';
?>
<div class="row-fluid">
    <div class="span12">
        <?php if (empty($this->integration)) { ?>
            <h3>Email Services</h3>
            <hr>
            <div id="row_email">
                <h5>Email Marketing Services</h5>
                <ul class="current-integrations1">
                    <li>
                        <a href="<?php echo $integrations_url; ?>/aweber/"><div class="integrations-AWeber"></div></a>
                    </li>
                    <li>
                        <a href="<?php echo $integrations_url; ?>/mailchimp/"><div class="integrations-MailChimp"></div></a>
                    </li>
                    <li>
                        <a href="<?php echo $integrations_url; ?>/infusionsoft/"><div class="integrations-InfusionSoft"></div></a>
                    </li>
                    <li>
                        <a href="<?php echo $integrations_url; ?>/icontact/"><div class="integrations-iContact"></div></a>
                    </li>
                    <li>
                        <a href="<?php echo $integrations_url; ?>/getresponse/"><div class="integrations-GetResponse"></div></a>
                    </li>
                    <li>
                        <a href="<?php echo $integrations_url; ?>/constantcontact/"><div class="integrations-ConstantContact"></div></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div id="row_webinar">
                <h5>Webinar services</h5>
                <ul class="current-integrations2">
                    <li>
                        <a href="<?php echo $integrations_url; ?>/gotowebinar/"><div class="integrations-GoToWebinar"></div></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <?php
        } else {
            ?>
            <h3><?php echo $this->integration_title; ?></h3>
            <hr/>
            <?php
            if (!$this->integrations[$this->integration]['active']) {
                switch ($this->integration) {
                    case "mailchimp":
                        ?>
                        <form class="form-horizontal" action="<?php echo JURI::current() ?>connect" method="POST">
                            <fieldset>
                                <div class="control-group">
                                    <label for="id-template-name" class="control-label">API Token</label>
                                    <div class="input-space controls">
                                        <input type="text" name="mailchimp_token" class="required input-xlarge" id="id-mailchimp-token" value="">
                                    </div>
                                </div>
                                <div class="controls">
                                    <button class="btn btn-primary" type="submit">Connect to Mailchimp</button>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                        break;

                    default:
                        ?>
                        <a href="<?php echo "$integrations_url/$this->integration/connect"; ?>" class="btn"><i class="icon-magnet"></i> Connect to <?php echo $this->integration_title ?></a>
                        <?php
                        break;
                }
            } else {
                ?>
                <p>
                    <i class="icon-ok"></i>
                    <?php echo $this->integration_title ?> is connected <a class="btn btn-small fright" href="<?php echo "$integrations_url/$this->integration/disconnect"; ?>"><i class="icon-remove"></i> Disconnect</a>
                </p>
                <hr>
                <?php if (1 == 0) { ?>
                    <div class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label">Allow copy/paste</label>
                            <div class="controls">
                                <div data-handler="<?php echo $base_url; ?>/my-account/services/aweber/toggle" id="copy-paste" class="btn-group">
                                    <button class="btn on<?php if ($this->integrations[$this->integration]['copy_paste']) echo ' active btn-success'; ?>">On</button>
                                    <button class="btn off<?php if (!$this->integrations[$this->integration]['copy_paste']) echo ' active btn-danger'; ?>">Off</button>
                                </div>
                                <small class="muted hide" id="copy-paste-saved" style="display: none;">Saved</small>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        var message = <?php echo!empty($this->message) ? $this->message : '' ?>;
        if (message)
            addAlertFromResponse(message);

    });

</script>