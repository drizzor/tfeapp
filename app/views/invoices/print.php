<?php 
// render('header', ['title' => 'Facture']); 
ob_start();
?>  

<style>
    * { color:#717375; }
    table {
        border-collapse: collapse;         
        width:100%;          
        font-size:11pt; 
        font-family:helvetica;
        line-height: 5mm;
        letter-spacing: 1px;
        }
    #header tr td { vertical-align: top; }
    table b { color: #000000; }   
    h1 { color: #009fe3; font-weight: none;} 
    h2, h3 { color: #000000;  margin:0; padding:0 } 
    #logo { width: 45%; }
    td.right { text-align: right; }
    table.border td { border:1px solid #CFD1D2; padding:3mm 1mm; }
    table td.border { border:1px solid #CFD1D2; padding:3mm 1mm; }
    table.border th { background:#b3b3b3; color:#141414; font-weight:normal; border:1px solid #FFF; padding:2mm 1mm; }
    .lightgreyBg1 { background:#b3b3b3; color:#141414; }
    .lightgreyBg2 { background:#e6e6e6; color:#141414; }
    .noborder { border: none; }
    #footer, #footer table td, footer table td b { font-size: 9pt; }
    .logo {width: 90px ;}
    .tva { text-align: right; background: #e6e6e6; border: 1px solid #e6e6e6;}
    .none {background: transparent; border: 1px solid transparent;}

</style>

<page backtop="20mm" backleft="10mm" backright="10mm" backbottom="30mm" footer="page; date;">
    
    <page_footer id="footer">
        <hr>
        <h3 style="margin-bottom:3mm">Service Clients</h3>
        Pour toute question concernant votre facture, contactez le service Clients.
        <table style="margin-top:2mm; margin-left:8mm">  
        <tr>            
            <td style="width:75%;">                                
                <b style='color:#717375;'>E-mail :</b> demo@company.be<br/>
                <b style='color:#717375;'>Téléphone :</b> 0485/99.99.99
            </td>
        </tr>
    </table>
    </page_footer>
    <!-- <bookmark title="Informations" level="0"></bookmark> -->
    <table id="header"> 
        <tr>
            <th style="width:75%;"><img class="logo" src="<?= $_SERVER['DOCUMENT_ROOT'] ?>/TFEAPP/public/images/company/logo.png" id="logo" alt=""></th><br>
            <th style="width:25%;"><h1>FACTURE</h1></th>
        </tr>   
        <tr>            
            <td style="width:75%;">                
                <b>Company Name</b><br/>                
                7190 - Ecaussinnes<br/>
                Rue Ernest Martel, 6<br/>
                BE 0999 999 999<br/>
                <b>IBAN : </b>BE68 5390 0754 7034
            </td>
            <td style="width:25%;">
               <b><?= $data['customer'] ?></b><br/>
               <?= $data['customer_zipcode'] . ' ' . $data['customer_city'] ?><br>
               <?= $data['customer_address'] ?><br>
               <?= $data['customer_country'] ?><br>
               <b>TVA : </b> <?= ($data['customer_tva_number'])? $data['customer_tva_number'] : "N/A" ?>
            </td>
        </tr>
    </table>

    <table style="vertical-align:bottom;margin-top:20mm; margin-bottom:3mm;">
        <tr>
            <td style="width:50%"><h2>Facture <?= $data['inv_year'] . ' / Nr. ' . $data['invoice_number'] ?></h2></td>   
            <td class="right" style="width:50%">Facturé le <?= $data['invoice_date'][2] .'/'. $data['invoice_date'][1] .'/'. $data['invoice_date'][0] ?></td>
        </tr>
    </table>

    <!-- <bookmark title="Détails" level="0"></bookmark> -->
    <table class="border" style="margin-bottom: 8mm;">
        <thead>
            <tr>
                <th style="width:50%">Description</th>
                <th style="width:10%">Qte</th>
                <th style="width:20%">Prix / U</th>
                <th style="width:20%">Total</th>
            </tr>
        </thead>   
        <tbody>
            <?php 
            $total_notax_amount = $total_amount = 0;
            for($i = 0; $i < $data['rowCount']; $i++): ?>   
                <tr>   
                    <?php
                    
                        $total_notax_amount +=  $data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity'];
                        $total_amount += ($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity']) * (1 + ($data['inv'][$i]['tax'] / 100));

                    ?>
                    <td><?= $data['inv'][$i]['invoice_description'] ?></td>
                    <td><?= $data['inv'][$i]['quantity'] ?></td>
                    <td><?= number_format($data['inv'][$i]['notax_amount'], 2, ',', '.') ?> €</td>
                    <td><?= number_format($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity'], 2, ',', '.') ?> €</td>                                    
                </tr>
            <?php endfor; ?>
                <?php if($data['tax_amount_0'] > 0): ?>
                    <tr>
                        <td class="none" colspan="2"></td>
                        <th class="tva">0 %</th>
                        <td class="tva"><?= number_format($data['tax_amount_0'], 2, ',', '.') ?> €</td>
                    </tr>
                <?php endif; ?>
                <?php if($data['tax_amount_6'] > 0): ?>
                    <tr>
                        <td class="none" colspan="2"></td>
                        <th class="tva">6 %</th>
                        <td class="tva"><?= number_format($data['tax_amount_6'], 2, ',', '.') ?> €</td>
                    </tr>
                <?php endif; ?>
                <?php if($data['tax_amount_12'] > 0): ?>
                    <tr>
                        <td class="none" colspan="2"></td>
                        <th class="tva">12 %</th>
                        <td class="tva"><?= number_format($data['tax_amount_12'], 2, ',', '.') ?> €</td>
                    </tr>
                <?php endif; ?>
                <?php if($data['tax_amount_21'] > 0): ?>
                    <tr>
                        <td class="none" colspan="2"></td>
                        <th class="tva">21 %</th>
                        <td class="tva"><?= number_format($data['tax_amount_21'], 2, ',', '.') ?> €</td>
                    </tr>
                <?php endif; ?>
        </tbody>     
    </table>

    <!-- <bookmark title="Montants" level="0"></bookmark> -->
    <table class="border">
        <tr>
            <td class="noborder" style="width:40%"></td>
            <td class="right lightgreyBg1" style="width:40%;">Montant HTVA</td>
            <td class="right lightgreyBg2" style="width:20%;"><?= number_format($total_notax_amount, 2, ',', '.') ?> €</td>
        </tr>

        <tr>
            <td class="noborder" style="width:40%"></td>
            <td class="right lightgreyBg1" style="width:40%;">TVA</td>
            <td class="right lightgreyBg2" style="width:20%;"><?= number_format($total_amount - $total_notax_amount, 2, ',', '.') ?> €</td>
        </tr>

        <tr>
            <td class="noborder" style="width:40%"></td>
            <td class="right lightgreyBg1" style="width:40%;">Montant TVAC</td>
            <td class="right lightgreyBg2" style="width:20%;"><?= number_format($total_amount, 2, ',', '.') ?> €</td>
        </tr>
    </table>

    <?php if(!empty($data['comment'])): ?>    
    <p>
        <b>Notation: </b> <?= $data['comment'] ?>
    </p>
    <?php endif; ?>    
</page>

<?php
$content = ob_get_clean();

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try{
    $pdf = new Html2Pdf('P', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullpage');
    $pdf->writeHTML($content);
    // $pdf->output('test.pdf', F);
    // $pdf->output('test.pdf', 'D');
    // $pdf->output($data['company'].'_invoice_'.$data['invoice_number'].'.pdf');
    $filePath = $_SERVER['DOCUMENT_ROOT'].'/TFEAPP/public/inv/' . $data['inv_year'] . '_MYCOMPANY_invoice_'.$data['invoice_number'].'.pdf';
    $fileName = $data['inv_year'] .'_MYCOMPANY_invoice_'.h($data['invoice_number']).'.pdf';
    if(!file_exists($filePath)){
        $pdf->output($filePath, 'F');
        header('Location: '. URLROOT . '/public/inv/'. $fileName);
    }else{
        header('Location: '. URLROOT . '/public/inv/'. $fileName);
        exit;
    }
    
}
catch(Html2Pdf_exception $e){
    die($e);
}