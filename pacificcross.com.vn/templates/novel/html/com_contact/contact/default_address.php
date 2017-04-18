<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<div class="pure-u-1 pure-u-md-1-3">
    <div class="pure-g">
        <div class="pure-u-1-4 text-center">
            <span class="icon-border"><i class="ion ion-ios-telephone"></i></span>
        </div><!-- end of pure-u-1-4 -->
        <div class="pure-u-3-4">
        	<?php if ($this->contact->telephone && $this->params->get('show_telephone')) : ?>
	            <h2 itemprop="telephone"><?php echo $this->params->get('marker_telephone'); ?></h2>
	            <p><?php echo nl2br($this->contact->telephone); ?></p>
            <?php endif; ?>
        </div><!-- end of pure-u-3-4 -->
    </div><!-- end of pure-g -->
</div><!-- end of pure-u-1 pure-u-md-1-3 -->

<div class="pure-u-1 pure-u-md-1-3">
    <div class="pure-g">
        <div class="pure-u-1-4 text-center">
            <span class="icon-border"><i class="ion ion-ios-email"></i></span>
        </div><!-- end of pure-u-1-4 -->
        <div class="pure-u-3-4">
        	<?php if ($this->contact->email_to && $this->params->get('show_email')) : ?>
            	<h2 itemprop="email"><?php echo $this->params->get('marker_email'); ?></h2>
            	<p><?php echo $this->contact->email_to; ?></p>
            <?php endif; ?>
        </div><!-- end of pure-u-3-4 -->
    </div><!-- end of pure-g -->
</div><!-- end of pure-u-1 pure-u-md-1-3 -->

<div class="pure-u-1 pure-u-md-1-3">
     <div class="pure-g">
        <div class="pure-u-1-4 text-center">
            <span class="icon-border"><i class="ion ion-ios-location"></i></span>
        </div><!-- end of pure-u-1-4 -->
        <div class="pure-u-3-4">
        	<?php if ($this->params->get('address_check') > 0) : ?>
            	<h2><?php echo $this->params->get('marker_address'); ?></h2>
            <?php endif; ?>
            <p>
            	<?php if (($this->params->get('address_check') > 0) && ($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode)) : ?>
					<?php if ($this->contact->address && $this->params->get('show_street_address')) : ?>
						<span class="contact-street" itemprop="streetAddress">
							<?php echo nl2br($this->contact->address) . ', '; ?>
						</span>
					<?php endif; ?>

					<?php if ($this->contact->suburb && $this->params->get('show_suburb')) : ?>
						<span class="contact-suburb" itemprop="addressLocality">
							<?php echo $this->contact->suburb . ', '; ?>
					<?php endif; ?>
					<?php if ($this->contact->state && $this->params->get('show_state')) : ?>
						<span class="contact-state" itemprop="addressRegion">
							<?php echo $this->contact->state . ', '; ?>
						</span>
					<?php endif; ?>
					<?php if ($this->contact->postcode && $this->params->get('show_postcode')) : ?>
						<span class="contact-postcode" itemprop="postalCode">
							<?php echo $this->contact->postcode . ', '; ?>
						</span>
					<?php endif; ?>
					<?php if ($this->contact->country && $this->params->get('show_country')) : ?>
						<span class="contact-country" itemprop="addressCountry">
							<?php echo $this->contact->country; ?>
						</span>
					<?php endif; ?>
				<?php endif; ?>
            </p>
        </div><!-- end of pure-u-3-4 -->
    </div><!-- end of pure-g -->
</div><!-- end of pure-u-1 pure-u-md-1-3 -->

<?php if ($this->params->get('show_contact_list') && count($this->contacts) > 1) : ?>
	<div class="pure-u-1">
	    <form action="#" method="get" class="pure-form pure-form-aligned form-contact-list" name="selectForm" id="selectForm">
	        <fieldset>
	            <div class="pure-control-group">
	                <label for="contact"><?php echo JText::_('COM_CONTACT_SELECT_CONTACT'); ?></label>
	                <?php echo JHtml::_('select.genericlist', $this->contacts, 'contact-chooser', 'onchange="document.location.href = this.value"', 'link', 'name', $this->contact->link);?>
	            </div>
	        </fieldset>
	    </form>
	</div><!-- end of pure-u-1 -->	
<?php endif; ?>