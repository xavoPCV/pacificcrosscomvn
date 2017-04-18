<?php
/**
 * @version     1.0.0
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */


// No direct access
defined('_JEXEC') or die;

class GnosisController extends JControllerLegacy
{
    /**
     * Method to display a view.
     *
     * @param    boolean $cachable    If true, the view output will be cached
     * @param    array $urlparams    An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return    JController        This object to support chaining.
     * @since    1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT . '/helpers/gnosis.php';

        $view = JFactory::getApplication()->input->getCmd('view', 'words');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }
	
	
	public function updates(){
	
	


$html= '

<p>“<span style="font-weight: bold;">Act of Terrorism</span>” means an act whether involving violence or the use of force or not, and/or the threat or the preparation thereof, of any person or group(s) of persons, whether acting alone or on behalf of or in conjunction with any organization(s) or government(s), committed for political, religious, ideological or ethnic purposes or reasons, including the intention to influence any government and/or to put the public or any section of the public in fear.</p>
<p>“<span style="font-weight: bold;">Anaesthetist</span>” means a physician who specializes in anaesthesiology and is registered to practice&nbsp; anaesthesiology under the relevant laws and regulations of the country in which he practices.</p>
<p>“<span style="font-weight: bold;">Application</span>” means the application form signed by the Policyholder and, if the Insured Person is different from the Policyholder, by each Insured Person whereby the Policyholder applied for each Insured Person to be covered under this Policy and which application form forms part of this Policy.&nbsp;</p>
<p>“<span style="font-weight: bold;">ASEAN countries</span>” consists of Brunei, Cambodia, Indonesia, Laos, Malaysia, Myanmar, Philippines, Singapore, Thailand and Vietnam.</p>
<p>“<span style="font-weight: bold;">Attending Physician</span>” means the Physician responsible for the medical treatment of an Illness of an Insured Person. The Attending Physician may not be the Insured Person, a Family Member, or a Travelling Companion.</p>
<p>“<span style="font-weight: bold;">Benefits Schedule</span>” means the benefits schedule attached to this Policy.</p>
<p>“<span style="font-weight: bold;">Bonesetter</span>” means a person who specializes in bone-setting and is licensed or registered to practice bone-setting under the relevant laws and regulations of the country in which he practices.</p>
<p>“<span style="font-weight: bold;">Carrier</span>” means a transportation company or transport undertaking whose principal business is the carriage by air, sea or land of passengers and/or cargo for hire or reward and which is duly licensed or certified in all jurisdictions in which such business is carried on for such purposes.</p>
<p>“<span style="font-weight: bold;">Child</span>” means a person who is a minor under the laws of his Country of Residence or a full-time student of not more than 23 years of age.</p>
<p>“<span style="font-weight: bold;">Chinese Medicine Practitioner</span>” means a person licensed or registered to practice Chinese medicine under the relevant laws and regulations of the country in which he practices.</p>
<p>“<span style="font-weight: bold;">Close Business Partner</span>” means a business associate that has a share in the Insured Person’s business.</p>
<p>“<span style="font-weight: bold;">Co-insurance</span>” is the percentage of the policy benefits paid or payable by the insured or by another insurance company.</p>
<p>“<span style="font-weight: bold;">Company</span>” means Hung Vuong Assurance Corporation.</p>
<p>“<span style="font-weight: bold;">Congenital Condition</span>” means a physical or medical abnormality existing at the time of birth as well as neonatal physical/mental abnormalities developing thereafter because of factors inherent at the time of birth.</p>
<p>“<span style="font-weight: bold;">Cosmetic Surgery</span>” means re-constructive surgery or surgery which is not medically necessary or which is performed principally to improve or with the principal objective of improving the appearance of a person or which the person concerned considers or believes will improve his appearance and includes any surgery necessary for psychological reasons, adaptation and personal satisfaction in respect of a Disability covered under this Policy.</p>
<p>“<span style="font-weight: bold;">Country of Residence</span>” means the Country where the applicant has an established residence and spends a minimum of 183 days in one calendar year.</p>
<p>“<span style="font-weight: bold;">Deductible</span>” means an amount as stipulated in the Benefits Schedule to be deducted from the benefits payable in respect of any Eligible Expenses in each Policy Year.</p>
<p>“<span style="font-weight: bold;">Dentist</span>” means a person qualified by degree and licensed or registered to practice dentistry under the relevant laws and regulations of the country in which he practices.</p>
<p>“<span style="font-weight: bold;">Disability</span>” means an Illness or Injury, and any symptoms, sequelae or complications thereof, and in the case of Injury includes all Injuries arising from the same event or series of contiguous events.</p>
<p>“<span style="font-weight: bold;">Eligible Expenses</span>” means medical expenses for treatments and services, which are medically necessary for the treatment of a Disability.</p>
<p>“<span style="font-weight: bold;">Eligible Person</span>” means (a) a person who is not a Child; or (b) a Child who has a parent who is or will become an Insured Person.</p>
<p>“<span style="font-weight: bold;">Emergency</span>” means a bona fide situation where there is a sudden change in an Insured Person’s state of health, which requires urgent medical or surgical intervention to avoid imminent danger to his life or health.</p>
<p>“<span style="font-weight: bold;">Follow-up Care</span>” means the treatment ordered by the Attending Physician of the hospitalization, including follow-up consultation, relevant medicines, diagnostic tests and physiotherapy.&nbsp; Cover is restricted to follow-up treatment of the specific medical condition for which the Insured Person received inpatient treatment covered by the Policy.&nbsp; Follow-up treatment does not include regular outpatient consultation and long-term medication for chronic medical condition.</p>
<p>“<span style="font-weight: bold;">Grace Period</span>” means the period of thirty (30) days commencing from the date on which the relevant payment is due.</p>
<p>“<span style="font-weight: bold;">Herb</span>” means a plant whose leaves are used as a medicine for and are required for the treatment of an Illness or Injury covered under this Policy and which is prescribed by a Herbalist or Chinese Medicine Practitioner for the treatment of such Illness or Injury.</p>
<p>“<span style="font-weight: bold;">Herbalist</span>” means a person who grows or sells Herbs and who is licensed, registered or authorized under the relevant laws and regulations in the country in which he carries on such activities to grow or sell such Herbs.</p>
<p>“<span style="font-weight: bold;">Home Country / Country of Origin</span>” is the country of which the Insured Person holds a passport and which has been declared on the Application.</p>
<p>“<span style="font-weight: bold;">Home Nursing</span>” means nursing care immediately after hospitalization provided by a licensed nurse at home, and must be certified by the Attending Physician to be medically necessary.</p>
<p>“<span style="font-weight: bold;">Hospice care</span>” means palliative care (symptom management, relief of suffering and end-of-life care) given to individuals who are terminally ill with an expected survival of six months or less.&nbsp; The focus of hospice care is on pain and symptom management, on meeting the physical, emotional, and spiritual needs of the dying individual, while fostering the highest quality of life possible.</p>
<p>“<span style="font-weight: bold;">Hospital</span>” means an institution which is legally licensed as a medical or surgical Hospital in the country in which it is situated and whose main functions are not those of a spa, hydro-clinic, sanitarium, nursing home, home for the aged, rehabilitation centre, a place for alcoholics or drug addicts. The Hospital must be under the constant supervision of a resident Physician.</p>

<p>“<span style="font-weight: bold;">Immediate Family Member</span>” means an Insured Person’s legal spouse, children (natural or adopted), siblings, siblings in law, parents, parents in law, grandparents, grandchildren, legal guardian, stepparents or stepchildren.</p>
<p>“<span style="font-weight: bold;">Injury</span>” means a bodily injury (which for the avoidance of doubt excludes psychiatric conditions) arising wholly and exclusively from an Accident which independently of all other causes directly results in loss covered by this Policy.</p>
<p>“<span style="font-weight: bold;">Inpatient</span>” means an Insured Person who suffers a Disability and who is admitted to Hospital for the treatment of that Disability and occupies a Hospital bed in connection therewith for a continuous period of not less than 18 hours.</p>
<p>“<span style="font-weight: bold;">Insured Person</span>” means anyone of the persons named in the Policy Schedule as an Insured Person.</p>
<p>“<span style="font-weight: bold;">Medical appliances</span>” means supplies prescribed by a doctor for medical use such as gloves, dressing, masks, nebulizer kits, syringes, cotton, and plaster as well as other consumable used for mechanical devices.</p>
<p>“<span style="font-weight: bold;">Medicines and Drugs</span>” are any medicines or drugs (other than those which are experimental or unproven) prescribed by a Physician which are specifically required for the treatment of a Disability.</p>
<p>“<span style="font-weight: bold;">Miscellaneous Charges</span>” means expenses required for laboratory tests, x-rays, professional fees, medicines and drugs, blood and plasma, wheelchair rentals, outpatient surgery, surgical appliances and devices, and intra-operative standard prosthetic devices.</p>
<p>“<span style="font-weight: bold;">Normal, Usual and Customary Charges</span>” means a reasonable charge, which is (a) usual and customary when compared with the charges made for similar services and supplies in the locality of the Insured Person; and (b) made to persons having similar medical conditions in the locality of the Insured Person.</p>
<p>“<span style="font-weight: bold;">North America</span>” means Canada, United States of America, Mexico and the Caribbean Islands.</p>
<p>“<span style="font-weight: bold;">Optional Benefit</span>” means a benefit included in this policy document which is included at the option of the Policyholder with the agreement of the Company and in respect of which the relevant rider is attached to this policy document and which is described as an affirmed benefit and for which additional premiums are payable to the Company.</p>
<p>“<span style="font-weight: bold;">Period of Insurance</span>” means the period stated on the Policy Schedule to be the period of insurance or subsequently any renewal thereof during which the policy is in effect.</p>
<p>“<span style="font-weight: bold;">Permanent Total Disablement</span>” means disablement which entirely prevents the Insured Person from attending to his business or occupation of any and every kind and which lasts 52 consecutive weeks and at the expiry of that period is beyond hope of improvement.</p>

<p>“<span style="font-weight: bold;">Physician</span>” means a person qualified by degree and licensed or registered to practice medicine under the relevant laws and regulations of the country in which he practices.</p>
<p>“<span style="font-weight: bold;">Policy</span>” means this policy document and includes the Application, the Policy Schedule, the Benefits Schedule and the Surgical Schedule and any Optional Benefits included in this policy document with the relevant rider attached to this policy document and any endorsements, amendments or riders thereto which have been approved by an executive officer of the Company.</p>
<p>“<span style="font-weight: bold;">Policy Effective Date</span>” means 12:00 midnight (local time in the Country of Residence) on the first day of the Period of Insurance.</p>
<p>“<span style="font-weight: bold;">Policyholder</span>” means the person named in the Policy Schedule as the Policyholder and to whom this Policy has been issued in respect of insurance of the Insured Persons.</p>
<p>“<span style="font-weight: bold;">Policy Schedule</span>” means the policy schedule attached to this Policy.</p>
<p>“<span style="font-weight: bold;">Policy Year</span>” means a calendar year commencing on the Policy Effective Date or any anniversary of that date.</p>

<p>“<span style="font-weight: bold;">Professional Fees</span>” means the fees payable to licensed medical professionals such as occupational therapist, physiotherapist, acupuncturist, dietician, Attending Physician (except Surgeon), a pathologist and radiologist.</p>
<p>“<span style="font-weight: bold;">Public Conveyance</span>” means all public common carrier such as multi-engined aircrafts, buses, trains, ships, hovercrafts, ferries, and taxis that are licensed to carry fare paying passengers and coach being arranged by travel agency and is not a contractor or private carrier.</p>
<p>“<span style="font-weight: bold;">Public Place</span>” means any publicly accessible location (such as car park, street, park, environmental area, bus station, airport, sports stadium or shopping centre) including public transportation of any kind or any other similar place or location.</p>
<p>“<span style="font-weight: bold;">Rehabilitation</span>” means treatment in the form of a combination of therapies such as physical, occupational and speech therapy aimed at restoring full function after an acute event which required inpatient treatment.</p>
<p>“<span style="font-weight: bold;">Renewal Date</span>” means the date specified in the Policy Schedule to be the renewal date.</p>

<p>“<span style="font-weight: bold;">Special Diseases</span>” means hemorrhoids, varicose veins, sinusitis, diabetes, all types of hepatitis.&nbsp;</p>
<p>“<span style="font-weight: bold;">Specialist</span>” means a Physician who specializes in one particular area of medicine.</p>
<p>“<span style="font-weight: bold;">Surgeon</span>” means a person qualified by degree and licensed or registered to practice surgery under the relevant laws and regulations of the country in which he practices surgery.</p>
<p>“<span style="font-weight: bold;">Surgeon’s Fee</span>” means fee payable to a Surgeon or Surgeons for providing surgery to treat a Disability. The Surgeon’s Fee includes pre-surgical assessment, hospital visits and normal post surgical care and is subject to Normal, Usual and Customary Charges.</p>
<p>“<span style="font-weight: bold;">VND</span>” means the lawful currency of the Socialist Republic of Vietnam.<br><br></p>
';



$arrhtml = str_replace('</p>', '', $html);
$arrhtml = explode('<p>', $arrhtml);
$k = 6;
 foreach ($arrhtml  as $r) {
     echo $r .'<br>';
     if ($r != '' ) {
         $tagname = "span";
         $string = "$r";
    $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
    preg_match($pattern, $string, $matches);
   
    
         $ar = explode('”', $r);
         $k++;
          $db = JFactory::getDbo();
         $sql = "INSERT INTO `bluecross`.`gd68j_gnosis` (`id`, `asset_id`, `ordering`, `state`, `word`, `pronounciation`, `category`, `definition`, `examples`, `etymology`, `quiz`, `created_by`, `creation_date`, `modified_date`, `tags`, `source`) VALUES (NULL, '', '$k', '1', '".$matches[1]."', ' ', '1', '".$ar['1']."', '', '', '', '53', NOW(), NOW() , '', '');";
       //  echo $sql;
         $db->setQuery($sql);
         $db->query();
     }
 }


	
}
	 
}

?>