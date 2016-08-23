<?php
					require("fpdf17/fpdf.php");
					$pdf = new FPDF( );
					$pdf->AddPage();
					$pdf->SetFont('Times','',9);
					$pdf->Cell(10,3,'Saurav is my Brother!', 0,1,'L');
					$pdf->Cell(10,5,'He rocks in us - great man!', 0,1,'L');
					$pdf->Output("filename.pdf","F");
?>