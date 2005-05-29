<?php
require('include/fpdf/fpdf.php');
require_once('modules/Orders/SalesOrder.php');

$id = $_REQUEST['record'];
global $adb;
//retreiving the invoice info
$focus = new SalesOrder();
$focus->retrieve_entity_info($_REQUEST['record'],"SalesOrder");
$account_name = getAccountName($focus->column_fields[account_id]);
$iData[] = $account_name;
$iData[] = $id;
$iData[] = date('Y-m-d');

//setting the billing address
$bdata[] = $account_name;
$bdata[] = $focus->column_fields["bill_street"];
$bdata[] = $focus->column_fields["bill_city"];
$bdata[] = $focus->column_fields["bill_state"];
$bdata[] = $focus->column_fields["bill_code"];
$bdata[] = $focus->column_fields["bill_country"];

//setting the shipping address
$sdata[] = $account_name;
$sdata[] = $focus->column_fields["ship_street"];
$sdata[] = $focus->column_fields["ship_city"];
$sdata[] = $focus->column_fields["ship_state"];
$sdata[] = $focus->column_fields["ship_code"];
$sdata[] = $focus->column_fields["ship_country"];

//getting the Product Data
$query="select products.productname,products.unit_price,soproductrel.* from soproductrel inner join products on products.productid=soproductrel.productid where salesorderid=".$id;

$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
for($i=1;$i<=$num_rows;$i++)
{
	$temp_data = Array();
        $productname=$adb->query_result($result,$i-1,'productname');
        $unitprice=$adb->query_result($result,$i-1,'unit_price');
        $productid=$adb->query_result($result,$i-1,'productid');
        $qty=$adb->query_result($result,$i-1,'quantity');
        $listprice=$adb->query_result($result,$i-1,'listprice');
        $total = $qty*$listprice;

	$temp_data[] = $productname;
	$temp_data[] = $qty;
	$temp_data[] = $unitprice;
	$temp_data[] = $listprice;
	$temp_data[] = $total;
	$iDataDtls[] = $temp_data;
	
}
//getting the Total Array
$price_total[] = $focus->column_fields["hdnSubTotal"];
$price_total[] = $focus->column_fields["txtTax"];
$price_total[] = $focus->column_fields["hdnGrandTotal"];

class PDF extends FPDF
{

// Invoice Title
function setInvoiceTitle($title)
{
	if($title != "")
	{
		$this->SetFillColor(224,235,255);
    		$this->SetTextColor(0);
    		$this->SetFont('','B',18);
    		$this->Cell(0,10,$title,0,0,'C',0);
		$this->Ln();
	}
}
//Invoice Address
function setAddress($billing="",$shipping="")
{
	$this->Ln();
	$this->SetFillColor(224,235,255);
 	$this->SetTextColor(0);
    	$this->SetFont('','B',10);
	$this->Cell(140,10,"Billing Address",0,0,'L',0);
 	$this->Cell(0,10,"Shipping Address",0,0,'L',0);
	for($i=0;$i<count($billing);$i++)
	{
		$this->Ln();
		$this->SetFont('','',10);
		$this->Cell(140,5,$billing[$i],0,0,'L',0);
		$this->Cell(0,5,$shipping[$i],0,0,'L',0);
	}

}
//Invoice from
function setInvoiceDetails($iHeader,$iData)
{
    $this->Ln();
    $this->SetFillColor(162,200,243);
    $this->SetTextColor(0);
    $this->SetDrawColor(61,121,206);
    $this->SetLineWidth(.3);
    $this->SetFont('Arial','B',10);
    //Header
    foreach($iHeader as $value)
    {
        $this->Cell(63,7,$value,1,0,'C',1);
    }
    $this->Ln();
    $this->SetFillColor(233,241,253);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    $fill=0;
    foreach($iData as $value)
    {
		$this->Cell(63,6,$value,1,0,'C',0);
    }
    $this->Ln();
}
//Product Details
function setProductDetails($ivHeader,$ivData)
{
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->SetFillColor(162,200,243);
    $this->SetTextColor(0);
    $this->SetDrawColor(61,121,206);
    $this->SetLineWidth(.3);
    $this->SetFont('Arial','B',10);
    //Header
    foreach($ivHeader as $value)
    {
        $this->Cell(38,7,$value,1,0,'C',1);
    }
    $this->Ln();
    $this->SetFillColor(233,241,253);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    $fill=0;
    	foreach($ivData as $key=>$value)
	{
    		foreach($value as $ivalue)
    		{
			$this->Cell(38,6,$ivalue,1,0,'C',0);	
    		}
		$this->Ln();
	}
    $this->Ln();
}

function setTotal($price_total="")
{
	$this->Ln();
	$this->SetFillColor(224,235,255);
 	$this->SetTextColor(0);
    	$this->SetFont('','B',10);
 	$this->Cell(0,8,"Sub Total: ".$price_total[0],0,0,'C',0);
    	$this->Ln();
 	$this->Cell(0,8,"Tax: ".$price_total[1],0,0,'C',0);
    	$this->Ln();
 	$this->Cell(0,8,"Total: ".$price_total[2],0,0,'C',0);
	

}
}
//$bdata = array("aaaaaaaaa","48/1,Katcherry Street","Rasipuram","Namakkal (D.T)");
//$sdata = array("bbbbbb","48/9","","mmmm","Don City");
$iHead = array("Company","Sales Order No.","Date");
$iHeadDtls = array("Product Name","Quantity","List Price","Unit Price","Total");
//$iDataDtls = array(array("P101010","vtigerCRM","2","$100","$200"),array("P101010","vtigerCRM","2","$100","$200"));

$pdf = new PDF('P','mm','A4');
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->setInvoiceTitle("Sales Order");
$pdf->setInvoiceDetails($iHead,$iData);
$pdf->setAddress($bdata,$sdata);
$pdf->setProductDetails($iHeadDtls,$iDataDtls);
$pdf->setTotal($price_total);
$pdf->Output();
?>
