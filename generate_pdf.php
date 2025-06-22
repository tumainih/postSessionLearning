<?php

require_once __DIR__ . '/vendor/autoload.php';

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Post Session Learning System');
$pdf->SetAuthor('System Documentation');
$pdf->SetTitle('Post Session Learning System - Complete Guide');
$pdf->SetSubject('System Documentation and User Guide');

// Set default header data
$pdf->SetHeaderData('', 0, 'Post Session Learning System', 'Complete System Guide and Documentation');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page
$pdf->AddPage();

// Read the markdown content
$markdownContent = file_get_contents(__DIR__ . '/documentation/system_guide.md');

// Convert markdown to HTML (simple conversion)
$html = convertMarkdownToHtml($markdownContent);

// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output('Post_Session_Learning_System_Guide.pdf', 'D');

function convertMarkdownToHtml($markdown) {
    $html = $markdown;
    
    // Convert headers
    $html = preg_replace('/^### (.*$)/m', '<h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">$1</h3>', $html);
    $html = preg_replace('/^## (.*$)/m', '<h2 style="color: #34495e; background-color: #ecf0f1; padding: 10px; border-left: 4px solid #3498db;">$1</h2>', $html);
    $html = preg_replace('/^# (.*$)/m', '<h1 style="color: #2c3e50; text-align: center; border-bottom: 3px solid #3498db; padding-bottom: 10px;">$1</h1>', $html);
    
    // Convert bold
    $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
    
    // Convert italic
    $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);
    
    // Convert code blocks
    $html = preg_replace('/```(.*?)```/s', '<pre style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px; font-family: monospace;">$1</pre>', $html);
    
    // Convert inline code
    $html = preg_replace('/`(.*?)`/', '<code style="background-color: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace;">$1</code>', $html);
    
    // Convert lists
    $html = preg_replace('/^- (.*$)/m', '<li style="margin: 5px 0;">$1</li>', $html);
    $html = preg_replace('/^(\d+)\. (.*$)/m', '<li style="margin: 5px 0;">$2</li>', $html);
    
    // Wrap lists in ul/ol tags
    $html = preg_replace('/(<li.*<\/li>)/s', '<ul style="margin: 10px 0; padding-left: 20px;">$1</ul>', $html);
    
    // Convert links
    $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" style="color: #3498db;">$1</a>', $html);
    
    // Convert emojis to text
    $html = str_replace('📋', '📋', $html);
    $html = str_replace('🎯', '🎯', $html);
    $html = str_replace('🗄️', '🗄️', $html);
    $html = str_replace('📁', '📁', $html);
    $html = str_replace('👥', '👥', $html);
    $html = str_replace('⭐', '⭐', $html);
    $html = str_replace('🔄', '🔄', $html);
    $html = str_replace('🧭', '🧭', $html);
    $html = str_replace('🔧', '🔧', $html);
    $html = str_replace('👨‍🎓', '👨‍🎓', $html);
    $html = str_replace('⚙️', '⚙️', $html);
    $html = str_replace('🔍', '🔍', $html);
    $html = str_replace('📚', '📚', $html);
    $html = str_replace('✅', '✅', $html);
    $html = str_replace('❌', '❌', $html);
    $html = str_replace('🗑️', '🗑️', $html);
    $html = str_replace('💡', '💡', $html);
    $html = str_replace('🔒', '🔒', $html);
    $html = str_replace('🌐', '🌐', $html);
    $html = str_replace('🎉', '🎉', $html);
    
    // Convert paragraphs
    $html = preg_replace('/\n\n([^<].*)/', '<p style="margin: 10px 0; line-height: 1.6;">$1</p>', $html);
    
    // Add styling
    $html = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">' . $html . '</div>';
    
    return $html;
}
?> 