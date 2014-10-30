<?php


namespace Dothiv\BusinessBundle\Tests\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\BusinessBundle\Service\WhoisReportParser;

class WhoisReportParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group WHOIS
     * @group Service
     * @group BusinessBundle
     */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceOf('\Dothiv\BusinessBundle\Service\WhoisReportParser', $this->createTestObject());
    }

    /**
     * @test
     * @group   WHOIS
     * @group   Service
     * @group   BusinessBundle#
     * @depends itShouldBeInstantiable
     */
    public function itShouldParseAWhoisReport()
    {
        $whoisReport = <<<EOF
Domain Name:TLD.HIV 
Domain ID: D2852788-AGRS 
Creation Date: 2014-07-17T21:24:35Z 
Updated Date: 2014-09-01T16:51:22Z 
Registry Expiry Date: 2015-07-17T21:24:35Z 
Sponsoring Registrar:TLD dotHIV Registry GmbH (R3355-AGRS) 
Sponsoring Registrar IANA ID: 9998 
WHOIS Server: 
Referral URL: 
Domain Status: ok 
Registrant ID:REGISTRY 
Registrant Name:TLD dotHIV Registry GmbH 
Registrant Organization:TLD dotHIV Registry GmbH 
Registrant Street: Melchiorstrasse 26 
Registrant City:Berlin 
Registrant State/Province:Berlin 
Registrant Postal Code:10179 
Registrant Country:DE 
Registrant Phone:+49.3091564707 
Registrant Phone Ext: 
Registrant Fax: +49.3055229087 
Registrant Fax Ext: 
Registrant Email:domains@tld.hiv 
Admin ID:REGISTRY 
Admin Name:TLD dotHIV Registry GmbH 
Admin Organization:TLD dotHIV Registry GmbH 
Admin Street: Melchiorstrasse 26 
Admin City:Berlin 
Admin State/Province:Berlin 
Admin Postal Code:10179 
Admin Country:DE 
Admin Phone:+49.3091564707 
Admin Phone Ext: 
Admin Fax: +49.3055229087 
Admin Fax Ext: 
Admin Email:domains@tld.hiv 
Tech ID:REGISTRY 
Tech Name:TLD dotHIV Registry GmbH 
Tech Organization:TLD dotHIV Registry GmbH 
Tech Street: Melchiorstrasse 26 
Tech City:Berlin 
Tech State/Province:Berlin 
Tech Postal Code:10179 
Tech Country:DE 
Tech Phone:+49.3091564707 
Tech Phone Ext: 
Tech Fax: +49.3055229087 
Tech Fax Ext: 
Tech Email:domains@tld.hiv 
Name Server:NS-CLOUD-E1.GOOGLEDOMAINS.COM 
Name Server:NS-CLOUD-E2.GOOGLEDOMAINS.COM 
Name Server:NS-CLOUD-E3.GOOGLEDOMAINS.COM 
Name Server:NS-CLOUD-E4.GOOGLEDOMAINS.COM 
Name Server: 
Name Server: 
Name Server: 
Name Server: 
Name Server: 
Name Server: 
Name Server: 
Name Server: 
Name Server: 
DNSSEC:Unsigned 

Access to WHOIS information is provided to assist persons in determining the contents of a domain name registration record in the registry database. The data in this record is provided by The Registry Operator for informational purposes only, and accuracy is not guaranteed. This service is intended only for query-based access. You agree that you will use this data only for lawful purposes and that, under no circumstances will you use this data to(a) allow, enable, or otherwise support the transmission by e-mail, telephone, or facsimile of mass unsolicited, commercial advertising or solicitations to entities other than the data recipient's own existing customers; or (b) enable high volume, automated, electronic processes that send queries or data to the systems of Registry Operator, a Registrar, or Afilias except as reasonably necessary to register domain names or modify existing registrations. All rights reserved. Registry Operator reserves the right to modify these terms at any time. By submitting this query, you agree to abide by this policy.
EOF;
        $report      = $this->createTestObject()->parse($whoisReport);
        $this->assertEquals('TLD.HIV', $report->get('Domain Name'));
        $this->assertEquals('TLD dotHIV Registry GmbH', $report->get('Registrant Name'));
        $this->assertEquals('domains@tld.hiv', $report->get('Registrant Email'));
        $nameservers = new ArrayCollection(array(
            'NS-CLOUD-E1.GOOGLEDOMAINS.COM',
            'NS-CLOUD-E2.GOOGLEDOMAINS.COM',
            'NS-CLOUD-E3.GOOGLEDOMAINS.COM',
            'NS-CLOUD-E4.GOOGLEDOMAINS.COM'
        ));
        $this->assertEquals($nameservers, $report->get('Name Server'));
    }

    /**
     * @return WhoisReportParser
     */
    protected function createTestObject()
    {
        return new WhoisReportParser();
    }
} 
