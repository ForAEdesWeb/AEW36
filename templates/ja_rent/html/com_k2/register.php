<?php
/*
 * ------------------------------------------------------------------------
 * JA Rent template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;

?>

<!-- K2 user register form -->
<?php if(isset($this->message)) $this->display('message'); ?>

<form action="<?php echo JURI::root(true); ?>/index.php" enctype="multipart/form-data" method="post" id="josForm" name="josForm" class="form-validate form-horizontal">
	<?php if($this->params->def('show_page_title',1)): ?>
	<h2 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h2>
	<?php endif; ?>
	
	<div class="k2AccountPageNotice"><?php echo JText::_('K2_REGISTER_REQUIRED'); ?></div>
	
	<div id="k2Container" class="k2AccountPage">
		<hr>
			<h4 class="k2ProfileHeading">
				<?php echo JText::_('K2_ACCOUNT_DETAILS'); ?>
			</h4>
			<div class="form-group">
				<label class="col-sm-3 control-label" id="namemsg" for="name"><?php echo JText::_('K2_NAME'); ?>*</label>
				<div class="col-sm-9">
					<input type="text" name="<?php echo $this->nameFieldName; ?>" id="name" size="40" value="<?php echo $this->escape($this->user->get( 'name' )); ?>" class="inputbox form-control required" maxlength="50" />
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" id="usernamemsg" for="username"><?php echo JText::_('K2_USER_NAME'); ?>*</label>
				<div class="col-sm-9">
					<input type="text" id="username" name="<?php echo $this->usernameFieldName; ?>" size="40" value="<?php echo $this->escape($this->user->get( 'username' )); ?>" class="inputbox required validate-username" maxlength="25" />
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" id="emailmsg" for="email"><?php echo JText::_('K2_EMAIL'); ?>*</label>
				<div class="col-sm-9">
					<input type="text" id="email" name="<?php echo $this->emailFieldName; ?>" size="40" value="<?php echo $this->escape($this->user->get( 'email' )); ?>" class="inputbox required validate-email" maxlength="100" />
					
				</div>
			</div>
			<?php if(version_compare(JVERSION, '1.6', 'ge')): ?>
			<div class="form-group">
				<label class="col-sm-3 control-label" id="email2msg" for="email2"><?php echo JText::_('K2_CONFIRM_EMAIL'); ?>*</label>
				<div class="col-sm-9">
					<input type="text" id="email2" name="jform[email2]" size="40" value="" class="inputbox required validate-email" maxlength="100" />

				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-3 control-label" id="pwmsg" for="password"><?php echo JText::_('K2_PASSWORD'); ?>*</label>
				<div class="col-sm-9">
					<input class="inputbox required validate-password" type="password" id="password" name="<?php echo $this->passwordFieldName; ?>" size="40" value="" />

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" id="pw2msg" for="password2"><?php echo JText::_('K2_VERIFY_PASSWORD'); ?>*</label>
				<div class="col-sm-9">
					<input class="inputbox required validate-passverify" type="password" id="password2" name="<?php echo $this->passwordVerifyFieldName; ?>" size="40" value="" />

				</div>
			</div>
			<hr>
			<h4 class="k2ProfileHeading">
				<?php echo JText::_('K2_PERSONAL_DETAILS'); ?>
			</h4>
			<div class="k2PersonalDetails">
				<!-- K2 attached fields -->
				<div class="form-group">
					<label class="col-sm-3 control-label" id="gendermsg" for="gender"><?php echo JText::_('K2_GENDER'); ?></label>
					<div class="col-sm-9">
						<div class="radio-inline">
							<?php echo $this->lists['gender']; ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" id="descriptionmsg" for="description"><?php echo JText::_('K2_DESCRIPTION'); ?></label>
					<div class="col-sm-9">
						<?php echo $this->editor; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" id="imagemsg" for="image"><?php echo JText::_( 'K2_USER_IMAGE_AVATAR' ); ?></label>
					<div class="col-sm-9">
						<input type="file" id="image" name="image"/>
						<?php if ($this->K2User->image): ?>
						<img class="k2AdminImage" src="<?php echo JURI::root().'media/k2/users/'.$this->K2User->image; ?>" alt="<?php echo $this->user->name; ?>" />
						<input type="checkbox" name="del_image" id="del_image" />
						<label class="col-sm-3 control-label" for="del_image"><?php echo JText::_('K2_CHECK_THIS_BOX_TO_DELETE_CURRENT_IMAGE_OR_JUST_UPLOAD_A_NEW_IMAGE_TO_REPLACE_THE_EXISTING_ONE'); ?></label>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" id="urlmsg" for="url"><?php echo JText::_('K2_URL'); ?></label>
					<div class="col-sm-9">
						<input type="text" size="50" value="<?php echo $this->K2User->url; ?>" name="url" id="url"/>
					</div>
				</div>
			</div>
			<?php if(count(array_filter($this->K2Plugins))): ?>
			<hr>	
			<!-- K2 Plugin attached fields -->
			<h4 class="k2ProfileHeading">
				<?php echo JText::_('K2_ADDITIONAL_DETAILS'); ?>
			</h4>
			<?php foreach ($this->K2Plugins as $K2Plugin): ?>
			<?php if(!is_null($K2Plugin)): ?>
			<div class="form-group">
				<?php echo $K2Plugin->fields; ?>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>
			
			<!-- Joomla! 1.6+ JForm implementation -->
			<?php if(isset($this->form)): ?>
			<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
				<?php if($fieldset->name != 'default'): ?>
				<?php $fields = $this->form->getFieldset($fieldset->name);?>
				<?php if (count($fields)):?>
					<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
						<hr>
					<h4 class="k2ProfileHeading">
						<?php echo JText::_($fieldset->label);?>
					</h4>
					<?php endif;?>
					<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
						<?php if ($field->hidden):// If the field is hidden, just display the input.?>
							<div class="form-group"><?php echo $field->input;?></div>
						<?php else:?>
							<div class="form-group">
								<td class="key">
									<?php echo $field->label; ?>
									<?php if (!$field->required && $field->type != 'Spacer'): ?>
										<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
									<?php endif; ?>
								</div>
								<div class="col-sm-9"><?php echo $field->input;?></div>
							</div>
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
				<?php endif; ?>
			<?php endforeach;?>
			<?php endif; ?>

		
		<?php if($this->K2Params->get('recaptchaOnRegistration') && $this->K2Params->get('recaptcha_public_key')): ?>
		<div class="form-group">
			<label class="formRecaptcha col-sm-3 control-label"><?php echo JText::_('K2_ENTER_THE_TWO_WORDS_YOU_SEE_BELOW'); ?></label>
			<div class="col-sm-9" id="recaptcha"></div>
		</div>
		<?php endif; ?>
		
		<div class="k2AccountPageUpdate">
			<button class="button validate btn-primary btn" type="submit">
				<?php echo JText::_('K2_REGISTER'); ?>
			</button>
		</div>
	</div>
	<input type="hidden" name="option" value="<?php echo $this->optionValue; ?>" />
	<input type="hidden" name="task" value="<?php echo $this->taskValue; ?>" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="gid" value="0" />
	<input type="hidden" name="K2UserForm" value="1" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
