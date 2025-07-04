<?php

namespace App\Func;


use Illuminate\Support\Str;
use App\Func\Func;
use App\Models\Setting;
use PDF;

// require base_path("vendor/tecnickcom/tcpdf/tcpdf_autoconfig.php");



class MyPdf
{

    public $mail = null;

    public function __construct() {}


    public function certificatexxxx($quote)
    {

        // Create a new TCPDF object
        // $pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new PDF('P', 'mm', array(216, 279), true, 'UTF-8', false);

        $created_at = date("d-m-Y");

        $pdf::setHeaderCallback(function ($pdf) {});

        $pdf::setFooterCallback(function ($pdf) {});


        $settingP = Setting::where("param", 'pags[doc_certificate]')->first();
        if ($settingP != null) {
            $pdf_name = $settingP->value;
        } else {
            $pdf_name = "Certificate";
        }


        $settingP = Setting::where("param", 'page[cert_pdf]')->first();
        if ($settingP != null) {
            $pageContent = $settingP->value;
        } else {
            $pageContent = "No content ";
        }


        // set document information
        $pdf::SetCreator('TCPDF');
        $pdf::SetAuthor(config('app.name'));
        $pdf::SetTitle($pdf_name . ' - ' . $quote->policy_number);
        $pdf::SetSubject($pdf_name . ' - ' . $quote->policy_number);

        // set margins to 1 inch (72 points) on all sides
        // $pdf::SetMargins(10, 10);
        // Set margins
        $margin = 0.75 * 25.4;  //inch to mm
        $margin2 = 0.75 * 40.4;  //inch to mm
        $pdf::SetMargins($margin2, $margin, $margin2);
        // Set auto page breaks
        // $pdf->SetAutoPageBreak(TRUE, 0.75);

        // add a page
        // $pdf::AddPage();
        $pdf::AddPage('P', array(215.9, 279.4));

        $html = Func::replaceContent($quote, $pageContent);


        //die($html);
        // output the HTML content
        $pdf::writeHTML($html, true, false, true, false, '');

        $filename = Str::slug($pdf_name . "-" . $quote->policy_number);

        $pdf::Output($filename . '.pdf', 'I');
        die();
    }




    public function certificate($quote)
    {

        // Create a new TCPDF object
        // $pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new PDF('P', 'mm', array(216, 279), true, 'UTF-8', false);

        
        $settingP = Setting::where("param", 'pags[doc_certificate]')->first();
        if ($settingP != null) {
            $pdf_name = $settingP->value;
        } else {
            $pdf_name = "Certificate";
        }


        $created_at = date("d-m-Y");
       
        $pdf::setHeaderCallback(function ($pdf) {


        });

        $pdf::setFooterCallback(function ($pdf) {

        });


        // set document information
        $pdf::SetCreator('TCPDF');
        $pdf::SetAuthor(config('app.name'));
        $pdf::SetTitle($pdf_name . ' - ' . $quote->policy_number);
        $pdf::SetSubject($pdf_name . ' - ' . $quote->policy_number);

        // set margins to 1 inch (72 points) on all sides
        // $pdf::SetMargins(10, 10);
        // Set margins
        $margin = 0.75 * 25.4;  //inch to mm
        $margin2 = 0.75 * 40.4;  //inch to mm
        $pdf::SetMargins($margin2, $margin, $margin2);
        // Set auto page breaks
        // $pdf->SetAutoPageBreak(TRUE, 0.75);

        // add a page
        // $pdf::AddPage();
        $pdf::AddPage('P', array(215.9, 279.4));



        // $image_path = ""base_path("storage/app/public/" . $cdata->logo);
        // $image_path = url("storage/" . $cdata->logo);





        $html = "
<style>

.first_header{ font-size: 15px; font-weight: bold; color: #275c58; line-height: 100%; }

.header{ font-size: 13px; font-weight: bold; color: #275c58; }

.normal{ font-size: 9px; font-weight: normal; color: #333;}

.normal_table{ padding: 2px 0px;}

.line{color: #CCC; background-color: #CCC;}

</style>";

$html .= '<table style="width:100%; padding:0px 20px 0px 0px ;">
            <tr>
            <td style="width: 50%"><table class="normal_table">
                <tr><td  class="first_header">Certificate of Motor Insurance</td></tr>
                <tr><td  class="normal">Here is your insurance certificate and Schedule.</td></tr>
                <tr><td  class="normal">Extensions are visible even after the expiration date</td></tr>

                <tr><td  class="header"><br><br>Holder</td></tr>
                <tr><td  class="normal"><b>Name: </b>  '.$quote->first_name.' '.$quote->middle_name.' '.$quote->last_name.'</td></tr>
                <tr><td  class="normal"><b>Date of Birth: </b>  '.date('d-m-Y', strtotime($quote->date_of_birth)).'</td></tr>
            </table>
            </td>


            <td style="width: 50%">
            <table class="normal_table">
                <tr><td  class="normal"><b>Order Number: </b>  '.$quote->policy_number.'</td></tr>
                <tr><td  class="normal"><b>Valid From: </b>  '.date('d-m-Y H:i', strtotime($quote->start_date.' '.$quote->start_time)).'</td></tr>
                <tr><td  class="normal"><b>Valid Until: </b>  '.date('d-m-Y H:i', strtotime($quote->end_date.' '.$quote->end_time)).'</td></tr>
               
                
               <tr><td> <br><br>
               <span>             </span><table class="normal_table">
                    <tr><td  class="header">Vehicle</td></tr>
                    <tr><td  class="normal"><b>Make: </b>  '.$quote->vehicle_make.'</td></tr>
                    <tr><td  class="normal"><b>Model: </b>  '.$quote->vehicle_model.'</td></tr>
                    <tr><td  class="normal"><b>Engine CC: </b>  '.$quote->engine_cc.'</td></tr>
                    <tr><td  class="normal"><b>Registration Number: </b>  '.$quote->reg_number.'</td></tr>
                </table></td></tr>
               </table> 
            </td>
            
            </tr>
        </table>


        <table class="normal_table">

         </table><br><hr class="line"><br><table class="normal_table">
        
        <tr><td  class="header">Coverage</td></tr> 
        <tr><td  class="normal">The insurance policy provides comprehensive coverage for social, domestic, and pleasure purposes, including commuting. Additionally, it includes Class 1 business use. </td></tr>
        
        </table><br><hr class="line"><br><table class="normal_table">
        
        <tr><td  class="header">Restrictions & Exclusions</td></tr> 
        <tr><td  class="normal"><b>- Does not </b> cover the carriage of passengers or goods for hire or reward.</td></tr>
        <tr><td  class="normal"><b>- Only </b> provides coverage for the policyholder to drive the vehicle.</td></tr>
        <tr><td  class="normal"><b>- Does not </b> provide coverage for the recovery of an impounded vehicle.</td></tr>
        <tr><td  class="normal"><b>- Please refer to your full policy </b> document to familiarize yourself with any specific restrictions and exclusions that may apply to your insurance coverage.</td></tr>
       

        </table><br><hr class="line"><br><table class="normal_table">
        
        <tr><td  class="header">Endorsements</td></tr> 
        <tr><td  class="normal"><b>- Accidental Damage Fire & Theft Excess (001) -</b></td></tr>
        <tr><td  class="normal">We will not be liable to cover the initial amount, as indicated below, for any claims or series of claims arising from a single event covered by the Accidental Damage Section and/or Fire and Theft Section of  your policy.</td></tr>       


        </table><br><hr class="line"><br><table class="normal_table">
        
        <tr><td  class="header">Excess</td></tr> 
        <tr><td  class="normal">The mandatory excess for accidental damage, fire, and theft is set at</td></tr>
        <tr><td  class="normal"><b>£250</b></td></tr>  
        

        
        </table><br><hr class="line"><br><table class="normal_table">
        
        <tr><td  class="header">Contact</td></tr> 
        <tr><td  class="normal">For any inquiries or if you need to contact '.config('app.name').' regarding your policy, please  email '.config('services.contact.address').'  We will respond to your message as promptly
        as possible.</td></tr>  
        
        
        
        </table><br><hr class="line"><br><table class="normal_table">
        
        <tr><td  class="header">Underwriter Declaration</td></tr> 
        <tr><td  class="normal">I confirm that the insurance mentioned in this Certificate complies with the applicable laws in Great Britain, Northern
        Ireland, the Isle of Man, the Island of Guernsey, the Island of Jersey, and the Island of Alderney. This certification is
        provided on behalf of the authorized insurers, Mulsanne Insurance Company Limited. Mulsanne Insurance Company
        Limited is licensed by the Financial Services Commission in Gibraltar to conduct insurance operations under the
        Financial Services (Insurance Companies) Act.</td></tr> 
        
        </table>
        
        '; 
        




        //die($html);
// output the HTML content
        

        $pdf::writeHTML($html, true, false, true, false, '');

        $filename = Str::slug($pdf_name . "-" . $quote->policy_number);

        $pdf::Output($filename . '.pdf', 'I');


        die();




    }










    public function statementFact($quote)
    {

        // Create a new TCPDF object
        // $pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new PDF('P', 'mm', array(216, 279), true, 'UTF-8', false);

        $created_at = date("d-m-Y");

        $pdf::setHeaderCallback(function ($pdf) {

            $image_path = public_path("/img/mulsanne.png");
            $pdf->image($image_path, 160, 7, 31.5, 17);
        });

        $pdf::setFooterCallback(function ($pdf) {

            // Set font
            $pdf->SetFont('helvetica', '', 6);

            // Position at 15 mm from bottom
            $pdf->SetY(-15);

            // Set footer content
            $pdf->Cell(0, 5, 'Mulsanne Insurance Company Limited, PO Box 1338, 1st Floor, Grand Ocean Plaza, Ocean Village, Gibraltar', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });


        // set document information
        $pdf::SetCreator('TCPDF');
        $pdf::SetAuthor(config('app.name'));
        $pdf::SetTitle('Statement of Fact - ' . $quote->policy_number);
        $pdf::SetSubject('Statement of fact - ' . $quote->policy_number);

        // set margins to 1 inch (72 points) on all sides
        // $pdf::SetMargins(10, 10);
        // Set margins
        // $margin = 0.75 * 25.4;  //inch to mm
        // $margin2 = 0.75 * 40.4;  //inch to mm
        $margin = 0.75 * 25.4;  //inch to mm
        $margin2 = 0.75 * 15.4;  //inch to mm
        $pdf::SetMargins($margin2, $margin, $margin2);
        // Set auto page breaks
        // $pdf->SetAutoPageBreak(TRUE, 0.75);

        // add a page
        // $pdf::AddPage();
        $pdf::AddPage('P', array(215.9, 279.4));

        // 8.5 × 11



        // $image_path = ""base_path("storage/app/public/" . $cdata->logo);
        // $image_path = url("storage/" . $cdata->logo);





        $html = "
<style>


.normal{ font-size: 9px; font-weight: normal; color: #333;}

.it{
    font-style: italic;
}
.bd{
    font-weight: bold;
}
.ud{
    font-weight: bold;
    text-decoration: underline;
}
.tb0{
    width:100%; 
    padding:1px 6px 1px 6px;
    border: 1px solid #222;
}
.tb0 .td1{
  width: 40%;
  font-size: 8.5pt;
}
.tb0 .td2{
    width: 60%;
    font-size: 8.5pt;
}

.tb1{
    width:100%; 
    padding:1px 0px 1px 0px;
}
.tb1 .td1{
  width: 5%;
  font-weight: bold;
  font-size: 10pt;
  text-align:center;
}
.tb1 .td2{
    width: 95%;
    font-weight: bold;
    font-size: 10pt;
}


.tb2{
    width:100%; 
    padding:1px 0px 1px 0px;
}
.tb2 .td1{
  width: 5%;
  font-size: 10pt;
}
.tb2 .td2{
    width: 2.7%;
    font-size: 10pt;
}
.tb2 .td3{
    width: 92.3%;
    font-size: 10pt;
}


.tb3{
    width:100%; 
    padding:0px 0px 0px 0px;
}
.tb3 .td1{
  width: 100%;
  font-size: 8.3pt;
}


</style>";

        $html .= '<table style="padding-bottom:6px;"><tr><td style="font-weight:bold; font-size:12pt;">STATEMENT OF FACT - Short Term Insurance</td></tr></table>
        
        <table class="tb0">

        <tr><td class="td1 em"> </td><td class="td2"></td></tr>       
        
       <tr><td class="td1 bd it">Your Agent</td><td class="td2"></td></tr>
       <tr><td class="td1">Agent</td><td class="td2">' . config('app.name') . '  Limited</td></tr>

       <tr><td class="td1 em"> </td><td class="td2"></td></tr>

       <tr><td class="td1 bd it">Your Details - Name Address</td><td class="td2"></td></tr>
       <tr><td class="td1">Surname</td><td class="td2">' . $quote->last_name . '</td></tr>
       <tr><td class="td1">Forename(s)</td><td class="td2">' . $quote->first_name . ' ' . $quote->middle_name . '</td></tr>
       <tr><td class="td1">Title</td><td class="td2">' . $quote->title . '</td></tr>
       <tr><td class="td1">Address</td><td class="td2">' . $quote->address . ', ' . $quote->postcode . '</td></tr>
       <tr><td class="td1">Telephone number</td><td class="td2">' . $quote->contact_number . '</td></tr>
       <tr><td class="td1">Email address</td><td class="td2">' . $quote->user->email . '</td></tr>

       <tr><td class="td1 em"> </td><td class="td2"></td></tr>

       <tr><td class="td1 bd it">Your Policy Cover</td><td class="td2"></td></tr>
       <tr><td class="td1">Effective Date</td><td class="td2">' . date("d-m-Y", strtotime($quote->start_date)) . ' ' . $quote->start_time . '</td></tr>
       <tr><td class="td1">Expire Date</td><td class="td2">' . date("d-m-Y", strtotime($quote->end_date)) . ' ' . $quote->start_time . '</td></tr>
       <tr><td class="td1">Policy Cover</td><td class="td2">COMPREHENSIVE</td></tr>
       <tr><td class="td1">Number of Drivers (including you)</td><td class="td2">1</td></tr>
       <tr><td class="td1">Class of Use</td><td class="td2">Use for social domestic and pleasure purposes and use in person by the Policyholder in connection with their business or profession EXCLUDING use for hire or reward, racing, pacemaking, speed testing, commercial travelling or use for any purpose in connection with the motor trade.</td></tr>


       <tr><td class="td1 em"> </td><td class="td2"></td></tr>

       <tr><td class="td1 bd it">Driver Details (including you)</td><td class="td2"></td></tr>
       <tr><td class="td1">Full Name</td><td class="td2">' . $quote->first_name . ' ' . $quote->middle_name . ' ' . $quote->last_name . '</td></tr>
       <tr><td class="td1">Sex</td><td class="td2">-</td></tr>
       <tr><td class="td1">Date of Birth</td><td class="td2">' . date("d/m/Y", strtotime($quote->date_of_birth)) . '</td></tr>
       <tr><td class="td1">Licence Type</td><td class="td2">' . $quote->licence_type . '</td></tr>
       <tr><td class="td1">Occupation</td><td class="td2">' . $quote->occupation . '</td></tr> 
       

       <tr><td class="td1 em"> </td><td class="td2"></td></tr>

       <tr><td class="td1 bd it">Vehicle Details</td><td class="td2"></td></tr>
       <tr><td class="td1">Make</td><td class="td2">' . $quote->vehicle_make . '</td></tr>
       <tr><td class="td1">Model</td><td class="td2">' . $quote->vehicle_model . '</td></tr>
       <tr><td class="td1">Registration number</td><td class="td2">' . $quote->reg_number . '</td></tr>
       <tr><td class="td1">Vehicle value</td><td class="td2">' . $quote->vehicle_type . '</td></tr> 
       

       <tr><td class="td1 em"> </td><td class="td2"></td></tr>

       <tr><td class="td1 bd it">Accident / Claim Details</td><td class="td2"></td></tr>
       <tr><td class="td1">Driver Name</td><td class="td2">' . $quote->first_name . ' ' . $quote->middle_name . ' ' . $quote->last_name . '</td></tr>
       <tr><td class="td1">Date of Claim/Incident</td><td class="td2">-</td></tr>
       <tr><td class="td1">Costs</td><td class="td2">-</td></tr>
       <tr><td class="td1">Fault or Non-Fault</td><td class="td2">-</td></tr>
       
       <tr><td colspan="2" class="td1 em" style="width:100%"> <hr style="height:1.5px"></td></tr>
       
       <tr><td class="td1">Driver Name</td><td class="td2">' . $quote->first_name . ' ' . $quote->middle_name . ' ' . $quote->last_name . '</td></tr>
       <tr><td class="td1">Date of Claim/Incident</td><td class="td2">-</td></tr>
       <tr><td class="td1">Costs</td><td class="td2">-</td></tr>
       <tr><td class="td1">Fault or Non-Fault</td><td class="td2">-</td></tr>


       <tr><td colspan="2" style="width:100%;" class="td1"><br><div style="width:100%; background-color:#000; color:#FFF; text-align:center;  font-size:9.2pt">IMPORTANT - You also must read the Mulsanne Insurance Proposer Declaration & Important Notes on Pages 2 & 3</div></td></tr>
       

        </table><br pagebreak="true">


       <div style="font-size:15pt; font-weight:bold">Mulsanne / PROPOSER DECLARATION</div>


       <table class="tb1" style="padding-top:10px;"><tr><td class="td1">1.</td><td class="td2">I declare that I:</td></tr></table>
       
       <table class="tb2"><tr><td class="td1"></td><td class="td2">a.</td><td class="td3">Have no more than 2 motoring convictions and/or 6 penalty points in the last 3 years, and have no prosecution or police enquiry pending, other than a No Insurance conviction resulting from the current seizure of the vehicle.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">b.</td><td class="td3">Have NOT been disqualified from driving in the last 5 years.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">c.</td><td class="td3">Have no criminal convictions.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">d.</td><td class="td3">Have no more than 1 fault claim within the last 3 years (a pending or non-recoverable claim is considered a fault claims).</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">e.</td><td class="td3">Have <span class="bd ud">NOT</span> had a policy of insurance voided or cancelled by an insurance company</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">f.</td><td class="td3">Am a permanent UK resident for at least 36 month</td></tr></table>



       <table class="tb1"><tr><td class="td1">2.</td><td class="td2">I declare that the vehicle:</td></tr></table>
       
       <table class="tb2"><tr><td class="td1"></td><td class="td2">a.</td><td class="td3">Will only be used for social, domestic and pleasure purposes.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">b.</td><td class="td3">Is owned by me and I can prove legal title to the vehicle.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">c.</td><td class="td3">Will NOT be used for commuting, business use, hire or reward, racing, pace-making, speed testing, commercial travelling or use for any purpose in relation to the motor trade.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">d.</td><td class="td3">Will not be used to carry hazardous goods or be driven at a hazardous location.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">e.</td><td class="td3">Has not been modified and has no more than 8 seats in total and is right-hand drive only.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">g.</td><td class="td3">Is registered in Great Britain, Northern Ireland or the Isle of Man.</td></tr></table>

       <table class="tb2"><tr><td class="td1"></td><td class="td2">f.</td><td class="td3">Will be in the UK at the start of the policy and will not be exported from the UK during the duration of the policy.</td></tr></table>




       <table class="tb1"><tr><td class="td1">3.</td><td class="td2">I am aware that this insurance cannot be used for any vehicle not owned by me including Hire or Loan Vehicles (i.e. Vehicle Rentals, Vehicle Salvage/Recovery Agents, Credit Hire Vehicles/Companies and Accident Management Companies).</td></tr></table>


       <table class="tb1"><tr><td class="td1">4.</td><td class="td2">I agree that in the event of a claim I will provide the V5 registration document, a current MOT certificate (where one is required by law to be issued) and a copy of my driving licenc</td></tr></table><br pagebreak="true">


       
       
       
       
       <div style="font-size:12pt; font-weight:bold">IMPORTANT NOTES</div>
       
       <table class="tb3" style="padding-top:5px;"><tr><td class="td1"><span class="bd ud">WARNING:</span> No cover attaches until a Cover Note or Certificate of Motor Insurance has been issued by the Insurer or by their authorised agent on their behalf. If is an offence to make a false statement or to withhold any material information to obtain the issue of a Certificate of Motor Insurance. If you are in doubt about facts considered material you should disclose them. The Insurer reserves the right to decline any proposal or apply special terms. The Insurer reserves the right to establish the milometer (odometer) reading.</td></tr></table>

       <table class="tb3"><tr><td class="td1"><span>Claims and Underwriting Exchange:</span> Insurers pass information to the Claims and Underwriting Register, run by Insurance Database Mulsannes (IDS Ltd). The aim is to help us check information provided and also to prevent fraudulent claims. When we deal with your request for insurance, we may search the register. When you tell us about an incident which may or may not give rise to a claim, we will pass information relating to it to the register. You should show this notice to anyone who has an interest in vehicle insured under the policy.</td></tr></table>


       <table class="tb3"><tr><td class="td1">Motor Insurance Anti-Fraud and Theft Register: Insurers pass information to the Motor Insurance Anti-Fraud and Theft Register, run by the Association of British Insurers (ABI). The aim is to help us check information provided and also to prevent fraudulent claims. When we deal with your request for insurance, we may search the register. Under the conditions of your policy, you must tell us about any incident (such as accident or theft) which may or may not give rise to a claim. When you tell us about an incident, we will pass information relating to it to the register.</td></tr></table>

       <table class="tb3"><tr><td class="td1">Motor Insurance Database - Continuous Insurance Enforcement Information relating to your policy will be added to the Motor Insurance Database (\'MID\') managed by the Motor Insurance Bureau (\'MIB\'). MID and the data stored on it may be used by certain statutory and/or authorised bodies including the Police, the DVLA, the DVLANI, the Insurance Fraud Bureau and other bodies permitted by law for purposes not limited to but including:</td></tr></table>

       <table class="tb3"><tr><td class="td1">I. Electronic (Licensing)</td></tr></table>

       <table class="tb3"><tr><td class="td1">II. Continuous Insurance Enforcement</td></tr></table>

       <table class="tb3"><tr><td class="td1">III. Law enforcement (prevention, detection, apprehension and or prosecution of offenders)</td></tr></table>

       <table class="tb3"><tr><td class="td1">IV. The provision of government Mulsannes and or other Mulsannes aimed at reducing the level and incidence of uninsured driving.</td></tr></table>

       <table class="tb3"><tr><td class="td1">If you are involved in a road traffic accident (either in the UK, EEA or certain other territories), insurers and or the MIB may search the MID to obtain relevant information. Persons (including his or her appointed representatives) pursuing a claim in respect of a road traffic accident (including citizens of other countries) may also obtain information which is held on the MID. It is vital that the MID holds your correct registration number. If it is incorrectly shown on MID you are at risk of having your vehicle seized by the Police. You can check that your correct registration number details are shown on the MID at www.askmid.com. You should show this notice to anyone insured to drive the vehicle covered under the Policy.</td></tr></table>

       
       
       <table class="tb3" style="padding-top:7px;"><tr><td class="td1"><span class="bd ">DECLARATION:</span></td></tr></table>

       <table class="tb3"><tr><td class="td1">By agreeing to the delaration during the quotation process, I declare that to the best of my knowledge and belief all statements and answers in the proposal are true and correct. I understand that it is my duty to take reasonable care not to make a misrepresentation of information which will influence and/or assessment of the proposal and that due to the short term nature of the policy, changes and additions cannot be made once the policy has been taken out.</td></tr></table>

       <table class="tb3"><tr><td class="td1">I agree that this Proposal and Declaration shall form the basis of the contract between me and the Insurer and that if any answer has been written by any other person, such person shall be deemed to be my agent for that purpose. A copy of this completed Statement of Fact will be provided as an attachment in the Confirmation Email sent following the purchase of the policy, or by post if requested during the quotation process. You should keep a record (including copies of letters) of all information supplied to us for the purpose of entering into this contract. A specimen policy is available on request.</td></tr></table>




       <table class="tb3" style="padding-top:7px;"><tr><td class="td1"><span class="bd">IN THE EVENT OF A COMPLAINT</span></td></tr></table>

       <table class="tb3"><tr><td class="td1">Mulsanne Insurance Company Ltd aim to provide a standard of service that will leave no cause for complaint. However if <span class="bd">you</span> are dissatisfied with the service <span class="bd">we</span> have provided please write to The Complaints Department, c/o The A&A Group Ltd or Hyperformance Ltd, Garrick House, 161 High Street, Hampton Hill, Middlesex, TW12 1NL quoting <span class="bd">your</span> policy number or claim number and give <span class="bd">us</span> full details of <span class="bd">your</span> complaint. The A&A Group and Hyperformance Ltd are authorised to issue a final response to your complaint but where appropriate the final response may be issued by <span class="bd">your</span>
       insurer, Mulsanne Insurance Company Limited.</td></tr></table>

       <table class="tb3"><tr><td class="td1">Should <span class="bd">you</span> remain dissatisfied having received a final response, <span class="bd">you</span> may be able to take <span class="bd">your</span> complaint to the Financial Ombudsman Service (FOS) if it is appropriate in the circumstances of <span class="bd">your</span> complaint. Their address is The Financial Ombudsman Service, South Quay Plaza, 183 Marsh Wall, London E14 9SR.</td></tr></table>




       <table class="tb3"><tr><td class="td1"><span>INSURER INFORMATION</span></td></tr></table>

       <table class="tb3"><tr><td class="td1">Mulsanne Insurance Company Limited is licensed by the Chief Executive of the Gibraltar Financial Services Commission under the Insurance Companies Act to carry on insurance business. Address: Mulsanne Insurance Company Limited, PO Box 1338, First Floor, Grand Ocean Plaza, Ocean Village, Gibraltar.</td></tr></table>

       <table class="tb3"><tr><td class="td1">The following companies act as administrators on behalf of Mulsanne Insurance Company Limited:</td></tr></table>

       <table class="tb3"><tr><td class="td1">The A&A Group. Registered in England and Wales: Company No: 03578103. Registered Address: Garrick House, 161 High Street, Hampton Hill, Middlesex, TW12 1NL. Authorised and regulated by the Financial Conduct Authority. FCA Register Number: 309611.</td></tr></table>

       <table class="tb3"><tr><td class="td1">Hyperformance Limited. Registered in England and Wales: Company No: 03758951. Registered Address: Garrick House, 161 High Street, Hampton Hill, Middlesex, TW12 1NL. Authorised and regulated by the Financial Conduct Authority. FCA Register Number: 307711.</td></tr></table>


       <table class="tb4"><tr><td class="td1">
       <div style="background-color:#000; color:#FFF; font-soze:10pt; text-align:center; font-weight:bold;">IMPORTANT<br>There is no need to sign this document, as by agreeing to the declaration during the quotation process you have confirmed that you have read and agree to the Mulsanne / Proposer\'s Declaration</div>
       </td></tr></table>


       










       




       









       

        ';





        //die($html);
        // output the HTML content
        $pdf::writeHTML($html, true, false, true, false, '');

        $filename = Str::slug("Statement-of-Fact-" . $quote->policy_number);

        $pdf::Output($filename . '.pdf', 'I');
        die();



        // if ($inline_display) {
        //     // output PDF to browser or file
        //     $pdf::Output('testProjectInvoice.pdf', 'I');
        //     die();
        // } else {
        //     return $pdf::Output('', 'S'); // Save the PDF content as a string (no filename)
        // }

    }











    public function newPolicySchedule($quote)
    {

        // Create a new TCPDF object
        // $pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new PDF('P', 'mm', array(216, 279), true, 'UTF-8', false);

        $created_at = date("d-m-Y");

        $pdf::setHeaderCallback(function ($pdf) {

            $image_path = public_path("/img/mulsanne.png");
            $pdf->image($image_path, 12, 14, 37, 20);
        });

        $pdf::setFooterCallback(function ($pdf) {

            // Set font
            $pdf->SetFont('helvetica', '', 6);

            // Position at 15 mm from bottom
            $pdf->SetY(-40);

            // Set footer content
            $pdf->Cell(0, 5, 'Mulsanne Insurance Company Limited, PO Box 1338, 1st Floor, Grand Ocean Plaza, Ocean Village, Gibraltar', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });


        // set document information
        $pdf::SetCreator('TCPDF');
        $pdf::SetAuthor(config('app.name'));
        $pdf::SetTitle('New Policy Schedule - ' . $quote->policy_number);
        $pdf::SetSubject('New Policy Schedule - ' . $quote->policy_number);

        // set margins to 1 inch (72 points) on all sides
        // $pdf::SetMargins(10, 10);
        // Set margins
        // $margin = 0.75 * 25.4;  //inch to mm
        // $margin2 = 0.75 * 40.4;  //inch to mm
        $margin = 0.75 * 50;  //inch to mm
        $margin2 = 0.75 * 15.4;  //inch to mm
        $pdf::SetMargins($margin2, $margin, $margin2);
        // Set auto page breaks
        // $pdf->SetAutoPageBreak(TRUE, 0.75);

        // add a page
        // $pdf::AddPage();
        $pdf::AddPage('P', array(210, 352));

        // 8.5 × 11



        // $image_path = ""base_path("storage/app/public/" . $cdata->logo);
        // $image_path = url("storage/" . $cdata->logo);




        $image_path = public_path("/img/logo2.png");

        $html = "
<style>


.normal{ font-size: 9px; font-weight: normal; color: #333;}

.it{
    font-style: italic;
}
.bd{
    font-weight: bold;
    color:#333;
}
.ud{
    text-decoration: underline;
}
.tb0{
    width:100%; 
    padding:5px 6px 5px 6px;
    border-left: 0.57px solid #222;
    border-top: 0.57px solid #222;
}
.tb0 .td1{
  width: 28%;
  border-right: 0.57px solid #222;
  border-bottom: 0.57px solid #222;
}
.tb0 .td2{
    width: 14%;
    border-right: 0.57px solid #222;
    border-bottom: 0.57px solid #222;
}
.tb0 .td3{
    width: 14%;
    border-right: 0.57px solid #222;
    border-bottom: 0.57px solid #222;
}
.tb0 .td4{
    width: 14%;
    border-right: 0.57px solid #222;
    border-bottom: 0.57px solid #222;
}
.tb0 .td5{
    width: 30%;
    border-right: 0.57px solid #222;
    border-bottom: 0.57px solid #222;
}
.tb0 .hd{
    text-align: center;
    font-weight:bold;
    font-size: 9px;
}


.tb1{
    width:100%; 
    padding:0px 0px 0px 0px;
    font-size: 8.5pt;
}
.tb1 td{
  font-size: 8.5pt;
}
.tb1 .tdl{
    font-size: 8.7pt;
}


</style>";

        $parts = explode(", ", $quote->address);
        $lastPart = array_pop($parts);
        $firstPart = implode(", ", $parts);

        $html .= '
        <table class="tb0">
        
        <tr>
            <td style="width:56%" class="td1 hd" colspan="3">SHORT TERM INSURANCE - MULSANNE</td>
            <td style="width:44%" class="td4 hd" colspan="2">NEW BUSINESS SCHEDULE</td>
        </tr>
        <tr>
            <td class="td1"><table class="tb1"><tr><td class="bd">Policy Number:</td></tr><tr><td>' . $quote->policy_number . '</td></tr></table></td>
            <td class="td2"><table class="tb1"><tr><td class="bd">Date Isssued:</td></tr><tr><td>' . date("d/m/Y", strtotime($quote->start_date)) . '</td></tr></table></td>
            <td class="td3" style="width:58%" colspan="3"><table class="tb1"><tr><td class="bd">Agent:</td></tr><tr><td>' . config('app.name') . ' Limited</td></tr></table></td>
        </tr>
        <tr>
            <td class="td1" colspan="2" style="width:42%"><table class="tb1"><tr><td class="bd">Insured:</td><td>' . $quote->first_name . ' ' . $quote->middle_name . ' ' . $quote->last_name . '</td></tr></table></td>
            <td class="td3" colspan="2" style="width:28%; border-right:none" ><table class="tb1"><tr><td class="bd">Effective Time/Date:</td></tr></table></td>
            <td class="td5"><table class="tb1"><tr><td>' . date("H:i d/m/Y", strtotime($quote->start_date . ' ' . $quote->start_time)) . '</td></tr></table></td>
        </tr>


        <tr>
            <td class="td1" rowspan="3" colspan="2" style="width:42%"><table class="tb1"><tr><td class="bd">' . $firstPart . '</td></tr><tr><td class="bd">' . $lastPart . '</td></tr><tr><td class="bd">' . $quote->postcode . '</td></tr></table></td>
            <td  class="td3" colspan="2" style="width:28%; border-right: none;"><table class="tb1"><tr><td class="bd">Expiry Time/Date:</td></tr></table></td>
            <td class="td5"><table class="tb1"><tr><td>' . date("H:i d/m/Y", strtotime($quote->end_date . ' ' . $quote->end_time)) . '</td></tr></table></td>
        </tr>
        <tr>
            <td  class="td3" colspan="2" style="width:28%; border-right: none;"><table class="tb1"><tr><td class="bd">Reason for Issue:</td></tr></table></td>
            <td class="td5"><table class="tb1"><tr><td>' . $quote->cover_reason . '</td></tr></table></td>
        </tr>
        <tr>
            <td  class="td3" colspan="2" style="width:28%; border-right: none;"><table class="tb1"><tr><td class="bd">Premium (inc. IPT):</td></tr></table></td>
            <td class="td5"><table class="tb1"><tr><td>£' . number_format($quote->update_price, 2) . '</td></tr></table></td>
        </tr>

        

        <tr>
            <td class="td1" colspan="3" style="width:56%"><table class="tb1"><tr><td style="width:40%" class="bd ud">Reason for Issue:</td> <td><span class="bd">Registration Number:</span> ' . $quote->reg_number . ' </td> </tr></table></td>
            <td class="td4" colspan="2" style="width:44%"><table class="tb1"><tr><td><span class="bd">Cover:</span> FULLY COMPREHENSIVE</td></tr></table></td>
        </tr>

        <tr>
            <td class="td1" colspan="5" style="width:100%"><table class="tb1"><tr><td style="width:20%" class="bd">Vehicle Value: </td> <td style="width:20%">' . $quote->vehicle_type . ' </td><td style="width:30%" class="bd">Make and Model of Vehicle: </td> <td style="width:30%">' . $quote->vehicle_name . '  ' . $quote->vehicle_model . ' </td> </tr></table></td>
        </tr>


        </table>





        <br><br><table class="tb0">
            <tr>
                <td style="width:100%" class="td1">
                <table class="tb1">
                    <tr><td class="bd tdl">ENDORSEMENTS APPLICABLE (Full wordings shown within ENDORSEMENTS)</td></tr><tr><td class="tdl">FCC - FULLY COMPREHENSIVE</td></tr>
                    <tr><td class="tdl"><span class="bd">IMP</span> - IMPOUNDED VEHICLE</td></tr>
                </table>
                </td>
            </tr>
        </table>


        
        <br><br><table class="tb0">
            <tr>
                <td style="width:100%" class="td1">
                <table class="tb1">
                    <tr><td class="bd tdl ud">ENDORSEMENTS - only apply if noted in the ENDORSEMENTS APPLICABLE above</td></tr>
                   
                    </table><table style="padding:0px 0px; font-size:5pt;"><tr><td></td></tr></table><table class="tb1">
                    
                    <tr><td class="bd tdl ud">FCC - FULLY COMPREHENSIVE COVER</td></tr>
                    <tr><td>This Short Term Insurance Policy is for Fully Comprehensive cover. There is comprehensive cover for any damage to your vehicle.</td></tr>

                    </table><table style="padding:0px 0px; font-size:5pt;"><tr><td></td></tr></table><table class="tb1">
                    
                    <tr><td class="bd tdl ud">IMP - IMPOUNDED VEHICLE</td></tr>
                    <tr><td>This policy of insurance can be used to release the insured vehicle which has been seized by the Police or Local Authority.</td></tr>
                    
                    </table><table style="padding:0px 0px; font-size:5pt;"><tr><td></td></tr></table><table class="tb1">
                    
                    <tr><td class="bd tdl ud">017 - USE IN THE REPUBLIC OF IRELAND</td></tr>
                    <tr><td>The Territorial Limits mentioned in your Policy are amended to allow your vehicle to be used in the Republic of Ireland with indemnity as if it were in the United Kingdom.</td></tr>

                    </table><table style="padding:0px 0px; font-size:5pt;"><tr><td></td></tr></table><table class="tb1">
                    
                    <tr><td class="bd tdl ud">065 - FOREIGN USE EXTENSION</td></tr>
                    <tr><td>We will insure you for the cover shown in your schedule while your motor vehicle is being used within:</td></tr>
                    <tr><td>-any country in the European Union (EU).</td></tr>
                    <tr><td>-Andorra, Iceland, Liechtenstein, Norway and Switzerland.</td></tr>
                    <tr><td>Full details of your Foreign Use terms and conditions are stated within the Foreign Use section of your Policy. This endorsement only applies if we have agreed and you have paid an additional premium.</td></tr>


                </table>
                </td>
            </tr>
        </table>
 

        <br><br><table class="tb0">
        <tr>
            <td style="width:100%" class="td1">
            <table class="tb1">
                <tr><td class="bd  ud">Important Information</td></tr>
                
                </table><table style="padding:0px 0px; font-size:5pt;"><tr><td></td></tr></table><table class="tb1">
                
                <tr><td><span class="bd tdl ud">CONTINUOUS INSURANCE ENFORCEMENT and the MOTOR INSURANCE DATABASE</span> nformation relating to your policy will be added to the Motor Insurance Database (\'MID\') managed by the Motor Insurance Bureau (\'MIB\'). MID and the data stored on it may be used by certain statutory and/or authorised bodies including the Police, the DVLA, the DVLANI, the Insurance Fraud Bureau and other bodies permitted by law for purposes not limited to but including:</td></tr>

                <tr><td><ul><li>Electronic Licensing</li>
                <li>Continuous Insurance Enforcement</li>
                <li>Law enforcement (prevention, detection, apprehension and or prosecution of offenders)</li>
                <li>The provision of government services and or other services aimed at reducing the level and incidence of uninsured driving.</li></ul></td></tr>

                </table><table style="padding:0px 0px; font-size:2pt;"><tr><td></td></tr></table><table class="tb1">

                <tr><td>If you are involved in a road traffic accident (either in the UK, EEA or certain other territories), insurers and or the MIB may search the MID to obtain relevant information.</td></tr>

                </table><table style="padding:0px 0px; font-size:5pt;"><tr><td></td></tr></table><table class="tb1">


                <tr><td class="tdl">Persons (including his or her appointed representatives) pursuing a claim in respect of a road traffic accident (including citizens of other countries) may also obtain information which is held on the MID. It is vital that the MID holds your correct registration number. If it is incorrectly shown on MID you are at risk of having your vehicle seized by the Police.</td></tr>

            </table>
            </td>
        </tr>
    </table>



    <br><br><table class="tb0">
    <tr>
        <td style="width:100%" class="td1">
        <table class="tb1">
            <tr><td class="bd  ud">Insurer Information</td></tr>
            
            </table><table style="padding:0px 0px; font-size:5pt;"><tr><td></td></tr></table><table class="tb1">
            
            <tr><td>This policy is underwritten by Mulsanne Insurance Company Limited who are licensed by the Gibraltar Financial Services Commission to carry on insurance business under the Financial Services (Insurance Companies) Act 1987, and authorised co-insurers. Complete Cover Group Limited / Hyperformance Limited act as administrators on behalf of the Insurer, and are authorised and regulated by the Financial Services Authority.</td></tr>

        </table>
        </td>
    </tr>
</table>
<table class="tb1" style="padding:10px"><tr><td style="text-align:center"><img style="height:35px" src="' . $image_path . '"></td></tr></table>





        ';







        //die($html);
        // output the HTML content
        $pdf::writeHTML($html, true, false, true, false, '');

        $filename = Str::slug("Statement-of-Fact-" . $quote->policy_number);

        $pdf::Output($filename . '.pdf', 'I');
        die();



        // if ($inline_display) {
        //     // output PDF to browser or file
        //     $pdf::Output('testProjectInvoice.pdf', 'I');
        //     die();
        // } else {
        //     return $pdf::Output('', 'S'); // Save the PDF content as a string (no filename)
        // }

    }


}
