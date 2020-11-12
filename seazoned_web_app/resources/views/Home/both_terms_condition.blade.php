@extends("layouts.dashboardlayout")
@section('content')
<style>
        body{
            font-family: 'Montserrat', sans-serif;
            color: #292b2c;
        }

        body ul li{
            padding-top:10px;
            }
        .heading{
            padding-top:20px;
        }
        h6{
            font-weight: 400;
            color: #565656;
        }
        .terms h2{
            
            text-decoration: underline;
            padding-top: 10px;
            font-weight: 400;
            color: #565656;
        }
        .purpose-text h4{
            text-decoration: underline;
            font-weight: 400;
            padding-top: 20px; 
        }
        .conditions .text{
        color: #565656;
            font-weight: 600;
            font-size: 16px;
        }
        .conditions h4{
            text-decoration: underline;
            font-weight: 400;
        }
        p{
        color: #565656;
            padding: 10px 0px;
            font-weight: 300; 
        }
    </style>
<script type="text/javascript">
    function submit_form()
    {
        document.getElementById('profile_form').submit();
    }
</script>
<div class='clearfix'></div>
<section class="faq-banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">                       
                <h2 class="m-0 sm-bold">TERMS AND CONDITIONS</h2>
                                    
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 text-center  m-auto">   
                {!!Form::open(array('routes' => 'terms-services-view', 'method' => 'post','id'=>'profile_form'))!!}
                <select class="form-control" name="profile_id" class="" onchange="submit_form();">
                        <option value="2" selected>Customer</option>
                        <option value="3" <?php echo(isset($profile_id) && $profile_id==3)?'selected':''; ?>>Provider</option>
                    </select>                    
                {!!Form::close()!!} 
            </div>
        </div>
    </div>
</section>

<div class='clearfix'></div>
@if($profile_id == 2)
<section class="conditions">
            <div class="container">
             
                <p class="the-terms">PLEASE READ THESE TERMS AND CONDITIONS OF USE (THE ”TERMS”) CAREFULLY. THEY MAY BE AMENDED FROM TIME TO TIME BY US WITHOUT NOTICE, AND AS MODIFIED THEY FORM A BINDING AGREEMENT BETWEEN YOU AND SEAZONED, LLC D/B/A/ SEAZONED (“WE” OR “US”) GOVERNING THE MANNER IN WHICH YOU ARE PERMITTED TO USE THE MOBILE APPLICATION SEAZONED (THE “SITE”) AND THE SERVICES IT OFFERS (THE “SERVICES” AS FURTHER DESCRIBED BELOW). THE PRIVACY POLICY, AVAILABLE HERE IS HEREBY INCORPORATED BY REFERENCE INTO THESE TERMS. THESE TERMS WILL BE UPDATED FROM TIME TO TIME WITHOUT NOTICE AND AVAILABLE HERE. FAILURE TO AGREE TO THE TERMS WILL RESULT IN YOUR BEING UNABLE TO USE THE SITE OR SERVICES. ANY BREACH OF THESE TERMS WILL RESULT IN YOUR BEING UNABLE TO USE THE SITE OR SERVICES AND MAY SUBJECT YOU TO ADDITIONAL CIVIL PENALTIES.</p>
        <h3>OWNERSHIP: THIRD PARTIES</h3>
                <p> You may utilize the site to obtain on-demand lawn care and home solutions (the “services”) only for real property for which you are the legal owner or legally permitted to contract to benefit. You represent, by utilizing this site or the services, that you either are such legal owner or possess such legal permission, and you acknowledge that we are relying on such representation in causing the services to be performed. We may, but are not required to, demand proof of same. You acknowledge and agree that the services are to be performed by third-party contractors (“contractors”). We have no control over the actions of the contractors. Their performance of the services, and your use of the services, is explicitly subject to limitations of liability set forth in these terms.</p>
    <h3>REGISTRATION: USE</h3>
                <p> In order to utilize the site and obtain services, you must to register with us using complete and accurate information for all fields requested, which you are responsible for maintaining as current. You will only be permitted to register on your own behalf and no other person or entity, unless you are an authorized representative of that entity for which authorization we may, but are not required, to demand proof. We may refuse your registration or cancel your account at any time, for any or no reason, in our sole discretion, resulting in your inability to utilize the site or the services. Cancellation of your registration may result in the forfeiture and destruction of all information associated with your account. If you wish to terminate your account, you may do so by notifying us and ceasing all use of the service. These terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability. You are solely responsible and liable for activity that occurs on your account, shall not permit any third person or party to access or use your account and shall be responsible for maintaining the confidentiality of your password. You are not permitted to use another user’s account. You will immediately notify us in writing of any unauthorized use of your account, or other account related security breach of which you are aware. You represent and warrant that if you are an individual, you are of legal age to form a binding contract, or that if you are registering on behalf of an entity, that you are authorized to enter into, and bind the entity to, these terms. We may, but are not required to, demand proof of same. You are solely responsible for ensuring that these terms are in compliance with all laws, rules and regulations applicable to you. As a condition of use, you promise not to use the site or service for any purpose that is not expressly permitted by these terms. You shall not take any action that attempts to (i) interfere or has the effect of interfering with the proper working of the site or services, (ii) bypass any security measures of the site or services, (iii) collect, use or scrape any content from the site, or (iv) modify, appropriate, reproduce, distribute, reverse engineer, mine, create derivative works or adaptations of, publicly display, sell, trade, or in any way exploit the service or site content, except as expressly authorized by the terms. By creating an account, you agree that the services may send you informational text (sms) messages as part of the normal business operation of your use of the services.</p>
        <h3>REPRESENTATIONS</h3>
                <p>Each time you choose to use the services, you will be deemed to accept these terms as they exist at such time, and to represent and warrant that, at the time of commencement of the services and throughout the performance thereof, the area to be serviced: (i) is in a condition suitable for receiving our services (the Services”); (ii) will have boundaries clearly marked; (iii) will be free of all debris, obstacles and obstructions and (iv) is situated on land for which you have a legal right to contract.</p>
  <h3>DISCLAIMER: LIMITATION OF LIABILITY</h3>
                <p>We make no representations or warranties as to the contractors, their experience, quality of services, insurance status, certification, license or legitimacy. We operate only as an intermediary between you and the contractors, placing you in contact with a contractor for which service(s) you request and collecting payment for remittance thereto less a fee for our services. Your interactions with the contractors and any other terms, conditions, warranties or representations associated with such dealings, are solely between you and the contractors. you should make whatever investigation you feel necessary or appropriate before proceeding with the services. We shall not be responsible or liable for any loss or damage of any sort incurred as the result of any such dealings, including your use of the services. In the event of damage to person or property, your recourse is against the contractors only for negligent performance or non-performance of the services. We are not obligated to, and we will not, take part in any dispute. Our maximum liability to you will be a refund of amounts paid by you to us for the services out of which any dispute or damages arose. In the event of any such damages or dispute, you hereby release us, our officers, employees, agents, affiliates, representatives and successors from claims, demands and damages (actual and consequential) of every kind or nature, known or unknown, suspected and unsuspected, direct or indirect, disclosed and undisclosed, arising out of or in any way related to such disputes and/or the services. You agree to take reasonable precautions and exercise the utmost personal care in all interactions with any individual or entity you come into contact with during your use of the services. Your use of the site and services is solely at your own risk. Our site and the services are provided “as is,” “as available” and are provided without any representations or warranties of any kind, express or implied, including, but not limited to, the implied warranties of title, non-infringement, merchantability and fitness for a particular purpose, and any warranties implied by any course of performance or usage of trade, all of which are expressly disclaimed, save to the extent required by law. we do not warrant that: a) the site or services will be available at any particular time or location; b) any defects or errors will be corrected; c) any content available at or through the site is free of viruses or other harmful components; or d) the results of using the services will meet your requirements as to quality or timeliness. All liability of us, our directors, employees, agents, representatives, partners, suppliers, content providers, officers and successors howsoever arising for any loss suffered as a result of your use of the site or services is expressly excluded to the fullest extent permitted by law, save that, if a court of competent jurisdiction determines that liability of us, our directors, employees, officers, agents, representatives, affiliates, partners, suppliers, content providers or successors (as applicable) (the “Seazoned entities”) has arisen, the total of such liability shall be limited in the aggregate to one hundred u.s. dollars ($100.00). To the maximum extent permitted by applicable law, in no event shall the Seazoned entities be liable under contract, tort, strict liability, negligence or any other legal or equitable theory or otherwise (and whether or not any of such persons or entities had prior knowledge of the circumstances giving rise to such loss or damage) with respect to the site or services for: indirect or consequential losses or damages; loss of actual or anticipated profits; loss of revenue; loss of goodwill; loss of data; wasted expenditure; or cost of procurement of substitute goods or services. In no event shall we be liable for any damages whatsoever, whether direct, indirect, general, special, compensatory, consequential, and/or incidental, arising out of or relating to the conduct of you or any contractors in connection with the use of the services, including without limitation, bodily or personal injury, damage to property and/or any other damages resulting from performance or non-performance of the services. you understand that we make no guarantee, either express or implied, regarding the services.</p>
  <h3>INDEMNIFICATION</h3>
                <p> You shall defend, indemnify, and hold harmless the Seazoned entities from all losses, costs, actions, claims, damages, expenses (including reasonable legal costs) or liabilities, that arise from or relate to your use or misuse of, or access to, the site or services, violation of these terms, breach of representations herein or third-party using your account. We reserve the right to assume the exclusive defense and control of any matter otherwise subject to indemnification by you, in which event you will assist and cooperate with us in asserting any available defenses.</p>
  <h3>PAYMENT</h3>
                <p>When utilizing the services, you will pay us the applicable amount, plus applicable sales tax, up front using our third-party payment services, in which case we will charge your credit card or other third-party account according to your user agreement with your credit card, and you hereby authorize us to charge your credit card for such amounts and to submit such charge to our third-party payment processor. We will not be liable for any tax or withholding.</p>
        <h3>GOVERNING LAW</h3>
                <p>These terms shall be governed by and construed in accordance with the laws of the state of Utah excluding its conflicts of law rules. for all purposes of these terms, the parties consent to exclusive jurisdiction and venue in the united states federal courts or state courts located in the county of West Jordan, state of Utah.</p>
        <h3>MISCELLANEOUS</h3>
               <P>These terms are the entire agreement between you and us with respect to the site and services, and supersede all prior or contemporaneous communications and proposals (whether oral, written or electronic) between you and us with respect thereto. if any provision of these terms is found to be unenforceable or invalid, that provision will be limited or eliminated to the minimum extent necessary so that these terms will otherwise remain in full force and effect and enforceable. The failure of either party to exercise in any respect any right provided for herein shall not be deemed a waiver of any further rights hereunder. Waiver of compliance in any particular instance does not mean that we will waive compliance in the future. In order for any waiver of compliance with these terms to be binding, we must provide you with written notice of such waiver. These terms are personal to you, and are not assignable, transferable or sublicensable by you except with our prior written consent. We may assign, transfer or delegate any of our rights and obligations hereunder without consent. No agency, partnership, joint venture, or employment relationship is created as a result of these terms and neither party has any authority of any kind to bind the other in any respect. Any right not expressly granted herein is reserved by us. Unless otherwise specified in these terms, all notices under these terms will be in writing and will be deemed to have been duly given when received, if personally delivered or sent by certified or registered mail, return receipt requested; when receipt is electronically confirmed, if transmitted by facsimile or e-mail; or the day after it is sent, if sent for next day delivery by recognized overnight delivery service. If you believe that any content on the site belonging to us has been copied in any way that constitutes copyright infringement, or your intellectual property right have been otherwise violated, please notify us of such claims. 
</P>
            </div>
        </section>
@else
<section class="terms">
        <div class="container">
            <h2>Terms and Conditions For Providers</h2>
            <div class="heading">
          
                    <h6>1. PROVIDER TERMS</h6>
                    <h6>2. INDEPENDENT CONTRACTOR AGREEMENT</h6>
                    <h6>3. THIS INDEPENDENT CONTRACTOR AGREEMENT (this 'Agreement') is between Seazoned, LLC ('us', 'we' or 'our'), a Utah limited liability company, ('you', or 'your') company.</h6>
            
            </div>
        </div>
        <section class="purpose-text">
            <div class="container">

                <h4>PURPOSE</h4>

                <p>We own and run a mobile application known as “Seazoned”, which allows our customers to purchase on- demand lawn care and home solutions (the 'Services').</p>
                <ul>
                    <li>
                        You are in the business of providing the public with the Services.</li>
                    <li>
                        You are interested in performing the Services for our customers on the terms set out in this Agreement.</li>
                </ul>
                <p>and in our policies and procedures manual as we provide to you from time to time.</p>
            </div>
        </section>
        <section class="conditions">
            <div class="container">
                <h4>AGREEMENT</h4>
                <p><span class="text">Term :</span> The term (the “Term”) of this Agreement commences on the date both we and you sign and will remain in effect until terminated: (i) by us at any time for whatever reason or (ii) by you at any time for whatever reason.</p>
                <p><span class="text">Compensation :</span> When you receive a service request from a Seazoned customer and accept the service (a “Project”), you will receive payment in the amount that you listed on your profile for pricing specification after completion of the service (a “project”) by the next business day.</p>
                <p><span class="text">Confidentiality :</span> The Seazoned mobile application (the “App”) is our property. Information you learn about the App is our “Confidential Information”. You aren’t permitted to disclose any Confidential Information to third parties or use it in any way except to perform the Services under this Agreement for our customers. During this Agreement, and afterward for 12 months, you are also not permitted to, directly or indirectly, advise or solicit or encourage any of our customers to reduce its business with us, or to offer to provide any of our customers with services that are similar to the Seazoned services. Any violation of this paragraph would cause damages to us, and we will be allowed to restrain this conduct.</p>

                <p><span class="text">Independent Contractor :</span> You are an independent contractor, not an employee or representative of ours. We won’t provide any benefits to you such as workers’ compensation, insurance, or unemployment insurance. You must pay all of your own taxes and fees, such as FICA, income tax, unemployment insurance, disability insurance and workers’ compensation. This Agreement doesn’t make you our partner, and you won’t have authority to take any action to the contrary. You’re not permitted to make any public statements about us or on behalf of us about the Services. Seazoned, LLC You understand that we use many different contractors to perform the Services, so this Agreement doesn’t guarantee you any particular amount of income.</p>

                <p><span class="text">Insurance :</span> You must obtain insurance policies satisfactory to us, and maintain them during the Term. At a minimum you must carry workers compensation, commercial auto insurance and general liability insurance for damage to people and property in amounts acceptable to us. The policies must name us as an additional insured, on a primary and non-contributory basis, including waiver of subrogation. You can’t cancel any policy without notifying us 30 days in advance. </p>

                <p><span class="text">Indemnity :</span> You will indemnify and defend us, as well as our employees, members and managers, from all liabilities and expenses in connection with your performance of the Services, your breach of any representation or warranty under this Agreement, and your violation of any law applicable to the Services. It is your sole responsibility to know and comply with all laws, codes and regulations regarding the Services. You must notify us if we are required to obtain any permits or licenses, and you may not perform Services until all licenses and permits have been obtained by you.</p>

                <p><span class="text">Disclaimer :</span> We can’t attest to the identity, character or trustworthiness of any of our customers, and we won’t have any responsibility or liability to you arising from interaction with any customer (other than payments we owe you under in Section 2), such as hazards you encounter on a customer’s premises or dangerous conditions that exist. IN NO EVENT WILL WE BE LIABLE FOR ANY DAMAGES, DIRECT, INDIRECT, GENERAL, SPECIAL, COMPENSATORY, CONSEQUENTIAL, AND/OR INCIDENTAL, WHETHER WE HAVE BEEN ADVISED OF THEM IN ADVANCE, RELATING TO THE SERVICES. THIS LIMITATION INCLUDES ALL CLAIMS UNDER CONTRACT, TORT, STRICT LIABILITY, NEGLIGENCE OR ANY OTHER THEORY.</p>
               <P>No Assignment. You may not assign or transfer this Agreement</P>

                <p>Amendments. This Agreement may only be altered by written agreement of you and us.</p>
                
                <P><span class="text">Notices :</span> Notices will be considered received on the date of delivery. All notices required under this Agreement must be in writing and sent using one of the following methods:
                   <ul>
                       <li> Hand delivered in person or mailed by certified mail to:
                        <br>Seazoned, LLC
                        <br>2700 W 9306 S
                        <br>West Jordan, UT 84088
                    </li>


                       <li>E-mailed- <a href="info@seazoned.com">info@seazoned.com</a></li></ul></P>

                    <P><span class="text">General Provisions :</span> This Agreement will be governed by the laws of Utah, regardless of conflicts of laws principles. All rights under this Agreement are cumulative with all other rights a party might have. Failure by us to exercise any remedy will not be considered a waiver of any rights. This Agreement is the entire agreement and understanding of you and us regarding the Services and replaces any prior understanding, including but not limited to any previous Independent Contractor Agreement. If any provision in this Agreement is unenforceable, this Agreement will be construed as if such provision was not included. The courts in Utah will be the sole location for all disputes arising under this Agreement, and each party waives the right to trial by jury.</P>


            </div>
        </section>

    </section>
    @endif

@endsection