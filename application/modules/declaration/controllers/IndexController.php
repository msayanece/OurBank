<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
?>
<?php
class Declaration_IndexController extends Zend_Controller_Action
{
	public function init() 
	{
        $this->view->pageTitle = $this->view->translate('Declaration');
        $this->view->title = 'Accounting';
        $this->view->adm = new App_Model_Adm();
        $this->view->dbobj = new Declaration_Model_Dec();
	}

	public function indexAction() 
	{

        $declarationform = new Declaration_Form_Account();
        $this->view->form = $declarationform;

        $dec = $this->view->adm->viewRecord("ourbank_declaration","id","DESC");
                foreach($dec as $dec){
                        $declarationform->age->addMultiOption($dec['id'],$dec['name']);
                }
//         //submit action
         if ($this->_request->isPost() && $this->_request->getPost('Submit')) {
//                 $this->view->groupcode = $memcode = $this->_request->getParam('groupcode');
                $formData = $this->_request->getPost();
if($declarationform->isValid($formData)){
                $this->view->membercode = $memcode = $this->_request->getParam('membercode');

//if group
        if(substr($memcode,4,1) == 2 or substr($memcode,4,1) == 3)
        {
                $this->view->groupresult=$result = $this->view->dbobj->getmembers($memcode); // 
                if($result)
                { 
                 $declarationform->populate($result[0]);
                $this->view->groupcode = $groupcode=$result[0]['groupcode'];
//                 echo $groupcode;
                $this->view->relation =$result =$this->view->dbobj->getrelations($groupcode);


  }
// if individual
 } else 
  {
         $this->view->result=$result = $this->view->dbobj->getmember($memcode); // 
         if($result)
         {
         $declarationform->populate($result[0]);
        $this->view->membercode1 = $familyid=$result[0]['family_id'];
        $this->view->relation=$result =$this->view->dbobj->getrelation($familyid);
            }
         }
  }
}

}
public function reportdisplayAction() {
		$app = $this->view->baseUrl();
		$word=explode('/',$app);
		$projname = $word[1];
		$this->_helper->layout->disableLayout();
		$file1 = $this->_request->getParam('file'); 
		$this->view->filename = "/".$projname."/reports/".$file1;
	}


function pdfgenerationAction() {
  $declarationform = new Declaration_Form_Account();
        $this->view->form = $declarationform;
          $this->view->membercode = $memcode = $this->_request->getParam('membercode');
        if(substr($memcode,4,1) == 2 or substr($memcode,4,1) == 3)
        {
                $this->view->groupresult=$result = $this->view->dbobj->getmembers($memcode); // 
                if($result)
                { 
                 $declarationform->populate($result[0]);
                $this->view->groupcode = $groupcode=$result[0]['groupcode'];
                $this->view->relation =$result =$this->view->dbobj->getrelations($groupcode);


  }
 } else 
  {
         $this->view->result=$result = $this->view->dbobj->getmember($memcode); // 
         if($result)
         {
         $declarationform->populate($result[0]);
        $this->view->membercode1 = $familyid=$result[0]['family_id'];
        $this->view->relation=$result =$this->view->dbobj->getrelation($familyid);

                $dbobj = new Declaration_Model_Dec();
 		
		$app = $this->view->baseUrl();
		$word=explode('/',$app);
		$projname = $word[1];
		
		$title1 = "declaration";
		$this->view->pageTitle = $title1;

		  $pdf = new Zend_Pdf();
		$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
		$pdf->pages[] = $page;
		// Image
		$image_name = "/var/www/".$projname."/public/images/logo.jpg";
		$image = Zend_Pdf_Image::imageWithPath($image_name);
		//$page->drawImage($image, 25, 770, 570, 820);
	
	   $page->drawImage($image, 30, 770, 130, 820);
		$page->setLineWidth(1)->drawLine(25, 25, 570, 25); //bottom horizontal
		$page->setLineWidth(1)->drawLine(25, 25, 25, 820); //left vertical
		$page->setLineWidth(1)->drawLine(570, 25, 570, 820); //right vertical
		$page->setLineWidth(1)->drawLine(570, 820, 25, 820); //top horizonta

                // define font resource
                $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                // Image
                $image_name = "/var/www/".$projname."/public/images/logo.jpg";
                $image = Zend_Pdf_Image::imageWithPath($image_name);

            $Declaration = new Declaration_Model_Dec();
            $memcode=$this->_request->getParam('membercode');
            $familyid=$this->_request->getParam('membercode1');
            $showgetmember = $Declaration->getmember($memcode); 
            $showgetrelation = $Declaration->getrelation($familyid);
$dateconvert= new App_Model_dateConvertor();
foreach($this->view->result as $result) { }

                // write text to page
                $page->setFont($font, 12)
                    ->drawText('DECLARATION', 240, 720);
//                 $page->setLineWidth(1)->drawLine(0, 800, 820, 250);

                $page->setFont($font, 10)
                ->drawText('(TO BE SUBMITTED BY THE BORROWER UNDER SBI JOINT LIABILITY GROUP)', 72, 700);
                 $page->setFont($font, 9)
                    ->drawText('I,'.$result['name'].' (Name of the borrower), Son of ' .$this->view->relation[0]['fathername']. '',72, 670);
                $page->setFont($font, 9)
                    ->drawText('Aged around ' .$result['age']. ' years,presently residing at ' .$result['street']. ' do here by',72, 655);
                $page->setFont($font, 9)
                    ->drawText('Solemnly affirm and sincerely state on Oath as follows:',72, 625);
                $page->setFont($font, 9)
                    ->drawText('i)  I propose to avail a crop loan under SBI JLG scheme against hypothecation of the crop which the loan is to be sanctioned.',72, 605);
                $page->setFont($font, 9)
                ->drawText('ii) In this connection, I confirm that and declare that I am land less labourer / share cropper /tenant farmer /oral lessee',72, 585);
                 $page->setFont($font, 9)
                ->drawText('( Stricke out which ever not applicable ).',80, 575);
                $page->setFont($font, 9)
                ->drawText('iii) I hereby declare and confirm furture that the properties mentioned in the schedule to the affidavit is the property which',72, 555); 
                $page->setFont($font, 9)
                 ->drawText('is the subject matter of lease (Oral /written) in my favour for year to year or for period of  '. $dateconvert->normalformat($result['created_date']). '',80, 545);
                 $page->setFont($font, 9)
                ->drawText('year as mentioned in the document and the lease is presently in force and Sri ' .$result['landowner_name']. ' is the lesser and ',80, 535);
                $page->setFont($font, 9)
                ->drawText('the owner of the property (a copy of the lease deed is enclosed).',80, 525);

                $page->setFont($font, 9)
                ->drawText('iv)I hereby declare and confirm further that I have not committed any default in paying the lease amount to the lesser and',72,505);
                $page->setFont($font, 9)
                ->drawText('have not committed any breach of the terms and conditions of the lease.Moreover,I declare further  that there are no',80,495);
                $page->setFont($font, 9)
                ->drawText('arrears of any lease amount.',80,485);
                $page->setFont($font, 9)
                ->drawText('v) I have also not resorted to outside borrowing against security of the present crop which is the subject matter of the bank',72, 465);
                $page->setFont($font, 9)
                ->drawText('finance.The crop to be raised is free from the charge/encumbrances.',80, 455);

                // add page to document
                $pdf->pages[] = $page;

                $pdfData = $pdf->render();
		$pdfData = $pdf->render();
		$pdf->save('/var/www/'.$projname.'/reports/declaration.pdf');
		$path = '/var/www/'.$projname.'/reports/declaration.pdf';
		chmod($path,0777);
//                 $this->_redirect('/declaration/index');
	}
}
}
}