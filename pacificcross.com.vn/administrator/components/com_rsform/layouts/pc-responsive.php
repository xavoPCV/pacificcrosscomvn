{error}
<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->
<fieldset class="formHorizontal formContainer" id="rsform_{global:formid}_page_0">
	<div class="rsform-block rsform-block-step1header">
		{Step1Header:caption}
		{Step1Header:body}
	</div>

	<div class="rsform-block rsform-block-applytingto">
		<div class="formControlLabel">{ApplytingTo:caption}</div>
		<div class="formControls">
			<div class="formBody" style="max-width:320px">{ApplytingTo:body}<span class="formValidation">{ApplytingTo:validation}</span></div>
			<p class="formDescription">{ApplytingTo:description}</p>
		</div>
	</div>

	<div class="rsform-block rsform-block-policyholdernameemployername">
		<div class="formControlLabel">{PolicyHolderNameEmployerName:caption}</div>
		<div class="formControls">
			<div class="formBody">{PolicyHolderNameEmployerName:body}<span class="formValidation">{PolicyHolderNameEmployerName:validation}</span></div>
			<p class="formDescription">{PolicyHolderNameEmployerName:description}</p>
		</div>
	</div>
	<div class="rsform-block rsform-block-policyholdername">
		<div class="formControlLabel">{PolicyHolderName:caption}</div>
		<div class="formControls">
		<div class="formBody">{PolicyHolderName:body}<span class="formValidation">{PolicyHolderName:validation}</span></div>
		<p class="formDescription">{PolicyHolderName:description}</p>
		</div>
	</div>
	<div class="rsform-block rsform-block-policyholdertelephone">
		<div class="formControlLabel">Policy Holder Information</div>
		<div class="formControls">
			<div class="formBody">
				<div class="pure-g">
					<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
						<div class="">
							<label class="cd-label">{PolicyHolderTelephone:caption}</label>
							{PolicyHolderTelephone:body}<span class="formValidation">{PolicyHolderTelephone:validation}</span>
							<p class="formDescription">{PolicyHolderTelephone:description}</p>
						</div>
					</div>
					<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
						<div class="rsform-block-policyholderemail">
							<label class="cd-label">{PolicyHolderEmail:caption}</label>
							{PolicyHolderEmail:body}<span class="formValidation">{PolicyHolderEmail:validation}</span>
							<p class="formDescription">{PolicyHolderEmail:description}</p>
						</div>
					</div>
					<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
						<div class="rsform-block-effectivedate">
							<!-- <label class="cd-label">{EffectiveDate:caption}</label> -->
							{EffectiveDate:body}<span class="formValidation">{EffectiveDate:validation}</span>
							<p class="formDescription">{EffectiveDate:description}</p>
						</div>
					</div>
				</div><!-- end of pure-g -->
			</div>
		</div>
	</div>

	<div class="rsform-block rsform-block-policyholderaddress">
		<div class="formControlLabel">{PolicyHolderAddress:caption}</div>
		<div class="formControls">
			<div class="formBody">{PolicyHolderAddress:body}<span class="formValidation">{PolicyHolderAddress:validation}</span></div>
			<p class="formDescription">{PolicyHolderAddress:description}</p>
		</div>
	</div>

	<div class="rsform-block rsform-block-tostep2">
		<p class="text-center margin-top">
			{ToStep2:body}
		</p>
	</div>
</fieldset>

<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->
<fieldset class="formHorizontal formContainer" id="rsform_{global:formid}_page_1">
	<div class="rsform-block rsform-block-step2header">
		{Step2header:caption}
		{Step2header:body}<span class="formValidation">{Step2header:validation}</span>
	</div>
	<div class="rsform-block rsform-block-1stpersonfirstname">
		<div class="pure-g">
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonFirstName:caption}</label>
					{1stPersonFirstName:body}<span class="formValidation">{1stPersonFirstName:validation}</span>
					<p class="formDescription">{1stPersonFirstName:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonFamilyName:caption}</label>
					{1stPersonFamilyName:body}<span class="formValidation">{1stPersonFamilyName:validation}</span>
					<p class="formDescription">{1stPersonFamilyName:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonEmail:caption}</label>
					{1stPersonEmail:body}<span class="formValidation">{1stPersonEmail:validation}</span>
					<p class="formDescription">{1stPersonEmail:description}</p>
				</div>
			</div>
		</div><!-- end of pure-g -->

		<div class="pure-g" style="margin-top: 30px">
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonPhone:caption}</label>
					{1stPersonPhone:body}<span class="formValidation">{1stPersonPhone:validation}</span>
					<p class="formDescription">{1stPersonPhone:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonOccupation:caption}</label>
					{1stPersonOccupation:body}<span class="formValidation">{1stPersonOccupation:validation}</span>
					<p class="formDescription">{1stPersonOccupation:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonGender:caption}</label>
					{1stPersonGender:body}<span class="formValidation">{1stPersonGender:validation}</span>
					<p class="formDescription">{1stPersonGender:description}</p>
				</div>
			</div>
		</div><!-- end of pure-g -->
		
		<div class="pure-g" style="margin-top: 30px">
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label float2">{1stPersonDOB2:caption}</label>
					{1stPersonDOB2:body}<span class="formValidation">{1stPersonDOB2:validation}</span>
					<p class="formDescription">{1stPersonDOB2:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonRelationship:caption}</label>
					{1stPersonRelationship:body}<span class="formValidation">{1stPersonRelationship:validation}</span>
					<p class="formDescription">{1stPersonRelationship:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonSmoker:caption}</label>
					{1stPersonSmoker:body}<span class="formValidation">{1stPersonSmoker:validation}</span>
					<p class="formDescription">{1stPersonSmoker:description}</p>
				</div>
			</div>
		</div><!-- end of pure-g -->

		<div class="pure-g" style="margin-top: 30px">
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonHeight:caption}</label>
					{1stPersonHeight:body}<span class="formValidation">{1stPersonHeight:validation}</span>
					<p class="formDescription">{1stPersonHeight:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonWeight:caption}</label>
					{1stPersonWeight:body}<span class="formValidation">{1stPersonWeight:validation}</span>
					<p class="formDescription">{1stPersonWeight:description}</p>
				</div>
			</div>
		</div><!-- end of pure-g -->

		<div class="pure-g" style="margin-top: 30px">
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonPassport:caption}</label>
					{1stPersonPassport:body}<span class="formValidation">{1stPersonPassport:validation}</span>
					<p class="formDescription">{1stPersonPassport:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonCountryPassport:caption}</label>
					{1stPersonCountryPassport:body}<span class="formValidation">{1stPersonCountryPassport:validation}</span>
					<p class="formDescription">{1stPersonCountryPassport:description}</p>
				</div>
			</div>
			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonCountryResidence:caption}</label>
					{1stPersonCountryResidence:body}<span class="formValidation">{1stPersonCountryResidence:validation}</span>
					<p class="formDescription">{1stPersonCountryResidence:description}</p>
				</div>
			</div>
		</div><!-- end of pure-g -->

		<div class="pure-g" style="margin-top: 30px">
			<div class="pure-u-1 pure-u-sm-2-3 plr-15px">
				<div>
					<label class="cd-label">{1stPersonAddress:caption}</label>
					{1stPersonAddress:body}<span class="formValidation">{1stPersonAddress:validation}</span>
					<p class="formDescription">{1stPersonAddress:description}</p>
				</div>
			</div>
		</div><!-- end of pure-g -->
	</div>
		<div class="rsform-block rsform-block-tostep3">
			<p class="text-center margin-top">
				{ToStep3:body}
			</p>
		</div>
</fieldset>

<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->
<fieldset class="formHorizontal formContainer" id="rsform_{global:formid}_page_2">
	<div class="rsform-block rsform-block-step-3-header">
		{Step 3 header:caption}
		{Step 3 header:body}
	</div>
	<div class="pure-g white-box">
		<div class="pure-u-1">
			<p><strong>{Q1a:caption}</strong></p>
		</div>
		<div class="pure-u-1 pure-u-md-1-5">
			<div class="rsform-block rsform-block-q1a">
				<div class="formBody">{Q1a:body}<span class="formValidation">{Q1a:validation}</span></div>
				<p class="formDescription">{Q1a:description}</p>
			</div>
		</div><!-- end of pure-u-1 pure-u-1-5 -->
		<div class="pure-u-1 pure-u-md-4-5 plr-15px">
			<div class="rsform-block rsform-block-q1aextra">
				<p><strong>{Q1aExtra:caption}</strong></p>
				<div class="formBody"><p>{Q1aExtra:body}</p><span class="formValidation">{Q1aExtra:validation}</span></div>
				<p class="formDescription">{Q1aExtra:description}</p>
			</div>
		</div>
	</div><!-- end fo pure-g -->
	
	<div class="pure-g white-box">
		<div class="pure-u-1">
			<p><strong>{Q1b:caption}</strong></p>
		</div>
		<div class="pure-u-1 pure-u-md-1-5">
			<div class="rsform-block rsform-block-q1b">
				<div class="formBody">{Q1b:body}<span class="formValidation">{Q1b:validation}</span></div>
				<p class="formDescription">{Q1b:description}</p>
			</div>
		</div><!-- end of pure-u-1 pure-u-1-5 -->
		<div class="pure-u-1 pure-u-md-4-5 plr-15px">
			<div class="rsform-block rsform-block-q1bextra">
				<label class="cd-label">{Q1bExtra:caption}</label>
				{Q1bExtra:body}<span class="formValidation">{Q1bExtra:validation}</span>
				<p class="formDescription">{Q1bExtra:description}</p>
			</div>
		</div>
	</div><!-- end fo pure-g -->

	<div class="pure-g white-box">
		<div class="pure-u-1">
			<p><strong>{Q1c:caption}</strong></p>
		</div>
		<div class="pure-u-1 pure-u-md-1-5">
			<div class="rsform-block rsform-block-q1c">
				<div class="formBody">{Q1c:body}<span class="formValidation">{Q1c:validation}</span></div>
				<p class="formDescription">{Q1c:description}</p>
			</div>
		</div><!-- end of pure-u-1 pure-u-1-5 -->
		<div class="pure-u-1 pure-u-md-4-5 plr-15px">
			<div class="rsform-block rsform-block-q1cextra">
				<label class="cd-label">{Q1cExtra:caption}</label>
				{Q1cExtra:body}<span class="formValidation">{Q1cExtra:validation}</span>
				<p class="formDescription">{Q1cExtra:description}</p>
			</div>
		</div>
	</div><!-- end fo pure-g -->

	<div class="rsform-block rsform-block-q2info">
		<h2>{Q2info:body}</h2>
	</div>
	<div class="white-box">
		<div class="rsform-block rsform-block-q2a">
			<p><strong>{Q2a:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2a:body}<span class="formValidation">{Q2a:validation}</span></div>
			<p class="formDescription">{Q2a:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2aextra1">
			<p><strong>{Q2aExtra1:caption}</strong></p>
			<div class="formBody">{Q2aExtra1:body}<span class="formValidation">{Q2aExtra1:validation}</span></div>
			<p class="formDescription">{Q2aExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2aextra2">
			<p><strong>{Q2aExtra2:caption}</strong></p>
			<div class="formBody">{Q2aExtra2:body}<span class="formValidation">{Q2aExtra2:validation}</span></div>
			<p class="formDescription">{Q2aExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2aextra3">
			<p><strong>{Q2aExtra3:caption}</strong></p>
			<div class="formBody">{Q2aExtra3:body}<span class="formValidation">{Q2aExtra3:validation}</span></div>
			<p class="formDescription">{Q2aExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2aextra4">
			<p><strong>{Q2aExtra4:caption}</strong></p>
			<div class="formBody">{Q2aExtra4:body}<span class="formValidation">{Q2aExtra4:validation}</span></div>
			<p class="formDescription">{Q2aExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2aextra5">
			<p><strong>{Q2aExtra5:caption}</strong></p>
			<div class="formBody">{Q2aExtra5:body}<span class="formValidation">{Q2aExtra5:validation}</span></div>
			<p class="formDescription">{Q2aExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2b">
			<p><strong>{Q2b:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2b:body}<span class="formValidation">{Q2b:validation}</span></div>
			<p class="formDescription">{Q2b:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2bextra1">
			<p><strong>{Q2bExtra1:caption}</strong></p>
			<div class="formBody">{Q2bExtra1:body}<span class="formValidation">{Q2bExtra1:validation}</span></div>
			<p class="formDescription">{Q2bExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2bextra2">
			<p><strong>{Q2bExtra2:caption}</strong></p>
			<div class="formBody">{Q2bExtra2:body}<span class="formValidation">{Q2bExtra2:validation}</span></div>
			<p class="formDescription">{Q2bExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2bextra3">
			<p><strong>{Q2bExtra3:caption}</strong></p>
			<div class="formBody">{Q2bExtra3:body}<span class="formValidation">{Q2bExtra3:validation}</span></div>
			<p class="formDescription">{Q2bExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2bextra4">
			<p><strong>{Q2bExtra4:caption}</strong></p>
			<div class="formBody">{Q2bExtra4:body}<span class="formValidation">{Q2bExtra4:validation}</span></div>
			<p class="formDescription">{Q2bExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2bextra5">
			<p><strong>{Q2bExtra5:caption}</strong></p>
			<div class="formBody">{Q2bExtra5:body}<span class="formValidation">{Q2bExtra5:validation}</span></div>
			<p class="formDescription">{Q2bExtra5:description}</p>
			<hr>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2c">
			<p><strong>{Q2c:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2c:body}<span class="formValidation">{Q2c:validation}</span></div>
			<p class="formDescription">{Q2c:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2cextra1">
			<p><strong>{Q2cExtra1:caption}</strong></p>
			<div class="formBody">{Q2cExtra1:body}<span class="formValidation">{Q2cExtra1:validation}</span></div>
			<p class="formDescription">{Q2cExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2cextra2">
			<p><strong>{Q2cExtra2:caption}</strong></p>
			<div class="formBody">{Q2cExtra2:body}<span class="formValidation">{Q2cExtra2:validation}</span></div>
			<p class="formDescription">{Q2cExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2cextra3">
			<p><strong>{Q2cExtra3:caption}</strong></p>
			<div class="formBody">{Q2cExtra3:body}<span class="formValidation">{Q2cExtra3:validation}</span></div>
			<p class="formDescription">{Q2cExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2cextra4">
			<p><strong>{Q2cExtra4:caption}</strong></p>
			<div class="formBody">{Q2cExtra4:body}<span class="formValidation">{Q2cExtra4:validation}</span></div>
			<p class="formDescription">{Q2cExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2cextra5">
			<p><strong>{Q2cExtra5:caption}</strong></p>
			<div class="formBody">{Q2cExtra5:body}<span class="formValidation">{Q2cExtra5:validation}</span></div>
			<p class="formDescription">{Q2cExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2d">
			<p><strong>{Q2d:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2d:body}<span class="formValidation">{Q2d:validation}</span></div>
			<p class="formDescription">{Q2d:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2dextra1">
			<p><strong>{Q2dExtra1:caption}</strong></p>
			<div class="formBody">{Q2dExtra1:body}<span class="formValidation">{Q2dExtra1:validation}</span></div>
			<p class="formDescription">{Q2dExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2dextra2">
			<p><strong>{Q2dExtra2:caption}</strong></p>
			<div class="formBody">{Q2dExtra2:body}<span class="formValidation">{Q2dExtra2:validation}</span></div>
			<p class="formDescription">{Q2dExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2dextra3">
			<p><strong>{Q2dExtra3:caption}</strong></p>
			<div class="formBody">{Q2dExtra3:body}<span class="formValidation">{Q2dExtra3:validation}</span></div>
			<p class="formDescription">{Q2dExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2dextra4">
			<p><strong>{Q2dExtra4:caption}</strong></p>
			<div class="formBody">{Q2dExtra4:body}<span class="formValidation">{Q2dExtra4:validation}</span></div>
			<p class="formDescription">{Q2dExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2dextra5">
			<p><strong>{Q2dExtra5:caption}</strong></p>
			<div class="formBody">{Q2dExtra5:body}<span class="formValidation">{Q2dExtra5:validation}</span></div>
			<p class="formDescription">{Q2dExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2e">
			<p><strong>{Q2e:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2e:body}<span class="formValidation">{Q2e:validation}</span></div>
			<p class="formDescription">{Q2e:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2eextra1">
			<p><strong>{Q2eExtra1:caption}</strong></p>
			<div class="formBody">{Q2eExtra1:body}<span class="formValidation">{Q2eExtra1:validation}</span></div>
			<p class="formDescription">{Q2eExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2eextra2">
			<p><strong>{Q2eExtra2:caption}</strong></p>
			<div class="formBody">{Q2eExtra2:body}<span class="formValidation">{Q2eExtra2:validation}</span></div>
			<p class="formDescription">{Q2eExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2eextra3">
			<p><strong>{Q2eExtra3:caption}</strong></p>
			<div class="formBody">{Q2eExtra3:body}<span class="formValidation">{Q2eExtra3:validation}</span></div>
			<p class="formDescription">{Q2eExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2eextra4">
			<p><strong>{Q2eExtra4:caption}</strong></p>
			<div class="formBody">{Q2eExtra4:body}<span class="formValidation">{Q2eExtra4:validation}</span></div>
			<p class="formDescription">{Q2eExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2eextra5">
			<p><strong>{Q2eExtra5:caption}</strong></p>
			<div class="formBody">{Q2eExtra5:body}<span class="formValidation">{Q2eExtra5:validation}</span></div>
			<p class="formDescription">{Q2eExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2f">
			<p><strong>{Q2f:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2f:body}<span class="formValidation">{Q2f:validation}</span></div>
			<p class="formDescription">{Q2f:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2fextra1">
			<p><strong>{Q2fExtra1:caption}</strong></p>
			<div class="formBody">{Q2fExtra1:body}<span class="formValidation">{Q2fExtra1:validation}</span></div>
			<p class="formDescription">{Q2fExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2fextra2">
			<p><strong>{Q2fExtra2:caption}</strong></p>
			<div class="formBody">{Q2fExtra2:body}<span class="formValidation">{Q2fExtra2:validation}</span></div>
			<p class="formDescription">{Q2fExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2fextra3">
			<p><strong>{Q2fExtra3:caption}</strong></p>
			<div class="formBody">{Q2fExtra3:body}<span class="formValidation">{Q2fExtra3:validation}</span></div>
			<p class="formDescription">{Q2fExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2fextra4">
			<p><strong>{Q2fExtra4:caption}</strong></p>
			<div class="formBody">{Q2fExtra4:body}<span class="formValidation">{Q2fExtra4:validation}</span></div>
			<p class="formDescription">{Q2fExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2fextra5">
			<p><strong>{Q2fExtra5:caption}</strong></p>
			<div class="formBody">{Q2fExtra5:body}<span class="formValidation">{Q2fExtra5:validation}</span></div>
			<p class="formDescription">{Q2fExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2g">
			<p><strong>{Q2g:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2g:body}<span class="formValidation">{Q2g:validation}</span></div>
			<p class="formDescription">{Q2g:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2gextra1">
			<p><strong>{Q2gExtra1:caption}</strong></p>
			<div class="formBody">{Q2gExtra1:body}<span class="formValidation">{Q2gExtra1:validation}</span></div>
			<p class="formDescription">{Q2gExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2gextra2">
			<p><strong>{Q2gExtra2:caption}</strong></p>
			<div class="formBody">{Q2gExtra2:body}<span class="formValidation">{Q2gExtra2:validation}</span></div>
			<p class="formDescription">{Q2gExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2gextra3">
			<p><strong>{Q2gExtra3:caption}</strong></p>
			<div class="formBody">{Q2gExtra3:body}<span class="formValidation">{Q2gExtra3:validation}</span></div>
			<p class="formDescription">{Q2gExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2gextra4">
			<p><strong>{Q2gExtra4:caption}</strong></p>
			<div class="formBody">{Q2gExtra4:body}<span class="formValidation">{Q2gExtra4:validation}</span></div>
			<p class="formDescription">{Q2gExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2gextra5">
			<p><strong>{Q2gExtra5:caption}</strong></p>
			<div class="formBody">{Q2gExtra5:body}<span class="formValidation">{Q2gExtra5:validation}</span></div>
			<p class="formDescription">{Q2gExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2h">
			<p><strong>{Q2h:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2h:body}<span class="formValidation">{Q2h:validation}</span></div>
			<p class="formDescription">{Q2h:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2hextra1">
			<p><strong>{Q2hExtra1:caption}</strong></p>
			<div class="formBody">{Q2hExtra1:body}<span class="formValidation">{Q2hExtra1:validation}</span></div>
			<p class="formDescription">{Q2hExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2hextra2">
			<p><strong>{Q2hExtra2:caption}</strong></p>
			<div class="formBody">{Q2hExtra2:body}<span class="formValidation">{Q2hExtra2:validation}</span></div>
			<p class="formDescription">{Q2hExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2hextra3">
			<p><strong>{Q2hExtra3:caption}</strong></p>
			<div class="formBody">{Q2hExtra3:body}<span class="formValidation">{Q2hExtra3:validation}</span></div>
			<p class="formDescription">{Q2hExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2hextra4">
			<p><strong>{Q2hExtra4:caption}</strong></p>
			<div class="formBody">{Q2hExtra4:body}<span class="formValidation">{Q2hExtra4:validation}</span></div>
			<p class="formDescription">{Q2hExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2hextra5">
			<p><strong>{Q2hExtra5:caption}</strong></p>
			<div class="formBody">{Q2hExtra5:body}<span class="formValidation">{Q2hExtra5:validation}</span></div>
			<p class="formDescription">{Q2hExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2i">
			<p><strong>{Q2i:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2i:body}<span class="formValidation">{Q2i:validation}</span></div>
			<p class="formDescription">{Q2i:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2iextra1">
			<p><strong>{Q2iExtra1:caption}</strong></p>
			<div class="formBody">{Q2iExtra1:body}<span class="formValidation">{Q2iExtra1:validation}</span></div>
			<p class="formDescription">{Q2iExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2iextra2">
			<p><strong>{Q2iExtra2:caption}</strong></p>
			<div class="formBody">{Q2iExtra2:body}<span class="formValidation">{Q2iExtra2:validation}</span></div>
			<p class="formDescription">{Q2iExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2iextra3">
			<p><strong>{Q2iExtra3:caption}</strong></p>
			<div class="formBody">{Q2iExtra3:body}<span class="formValidation">{Q2iExtra3:validation}</span></div>
			<p class="formDescription">{Q2iExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2iextra4">
			<p><strong>{Q2iExtra4:caption}</strong></p>
			<div class="formBody">{Q2iExtra4:body}<span class="formValidation">{Q2iExtra4:validation}</span></div>
			<p class="formDescription">{Q2iExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2iextra5">
			<p><strong>{Q2iExtra5:caption}</strong></p>
			<div class="formBody">{Q2iExtra5:body}<span class="formValidation">{Q2iExtra5:validation}</span></div>
			<p class="formDescription">{Q2iExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2j">
			<p><strong>{Q2j:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2j:body}<span class="formValidation">{Q2j:validation}</span></div>
			<p class="formDescription">{Q2j:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2jextra1">
			<p><strong>{Q2jExtra1:caption}</strong></p>
			<div class="formBody">{Q2jExtra1:body}<span class="formValidation">{Q2jExtra1:validation}</span></div>
			<p class="formDescription">{Q2jExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2jextra2">
			<p><strong>{Q2jExtra2:caption}</strong></p>
			<div class="formBody">{Q2jExtra2:body}<span class="formValidation">{Q2jExtra2:validation}</span></div>
			<p class="formDescription">{Q2jExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2jextra3">
			<p><strong>{Q2jExtra3:caption}</strong></p>
			<div class="formBody">{Q2jExtra3:body}<span class="formValidation">{Q2jExtra3:validation}</span></div>
			<p class="formDescription">{Q2jExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2jextra4">
			<p><strong>{Q2jExtra4:caption}</strong></p>
			<div class="formBody">{Q2jExtra4:body}<span class="formValidation">{Q2jExtra4:validation}</span></div>
			<p class="formDescription">{Q2jExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2jextra5">
			<p><strong>{Q2jExtra5:caption}</strong></p>
			<div class="formBody">{Q2jExtra5:body}<span class="formValidation">{Q2jExtra5:validation}</span></div>
			<p class="formDescription">{Q2jExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2k">
			<p><strong>{Q2k:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2k:body}<span class="formValidation">{Q2k:validation}</span></div>
			<p class="formDescription">{Q2k:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2kextra1">
			<p><strong>{Q2kExtra1:caption}</strong></p>
			<div class="formBody">{Q2kExtra1:body}<span class="formValidation">{Q2kExtra1:validation}</span></div>
			<p class="formDescription">{Q2kExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2kextra2">
			<p><strong>{Q2kExtra2:caption}</strong></p>
			<div class="formBody">{Q2kExtra2:body}<span class="formValidation">{Q2kExtra2:validation}</span></div>
			<p class="formDescription">{Q2kExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2kextra3">
			<p><strong>{Q2kExtra3:caption}</strong></p>
			<div class="formBody">{Q2kExtra3:body}<span class="formValidation">{Q2kExtra3:validation}</span></div>
			<p class="formDescription">{Q2kExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2kextra4">
			<p><strong>{Q2kExtra4:caption}</strong></p>
			<div class="formBody">{Q2kExtra4:body}<span class="formValidation">{Q2kExtra4:validation}</span></div>
			<p class="formDescription">{Q2kExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2kextra5">
			<p><strong>{Q2kExtra5:caption}</strong></p>
			<div class="formBody">{Q2kExtra5:body}<span class="formValidation">{Q2kExtra5:validation}</span></div>
			<p class="formDescription">{Q2kExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2l">
			<p><strong>{Q2l:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2l:body}<span class="formValidation">{Q2l:validation}</span></div>
			<p class="formDescription">{Q2l:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2lextra1">
			<p><strong>{Q2lExtra1:caption}</strong></p>
			<div class="formBody">{Q2lExtra1:body}<span class="formValidation">{Q2lExtra1:validation}</span></div>
			<p class="formDescription">{Q2lExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2lextra2">
			<p><strong>{Q2lExtra2:caption}</strong></p>
			<div class="formBody">{Q2lExtra2:body}<span class="formValidation">{Q2lExtra2:validation}</span></div>
			<p class="formDescription">{Q2lExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2lextra3">
			<p><strong>{Q2lExtra3:caption}</strong></p>
			<div class="formBody">{Q2lExtra3:body}<span class="formValidation">{Q2lExtra3:validation}</span></div>
			<p class="formDescription">{Q2lExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2lextra4">
			<p><strong>{Q2lExtra4:caption}</strong></p>
			<div class="formBody">{Q2lExtra4:body}<span class="formValidation">{Q2lExtra4:validation}</span></div>
			<p class="formDescription">{Q2lExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2lextra5">
			<p><strong>{Q2lExtra5:caption}</strong></p>
			<div class="formBody">{Q2lExtra5:body}<span class="formValidation">{Q2lExtra5:validation}</span></div>
			<p class="formDescription">{Q2lExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2m">
			<p><strong>{Q2m:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2m:body}<span class="formValidation">{Q2m:validation}</span></div>
			<p class="formDescription">{Q2m:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2mextra1">
			<p><strong>{Q2mExtra1:caption}</strong></p>
			<div class="formBody">{Q2mExtra1:body}<span class="formValidation">{Q2mExtra1:validation}</span></div>
			<p class="formDescription">{Q2mExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2mextra2">
			<p><strong>{Q2mExtra2:caption}</strong></p>
			<div class="formBody">{Q2mExtra2:body}<span class="formValidation">{Q2mExtra2:validation}</span></div>
			<p class="formDescription">{Q2mExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2mextra3">
			<p><strong>{Q2mExtra3:caption}</strong></p>
			<div class="formBody">{Q2mExtra3:body}<span class="formValidation">{Q2mExtra3:validation}</span></div>
			<p class="formDescription">{Q2mExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2mextra4">
			<p><strong>{Q2mExtra4:caption}</strong></p>
			<div class="formBody">{Q2mExtra4:body}<span class="formValidation">{Q2mExtra4:validation}</span></div>
			<p class="formDescription">{Q2mExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2mextra5">
			<p><strong>{Q2mExtra5:caption}</strong></p>
			<div class="formBody">{Q2mExtra5:body}<span class="formValidation">{Q2mExtra5:validation}</span></div>
			<p class="formDescription">{Q2mExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2n">
			<p><strong>{Q2n:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2n:body}<span class="formValidation">{Q2n:validation}</span></div>
			<p class="formDescription">{Q2n:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2nextra1">
			<p><strong>{Q2nExtra1:caption}</strong></p>
			<div class="formBody">{Q2nExtra1:body}<span class="formValidation">{Q2nExtra1:validation}</span></div>
			<p class="formDescription">{Q2nExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2nextra2">
			<p><strong>{Q2nExtra2:caption}</strong></p>
			<div class="formBody">{Q2nExtra2:body}<span class="formValidation">{Q2nExtra2:validation}</span></div>
			<p class="formDescription">{Q2nExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2nextra3">
			<p><strong>{Q2nExtra3:caption}</strong></p>
			<div class="formBody">{Q2nExtra3:body}<span class="formValidation">{Q2nExtra3:validation}</span></div>
			<p class="formDescription">{Q2nExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2nextra4">
			<p><strong>{Q2nExtra4:caption}</strong></p>
			<div class="formBody">{Q2nExtra4:body}<span class="formValidation">{Q2nExtra4:validation}</span></div>
			<p class="formDescription">{Q2nExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2nextra5">
			<p><strong>{Q2nExtra5:caption}</strong></p>
			<div class="formBody">{Q2nExtra5:body}<span class="formValidation">{Q2nExtra5:validation}</span></div>
			<p class="formDescription">{Q2nExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q2o">
			<p><strong>{Q2o:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q2o:body}<span class="formValidation">{Q2o:validation}</span></div>
			<p class="formDescription">{Q2o:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2oextra1">
			<p><strong>{Q2oExtra1:caption}</strong></p>
			<div class="formBody">{Q2oExtra1:body}<span class="formValidation">{Q2oExtra1:validation}</span></div>
			<p class="formDescription">{Q2oExtra1:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2oextra2">
			<p><strong>{Q2oExtra2:caption}</strong></p>
			<div class="formBody">{Q2oExtra2:body}<span class="formValidation">{Q2oExtra2:validation}</span></div>
			<p class="formDescription">{Q2oExtra2:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2oextra3">
			<p><strong>{Q2oExtra3:caption}</strong></p>
			<div class="formBody">{Q2oExtra3:body}<span class="formValidation">{Q2oExtra3:validation}</span></div>
			<p class="formDescription">{Q2oExtra3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2oextra4">
			<p><strong>{Q2oExtra4:caption}</strong></p>
			<div class="formBody">{Q2oExtra4:body}<span class="formValidation">{Q2oExtra4:validation}</span></div>
			<p class="formDescription">{Q2oExtra4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q2oextra5">
			<p><strong>{Q2oExtra5:caption}</strong></p>
			<div class="formBody">{Q2oExtra5:body}<span class="formValidation">{Q2oExtra5:validation}</span></div>
			<p class="formDescription">{Q2oExtra5:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q3">
			<p><strong>{Q3:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q3:body}<span class="formValidation">{Q3:validation}</span></div>
			<p class="formDescription">{Q3:description}</p>
		</div>
		<div class="rsform-block rsform-block-q3extra1">
			<p><strong>{Q3Extra1:caption}</strong></p>
			<div class="formBody">{Q3Extra1:body}<span class="formValidation">{Q3Extra1:validation}</span></div>
			<p class="formDescription">{Q3Extra1:description}</p>
		</div>
	</div>

	<div class="white-box">
		<div class="rsform-block rsform-block-q4">
			<p><strong>{Q4:caption}</strong></p>
			<div class="formBody" style="max-width: 240px">{Q4:body}<span class="formValidation">{Q4:validation}</span></div>
			<p class="formDescription">{Q4:description}</p>
		</div>
		<div class="rsform-block rsform-block-q4extra1">
			<p><strong>{Q4Extra1:caption}</strong></p>
			<div class="formBody">{Q4Extra1:body}<span class="formValidation">{Q4Extra1:validation}</span></div>
			<p class="formDescription">{Q4Extra1:description}</p>
		</div>
	</div>

	<div class="pure-g white-box">
		<div class="pure-u-1">
			<p><strong>{Q5:body}</strong></p>
		</div>
		<div class="pure-u-1 pure-u-md-1-5">
			<div class="rsform-block rsform-block-q5name">
				<label class="cd-label">{Q5Name:caption}</label>
				{Q5Name:body}<span class="formValidation">{Q5Name:validation}</span>
				<p class="formDescription">{Q5Name:description}</p>
			</div>
		</div><!-- end of pure-u-1 pure-u-1-5 -->
		<div class="pure-u-1 pure-u-md-4-5 plr-15px">
			<div class="rsform-block rsform-block-q5details">
				<label class="cd-label">{Q5Details:caption}</label>
				{Q5Details:body}<span class="formValidation">{Q5Details:validation}</span>
				<p class="formDescription">{Q5Details:description}</p>
			</div>
		</div>
	</div><!-- end fo pure-g -->

	<div class="rsform-block rsform-block-tostep4">
		<p class="text-center margin-top">
			{ToStep4:body}
		</p>
	</div>
</fieldset>

<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->
<fieldset class="formHorizontal formContainer" id="rsform_{global:formid}_page_3">
	<div class="rsform-block rsform-block-step4header">
		{Step4Header:caption}
		{Step4Header:body}
	</div>
	<div class="pure-g">
		<div class="pure-u-1">
			<p><strong>{Q1a:caption}</strong></p>
		</div>
		<div class="pure-u-1 pure-u-md-1-5">
			<div class="rsform-block rsform-block-persoaccidentbenefit">
				<div class="formBody">{PersoAccidentBenefit:body}<span class="formValidation">{PersoAccidentBenefit:validation}</span></div>
				<p class="formDescription"></p>
			</div>
		</div><!-- end of pure-u-1 pure-u-1-5 -->
		<div class="pure-u-1 pure-u-md-4-5 plr-15px">
			<div class="rsform-block">
				<div class="formBody"><p>{PersoAccidentBenefit:description}</p></div>
			</div>
		</div>
	</div><!-- end fo pure-g -->

	<div class="rsform-block rsform-block-personalbenefitamount1864">
		<div style="max-width: 300px">
			<label class="cd-label">{PersonalBenefitAmount1864:caption}</label>
			{PersonalBenefitAmount1864:body}<span class="formValidation">{PersonalBenefitAmount1864:validation}</span>
			<p class="formDescription">{PersonalBenefitAmount1864:description}</p>
		</div>
	</div>
	<div class="rsform-block rsform-block-personalbenefitamountunder18">
		<div style="max-width: 300px">
			<label class="cd-label">{PersonalBenefitAmountUnder18:caption}</label>
			{PersonalBenefitAmountUnder18:body}<span class="formValidation">{PersonalBenefitAmountUnder18:validation}</span>
			<p class="formDescription">{PersonalBenefitAmountUnder18:description}</p>
		</div>
	</div>
	<div class="rsform-block rsform-block-personalbenefitamount6574">
		<div style="max-width: 300px">
			<label class="cd-label">{PersonalBenefitAmount6574:caption}</label>
			{PersonalBenefitAmount6574:body}<span class="formValidation">{PersonalBenefitAmount6574:validation}</span>
			<p class="formDescription">{PersonalBenefitAmount6574:description}</p>
		</div>
	</div>


	<div class="rsform-block rsform-block-tostep5">
		<p class="text-center margin-top">
			{ToStep5:body}
		</p>
	</div>
</fieldset>

<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->
<fieldset class="formHorizontal formContainer" id="rsform_{global:formid}_page_4">
	<div class="rsform-block rsform-block-step5header">
		{Step5Header:caption}
		{Step5Header:body}
	</div>
	<div class="rsform-block rsform-block-availableinsuranceplan">
		<div>
			<label class="cd-label">{AvailableInsurancePlan:caption}</label>
			{AvailableInsurancePlan:body}<span class="formValidation">{AvailableInsurancePlan:validation}</span>
			<p class="formDescription">{AvailableInsurancePlan:description}</p>
		</div>
	</div>

	<div class="rsform-block rsform-block-insuranceplannote">
		<p><strong>{InsurancePlanNote:body}</strong></p>
	</div>
	
	<div class="rsform-block rsform-block-standardplandiscount" style="padding:20px;background:#fff">
		<p><strong>{StandardPlanDiscount:caption}</strong></p>
		<div class="formBody">{StandardPlanDiscount:body}<span class="formValidation">{StandardPlanDiscount:validation}</span></div>
		<p class="formDescription">{StandardPlanDiscount:description}</p>
	</div>
	<!-- Standard plan -->
	<div class="rsform-block rsform-block-standardplanupgrade" style="padding:20px;background:#fff">
		<p><strong>{StandardPlanUpgrade:caption}</strong></p>
		<div class="formBody">{StandardPlanUpgrade:body}<span class="formValidation">{StandardPlanUpgrade:validation}</span></div>
		<p class="formDescription">{StandardPlanUpgrade:description}</p>
	</div>
	<div class="rsform-block rsform-block-standardplanbenefits" style="padding:20px;background:#fff">
		<p><strong>{StandardPlanBenefits:caption}</strong></p>
		<div class="formBody">{StandardPlanBenefits:body}<span class="formValidation">{StandardPlanBenefits:validation}</span></div>
		<p class="formDescription">{StandardPlanBenefits:description}</p>
	</div>

	<!-- Comprehensive plan -->
	<div class="rsform-block rsform-block-compplandiscount" style="padding:20px;background:#fff">
		<p><strong>{CompPlanDiscount:caption}</strong></p>
		<div class="formBody">{CompPlanDiscount:body}<span class="formValidation">{CompPlanDiscount:validation}</span></div>
		<p class="formDescription">{CompPlanDiscount:description}</p>
	</div>

	<!-- Major Medical Plan -->
	<div class="rsform-block rsform-block-majorplandiscount" style="padding:20px;background:#fff">
		<p><strong>{MajorPlanDiscount:caption}</strong></p>
		<div class="formBody">{MajorPlanDiscount:body}<span class="formValidation">{MajorPlanDiscount:validation}</span></div>
		<p class="formDescription">{MajorPlanDiscount:description}</p>
	</div>
	<div class="rsform-block rsform-block-majorplanupgrade" style="padding:20px;background:#fff">
		<p><strong>{MajorPlanUpgrade:caption}</strong></p>
		<div class="formBody">{MajorPlanUpgrade:body}<span class="formValidation">{MajorPlanUpgrade:validation}</span></div>
		<p class="formDescription">{MajorPlanUpgrade:description}</p>
	</div>
	<div class="rsform-block rsform-block-majorplanbenefits" style="padding:20px;background:#fff">
		<p><strong>{MajorPlanBenefits:caption}</strong></p>
		<div class="formBody">{MajorPlanBenefits:body}<span class="formValidation">{MajorPlanBenefits:validation}</span></div>
		<p class="formDescription">{MajorPlanBenefits:description}</p>
	</div>

	<!-- Standard Senior Plan -->
	<div class="rsform-block rsform-block-standardseniorplandiscount" style="padding:20px;background:#fff">
		<p><strong>{StandardSeniorPlanDiscount:caption}</strong></p>
		<div class="formBody">{StandardSeniorPlanDiscount:body}<span class="formValidation">{StandardSeniorPlanDiscount:validation}</span></div>
		<p class="formDescription">{StandardSeniorPlanDiscount:description}</p>
	</div>
	<div class="rsform-block rsform-block-standardseniorplanupgrade" style="padding:20px;background:#fff">
		<p><strong>{StandardSeniorPlanUpgrade:caption}</strong></p>
		<div class="formBody">{StandardSeniorPlanUpgrade:body}<span class="formValidation">{StandardSeniorPlanUpgrade:validation}</span></div>
		<p class="formDescription">{StandardSeniorPlanUpgrade:description}</p>
	</div>
	<div class="rsform-block rsform-block-standardseniorplanbenefits" style="padding:20px;background:#fff">
		<p><strong>{StandardSeniorPlanBenefits:caption}</strong></p>
		<div class="formBody">{StandardSeniorPlanBenefits:body}<span class="formValidation">{StandardSeniorPlanBenefits:validation}</span></div>
		<p class="formDescription">{StandardSeniorPlanBenefits:description}</p>
	</div>

	<!-- Comprehensive Senior Plan -->
	<div class="rsform-block rsform-block-compseniorplandiscount" style="padding:20px;background:#fff">
		<p><strong>{CompSeniorPlanDiscount:caption}</strong></p>
		<div class="formBody">{CompSeniorPlanDiscount:body}<span class="formValidation">{CompSeniorPlanDiscount:validation}</span></div>
		<p class="formDescription">{CompSeniorPlanDiscount:description}</p>
	</div>


	<div class="rsform-block rsform-block-tostep6">
		<p class="text-center margin-top">
			{ToStep6:body}
		</p>
	</div>
</fieldset>

<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->
<fieldset class="formHorizontal formContainer" id="rsform_{global:formid}_page_5">
	<div class="rsform-block rsform-block-step6header" style="margin-bottom: 0">
		{Step6Header:caption}
		{Step6Header:body}
	</div>

	<div class="rsform-block rsform-block-trueinfoconfirmation" style="margin-top: 0">
		<div class="formBody">{TrueInfoConfirmation:body}<span class="formValidation">{TrueInfoConfirmation:validation}</span></div>
		<p class="formDescription">{TrueInfoConfirmation:description}</p>
	</div>
	<div class="rsform-block rsform-block-medicalrelease" style="margin-bottom: 0">
		<p>{MedicalRelease:body}</p>
	</div>
	<div class="rsform-block rsform-block-medicalreleaseconfirmation" style="margin-top: 0">
		<div class="formBody">{MedicalReleaseConfirmation:body}<span class="formValidation">{MedicalReleaseConfirmation:validation}</span></div>
		<p class="formDescription">{MedicalReleaseConfirmation:description}</p>
	</div>

	<div class="rsform-block rsform-block-confirmationapplicantname">
		<p><strong>{ConfirmationApplicantName:caption}</strong></p>
		<div class="formBody">{ConfirmationApplicantName:body}<span class="formValidation">{ConfirmationApplicantName:validation}</span></div>
		<p class="formDescription">{ConfirmationApplicantName:description}</p>
	</div>

	<div class="rsform-block rsform-block-applicationdate">
		<p><strong>{ApplicationDate:caption}</strong></p>
		<div class="formBody" style="max-width: 240px">{ApplicationDate:body}<span class="formValidation">{ApplicationDate:validation}</span></div>
		<p class="formDescription">{ApplicationDate:description}</p>
	</div>

	<div class="rsform-block sform-block-captcha">
		<p class="text-center margin-top">
			{Captcha:body} Google Captcha will display once on the final domain
		</p>
	</div>
	
	<div class="rsform-block sform-block-submitbutton">
		<p class="text-center margin-top">
			{SubmitButton:body}
		</p>
	</div>
</fieldset>