<?php
include_once "header.php";
require_once TADTOOLS_PATH . '/tcpdf/tcpdf.php';
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');

$pdf = new TCPDF("P", "mm", "A4");
$pdf->setPrintHeader(false); //不要頁首
$pdf->setPrintFooter(false); //不要頁尾
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //設定自動分頁
$pdf->setFontSubsetting(true); //產生字型子集（有用到的字才放到文件中）
$pdf->SetMargins(15, 15); //設定頁面邊界，

$club_year = system_CleanVars($_REQUEST, 'club_year', '0', 'int');
$reg_all   = get_reg_uid_all($club_year);

$i = 1;
foreach ($reg_all as $reg_uid => $reg) {
    $myts = MyTextSanitizer::getInstance();
    if ($i % 2) {
        $pdf->AddPage(); //新增頁面，一定要有，否則內容出不來
    }

    $pdf->SetFont('droidsansfallback', '', 20, '', true); //設定字型
    $pdf->Cell(180, 12, "{$reg['class']} {$reg['name']} 社團報名繳費單", 0, 1, 'C', 0);
    $pdf->SetFont('droidsansfallback', '', 10, '', true); //設定字型

    $height = 10;
    //標題
    $pdf->MultiCell(40, $height, "社團名稱", 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
    $pdf->MultiCell(20, $height, "報名者", 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
    $pdf->MultiCell(20, $height, "社團學費", 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
    $pdf->MultiCell(20, $height, "額外費用", 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
    $pdf->MultiCell(20, $height, "是否候補", 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
    $pdf->MultiCell(20, $height, "是否繳費", 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
    $pdf->MultiCell(40, $height, "報名日期", 1, 'C', false, 1, '', '', false, 0, false, false, $height, 'M', true);

    $money = $fee = 0;

    foreach ($reg['data'] as $class_id => $data) {

        $reg_name     = $myts->htmlSpecialChars($data['reg_name']);
        $class_title  = $myts->htmlSpecialChars($data['class_title']);
        $class_money  = $myts->htmlSpecialChars($data['class_money']);
        $class_fee    = $myts->htmlSpecialChars($data['class_fee']);
        $reg_isreg    = $myts->htmlSpecialChars($data['reg_isreg']);
        $reg_isfee    = $myts->htmlSpecialChars($data['reg_money']);
        $reg_datetime = $myts->htmlSpecialChars($data['reg_datetime']);

        $pdf->MultiCell(40, $height, $class_title, 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
        $pdf->MultiCell(20, $height, $reg_name, 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
        $pdf->MultiCell(20, $height, $class_money, 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
        $pdf->MultiCell(20, $height, $class_fee, 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
        $pdf->MultiCell(20, $height, $class_isreg ? '是' : '否', 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
        $pdf->MultiCell(20, $height, $class_isfee ? '是' : '否', 1, 'C', false, 0, '', '', false, 0, false, false, $height, 'M', true);
        $pdf->MultiCell(40, $height, $reg_datetime, 1, 'C', false, 1, '', '', false, 0, false, false, $height, 'M', true);

        $money += $class_money;
        $fee += $class_fee;
        // $pdf->Cell(50, $height, $username, 1, 0,'C',0,'',1);
        // $pdf->Cell(36, $height, $snews['update_time'], 1, 1,'C',0,'',1);
    }
    $pdf->Cell(25, 10, '總學費金額：', 0, 0);
    $pdf->Cell(30, 10, $money, 'B', 0, 0);
    $pdf->Cell(20, 10, '額外加收：', 0, 0);
    $pdf->Cell(30, 10, $fee, 'B', 0, 0);
    // $pdf->Image('images/aa.png', 180, 250, 20, 20, 'png');

    $pdf->SetFont('droidsansfallback', '', 16, '', true); //設定字型
    $pdf->Cell(20, 10, '簽名：', 0, 0);
    $pdf->Cell(50, 10, '', 'B', 0, 0);

    $pdf->SetXY(15, 148);
    $pdf->Cell(180, 1, '', 'T', 1, '');

    $pdf->SetXY(15, 164);

    $i++;
}

$club_year_text = club_year_to_text($club_year);
$title          = iconv('utf-8', 'big5', $club_year_text . '社團報名繳費單');
//PDF內容設定
$pdf->Output($title . '.pdf', 'D');
